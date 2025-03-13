<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class DocumentOcrController extends Controller
{
    public function convertPdfToSearchable(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:pdf|max:5120', // Max 5MB file size
        ]);

        // Get original filename
        $originalFilename = $request->file('file')->getClientOriginalName();
        $filenameWithoutExt = pathinfo($originalFilename, PATHINFO_FILENAME);

        // Define directories for input and output
        $inputDir = storage_path('app/public/temp/');
        $outputDir = storage_path('app/public/output/');

        // Ensure directories exist
        try {
            if (!file_exists($inputDir)) {
                mkdir($inputDir, 0755, true);
            }
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create directories: ' . $e->getMessage(),
            ], 500);
        }

        // Store the uploaded file temporarily
        $pdfPath = $inputDir . '/' . uniqid() . '.pdf';
        try {
            $request->file('file')->move($inputDir, basename($pdfPath));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to move uploaded file: ' . $e->getMessage(),
            ], 500);
        }

        // Path for the output file - use unique ID for processing but keep original name for download
        $searchablePdfPath = $outputDir . '/' . uniqid() . '.pdf';

        // Set environment variables with more complete paths
        $env = [
            'PATH' => '/usr/local/bin:/usr/bin:/bin:/opt/local/bin:/opt/homebrew/bin',
            'TMPDIR' => $outputDir // Explicitly set temp directory
        ];

        // Run OCRmyPDF to convert to searchable PDF
        $ocrProcess = new Process([
            'ocrmypdf',
            '--force-ocr',
            '--output-type',
            'pdf',
            '--deskew', // Straighten tilted pages
            '--clean', // Clean before OCR
            '--jobs',
            '2', // Limit parallel processing
            $pdfPath,
            $searchablePdfPath
        ]);

        $ocrProcess->setEnv($env);
        $ocrProcess->setTimeout(300); // 5 minute timeout
        $ocrProcess->run();

        // Clean up input file
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        if (!$ocrProcess->isSuccessful()) {
            return response()->json([
                'success' => false,
                'error' => 'OCR process failed',
                'details' => $ocrProcess->getErrorOutput(),
            ], 500);
        }

        // Ensure the file exists before attempting to download
        if (!file_exists($searchablePdfPath)) {
            return response()->json([
                'success' => false,
                'error' => 'File not found after conversion',
            ], 500);
        }

        // Generate a URL for the downloadable file
        $downloadUrl = Storage::url('output/' . basename($searchablePdfPath));

        // Return the URL for the downloadable file
        return Inertia::render('Services/Pdf2Pdf', [
            'downloadUrl' => $downloadUrl,
        ]);
    }


    public function uploadAndConvertToText(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the uploaded image
        $imagePath = $request->file('image')->store('uploads', 'public');

        // Get the full path to the stored image
        $fullImagePath = storage_path('app/public/' . $imagePath);

        // Define the output text file path
        $outputTextPath = storage_path('app/public/text_outputs/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.txt');

        // Ensure the output directory exists
        if (!file_exists(dirname($outputTextPath))) {
            mkdir(dirname($outputTextPath), 0777, true);
        }

        // Run Tesseract OCR command
        $process = new Process(['tesseract', $fullImagePath, $outputTextPath]);
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Read the generated text file
        $textContent = file_get_contents($outputTextPath . '.txt');

        // Optionally, save the text content to the database or return it
        // Example: Save to database
        // YourModel::create(['text_content' => $textContent]);

        // Return the text content as a response
        return response()->json([
            'success' => true,
            'text' => $textContent,
            'text_file_url' => Storage::url('text_outputs/' . basename($outputTextPath) . '.txt'),
        ]);
    }

    private function convertPdfToDocx($pdfPath, $outputDir)
    {
        // Path for the DOCX output file
        $docxPath = $outputDir . uniqid() . '.docx';

        // Set environment variables
        $env = [
            'PATH' => '/usr/local/bin:/usr/bin:/bin:/opt/local/bin:/opt/homebrew/bin',
            'TMPDIR' => $outputDir
        ];

        // Convert PDF to DOCX using LibreOffice
        $convertProcess = new Process([
            'libreoffice',
            '--headless',
            '--convert-to',
            'docx',
            $pdfPath,
            '--outdir',
            $outputDir
        ]);

        $convertProcess->setEnv($env);
        $convertProcess->run();

        if (!$convertProcess->isSuccessful()) {
            throw new ProcessFailedException($convertProcess);
        }

        return $docxPath;
    }
    function removeFile(Request $request)
    {
        if ($request->url) {
            Storage::delete($request->url);
        }
    }
}
