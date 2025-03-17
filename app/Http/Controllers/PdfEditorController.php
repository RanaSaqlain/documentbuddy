<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PdfEditorController extends Controller
{
    public function index()
    {
        return Inertia::render('Services/PdfEditor/Upload');
    }

    public function edit($id)
    {
        $document = Storage::get("public/pdfs/{$id}.pdf");
        return Inertia::render('Services/PdfEditor/Editor', [
            'documentId' => $id,
            'documentUrl' => Storage::url("pdfs/{$id}.pdf"),
        ]);
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240', // 10MB max
            ]);

            $uniqueId = Str::uuid();
            $path = $request->file('file')->storeAs(
                'public/pdfs',
                $uniqueId . '.pdf'
            );

            return response()->json([
                'success' => true,
                'documentId' => $uniqueId,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Upload failed. Please try again.'
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $request->validate([
                'documentId' => 'required|string',
                'changes' => 'required|array',
            ]);

            // Save PDF changes logic here
            // This will depend on your PDF manipulation library

            return response()->json([
                'success' => true,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Save failed. Please try again.'
            ], 500);
        }
    }

    public function performOcr(Request $request)
    {
        try {
            $request->validate([
                'documentId' => 'required|string',
                'pageNumber' => 'required|integer',
            ]);

            // OCR logic here using Tesseract or other OCR tool

            return response()->json([
                'success' => true,
                'text' => $extractedText,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'OCR failed. Please try again.'
            ], 500);
        }
    }
} 