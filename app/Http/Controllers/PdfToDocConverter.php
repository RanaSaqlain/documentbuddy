<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfToDocConverter extends Controller
{
    public function convertToDoc(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240', // Max 10MB file size
            ]);

            // Get original filename and sanitize it
            $originalFilename = $request->file('file')->getClientOriginalName();
            $fileName = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME));

            // Define directories
            $inputDir = storage_path('app/public/temp');
            $outputDir = storage_path('app/public/output');

            // Ensure directories exist
            if (!Storage::exists('public/temp')) {
                Storage::makeDirectory('public/temp');
            }
            if (!Storage::exists('public/output')) {
                Storage::makeDirectory('public/output');
            }

            // Generate unique filenames
            $uniqueId = config("app.name") . '_' . Carbon::now();
            $pdfPath = "{$inputDir}/{$fileName}_{$uniqueId}.pdf";
            $ocrPdfPath = "{$inputDir}/{$fileName}_{$uniqueId}_ocr.pdf";
            $docxPath = "{$outputDir}/{$fileName}_{$uniqueId}.docx";

            // Store the uploaded file
            $request->file('file')->move($inputDir, basename($pdfPath));

            // Step 1: First, ensure the PDF has selectable text using OCRmyPDF
            $ocrCommand = [
                '/opt/local/bin/ocrmypdf',
                '--skip-text',           // Skip pages that already contain text
                '--deskew',              // Fix skewed pages
                '--clean',               // Clean the image
                '--optimize',
                '3',       // Optimize output
                '--output-type',
                'pdfa', // Convert to PDF/A format for better compatibility
                '--language',
                'eng',     // OCR language
                $pdfPath,
                $ocrPdfPath
            ];

            $process = new Process($ocrCommand);
            $process->setTimeout(600);
            $process->run();

            // Even if OCR fails, we'll try direct conversion with the original PDF
            $sourceFile = file_exists($ocrPdfPath) ? $ocrPdfPath : $pdfPath;

            // Instead of LibreOffice's direct PDF import, first extract text with pdftotext
            $textPath = "{$inputDir}/{$fileName}_{$uniqueId}.txt";
            $extractCommand = [
                '/opt/local/bin/pdftotext',
                '-layout',       // Maintain layout
                '-nopgbrk',      // No page breaks
                $sourceFile,
                $textPath
            ];

            $process = new Process($extractCommand);
            $process->setTimeout(300);
            $process->run();
            if (!$process->isSuccessful() || !file_exists($textPath)) {
                Log::info("failed at extraction of text");
                throw new \Exception('Unable to extract text from file');
            }
            // if (!$process->isSuccessful() || !file_exists($textPath) || true ) {
            // If text extraction fails, attempt direct conversion with LibreOffice
            $libreoffice = "/usr/local/bin/soffice"; // Adjust path as needed

            $convertCommand = [
                $libreoffice,
                '--headless',
                '--convert-to',
                'docx:MS Word 2007 XML',
                '--outdir',
                $outputDir,
                $textPath
            ];

            Log::info('Running LibreOffice command: ' . implode(' ', $convertCommand));

            $process = new Process($convertCommand);
            $process->setTimeout(600);
            $process->run();
            // } else {

            //     // Convert text to DOCX using Pandoc for better formatting
            //     $pandocCommand = [
            //         '/opt/local/bin/pandoc',
            //         $textPath,
            //         '-o',
            //         $docxPath
            //     ];
            //     Log::info(implode(' ', $pandocCommand)); // Log the command as a string
            //     $process = new Process($pandocCommand);
            //     $process->setTimeout(300); // Set timeout for the process
            //     $process->run();
            //     $process->setTimeout(300);
            //     $process->run();
            // }

            // Find the output file
            if (!file_exists($docxPath)) {
                // LibreOffice might have named it differently
                $baseName = basename($sourceFile, '.pdf');
                $possibleDocxPath = "{$outputDir}/{$baseName}.docx";

                if (file_exists($possibleDocxPath)) {
                    $docxPath = $possibleDocxPath;
                } else {
                    throw new \Exception('Output file not found after conversion');
                }
            }

            // Clean up temporary files
            foreach ([$pdfPath, $ocrPdfPath, $textPath] as $tempFile) {
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }

            // Generate download URL
            $downloadUrl = Storage::url("output/" . basename($docxPath));

            return Inertia::render('Services/PdfToDoc', [
                'success' => true,
                'downloadUrl' => $downloadUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('PDF to DOC conversion failed: ' . $e->getMessage());

            return Inertia::render('Services/PdfToDoc', [
                'success' => false,
                'error' => 'Conversion failed: ' . $e->getMessage()
            ]);
        }
    }
}
