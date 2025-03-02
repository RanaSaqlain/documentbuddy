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
        if (!file_exists($inputDir)) {
            mkdir($inputDir, 0755, true);
        }
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
    
        // Store the uploaded file temporarily
        $pdfPath = $inputDir . '/' . uniqid() . '.pdf';
        $request->file('file')->move($inputDir, basename($pdfPath));
    
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
        unlink($pdfPath);
    
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
        return Inertia::render('Pdf2Doc', [
            'downloadUrl' => $downloadUrl,
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
}
