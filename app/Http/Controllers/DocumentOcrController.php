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
        try {
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
            if (!Storage::exists('public/temp')) {
                Storage::makeDirectory('public/temp');
            }
            if (!Storage::exists('public/output')) {
                Storage::makeDirectory('public/output');
            }

            // Store the uploaded file temporarily
            $pdfPath = $inputDir . '/' . uniqid() . '.pdf';
            $request->file('file')->move($inputDir, basename($pdfPath));

            // Path for the output file
            $searchablePdfPath = $outputDir . '/' . uniqid() . '.pdf';

            // Set environment variables
            $env = [
                'PATH' => '/usr/local/bin:/usr/bin:/bin:/opt/local/bin:/opt/homebrew/bin',
                'TMPDIR' => $outputDir
            ];

            // Run OCRmyPDF
            $ocrProcess = new Process([
                'ocrmypdf',
                '--force-ocr',
                '--output-type',
                'pdf',
                '--deskew',
                '--clean',
                '--jobs',
                '2',
                $pdfPath,
                $searchablePdfPath
            ]);

            $ocrProcess->setEnv($env);
            $ocrProcess->setTimeout(300);
            $ocrProcess->run();

            // Clean up input file
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            if (!$ocrProcess->isSuccessful()) {
                throw new ProcessFailedException($ocrProcess);
            }

            if (!file_exists($searchablePdfPath)) {
                throw new \Exception('Output file not found after conversion');
            }

            // Generate download URL
            $downloadUrl = Storage::url('output/' . basename($searchablePdfPath));

            return Inertia::render('Services/Pdf2Pdf', [
                'success' => true,
                'downloadUrl' => $downloadUrl,
            ]);

        } catch (\Exception $e) {
            \Log::error('PDF to searchable conversion failed: ' . $e->getMessage());
            
            return Inertia::render('Services/Pdf2Pdf', [
                'success' => false,
                'error' => 'Conversion failed. Please try again.'
            ]);
        }
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
            'text' => nl2br($textContent),
        ]);
    }
    
    function removeFile(Request $request)
    {
        if ($request->url) {
            Storage::delete($request->url);
        }
    }
}
