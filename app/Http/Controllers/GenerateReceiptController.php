<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateReceiptController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Accept markdown content in the request and convert to HTML
        $data = $request->validate([
            'markdown' => 'required|string',
            // optional: if you want to still accept structured data
            'save' => 'nullable|boolean',
            // optional: custom file name
            'file_name' => 'nullable|string',
        ]);

        try {
            // Normalize and convert newlines so incoming text preserves line breaks
            $markdown = $data['markdown'];

            // Convert literal "\\n" sequences into real newlines
            $markdown = str_replace('\\n', "\n", $markdown);

            // Normalize Windows/Mac line endings to LF
            $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);

            // Convert single newlines (not paragraph breaks) into markdown hard breaks
            $markdown = preg_replace('/(?<!\n)\n(?!\n)/', "  \n", $markdown);

            $html = Str::markdown($markdown);

            // Wrap HTML with basic styles for PDF
            $fullHtml = '<!doctype html><html><head><meta charset="utf-8"><style>' .
                'body{font-family: DejaVu Sans, Arial, sans-serif; font-size:14px; line-height:1.4}' .
                'img{max-width:100%}' .
                '</style></head><body>' . $html . '</body></html>';

            $pdf = Pdf::loadHTML($fullHtml);

            $file_name = (str($data['file_name'])->slug() ?? 'receipt-'.time()) . '.pdf';

            // If save=true, store and return public URL
            if (!empty($data['save'])) {
                $filename = 'receipts/' . $file_name;
                Storage::disk('public')->put($filename, $pdf->output());
                $url = Storage::disk('public')->url($filename);
                return response()->json(['url' => $url]);
            }

            // Default: stream PDF to browser
            return $pdf->stream($file_name);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
