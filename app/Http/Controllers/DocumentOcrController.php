<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Inertia\Inertia;

class DocumentOcrController extends Controller
{
    public function convertPdfToDoc(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:pdf|max:5120', // Max 5MB file size
        ]);

        // Define directories for input and output
        $inputDir = storage_path('app/public/temp');
        $outputDir = storage_path('app/public/output');

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

        // Paths for output files
        $ocrPdfPath = $outputDir . uniqid() . '.pdf';
        $docxPath = $outputDir . uniqid() . '.docx';

        // Set environment variables with more complete paths
        $env = [
            'PATH' => '/usr/local/bin:/usr/bin:/bin:/opt/local/bin:/opt/homebrew/bin',
            'TMPDIR' => $outputDir // Explicitly set temp directory
        ];

        // Run OCRmyPDF with additional options
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
            $ocrPdfPath
        ]);

        $ocrProcess->setEnv($env);
        $ocrProcess->setTimeout(300); // 5 minute timeout
        $ocrProcess->run();

        if (!$ocrProcess->isSuccessful()) {
            unlink($pdfPath);
            return Inertia::render('Pdf2Doc', [
                'error' => 'OCR process failed',
                'details' => $ocrProcess->getErrorOutput(),
            ]);
        }

        // Clean up input file after successful OCR
        unlink($pdfPath);

        // Step 2: Convert OCR-processed PDF to DOCX
        $convertProcess = new Process([
            'libreoffice',
            '--headless',
            '--convert-to',
            'docx',
            $ocrPdfPath,
            '--outdir',
            $outputDir
        ]);
        $convertProcess->setEnv($env);
        $convertProcess->run();

        if (!$convertProcess->isSuccessful()) {
            // Clean up OCR output file
            unlink($ocrPdfPath);
            return Inertia::render('Pdf2Doc', [
                'error' => 'PDF to DOCX conversion failed',
                'details' => $convertProcess->getErrorOutput(),
            ]);
        }

        // Clean up OCR output file after successful conversion
        unlink($ocrPdfPath);

        // Step 3: Return the converted DOCX file
        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}
