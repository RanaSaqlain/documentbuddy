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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the uploaded image in the temp folder
        $imagePath = $request->file('file')->store('temp', 'public');
        // Get the full path to the stored image
        $fullImagePath = storage_path('app/public/' . $imagePath);

        // Define the output text file path in the output folder
        $outputTextPath = storage_path('app/public/output/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.txt');

        // Ensure the output directory exists
        if (!file_exists(dirname($outputTextPath))) {
            mkdir(dirname($outputTextPath), 0777, true);
        }

        // Run Tesseract OCR command
        $tesseractPath = env('TESSERACT_PATH'); 
        $process = new Process([$tesseractPath, $fullImagePath, $outputTextPath]);
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Read the generated text file
        $textContent = file_get_contents($outputTextPath . '.txt');

        // Clean up the input file
        if (file_exists($fullImagePath)) {
            unlink($fullImagePath);
        }

        // Clean up the output text file
        if (file_exists($outputTextPath . '.txt')) {
            unlink($outputTextPath . '.txt');
        }
        // Return the text content as a response using Inertia
        return Inertia::render('Services/ImageToText', [
            'success' => true,
            'text' => $textContent,
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
