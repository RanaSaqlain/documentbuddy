<?php

namespace App\Http\Controllers;

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
                'file' => 'required|mimes:pdf|max:5120', // Max 5MB file size
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
            $uniqueId = Str::uuid();
            $pdfPath = "{$inputDir}/{$fileName}_{$uniqueId}.pdf";
            $docxPath = "{$outputDir}/{$fileName}_{$uniqueId}.docx";
            // Store the uploaded file
            $request->file('file')->move($inputDir, basename($pdfPath));
            $libreoffice = "/usr/local/bin/soffice";
            // Prepare the conversion process
            $command = [
                $libreoffice,
                '--headless',
                '--infilter=writer_pdf_import',
                '--convert-to',
                'docx:MS Word 2007 XML',
                '--outdir',
                $outputDir,
                $pdfPath
            ];
            Log::info('Running command: ' . implode(' ', $command)); // Log the running command
            $process = new Process($command);

            $process->setWorkingDirectory($inputDir);
            $process->setTimeout(300); // 5 minutes
            $process->run();

            // Clean up the input file
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Check if output file exists
            if (!file_exists($docxPath)) {
                throw new \Exception('Output file not found after conversion');
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
                'error' => 'Conversion failed. Please try again'
            ]);
        }
    }
}
