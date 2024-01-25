<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Filepond;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function show($id)
    {
        $file = Filepond::where('id', $id)->first();

        return response()->file($file->path_doc);
    }

    public function showThumbnail($id)
    {
        $file = Filepond::where('id_file', $id)->first();

        return response()->file($file->path_doc);
    }

    public function generateId($id_file)
    {

        $file = Filepond::select('id', 'register')->where('id_file', $id_file)->get();

        return response()->json($file);
    }

    public function convert($id)
    {
        $files = Dokumen::with([
            'filepond' => function ($query) {
                $query->latest();
            }
        ])
            ->find($id);

        $img = $files->filepond[0];

        return view('pdf.convert', compact('files'));
    }

    public function convertDomPdf($id)
    {

        $files = Dokumen::with([
            'filepond' => function ($query) {
                $query->latest();
            }
        ])
            ->find($id);

        $pdf = Pdf::loadView('pdf.convert-dompdf', compact('files'));

        return $pdf->download($files->register . '_' . $files->mr . '_' . $files->nama_dok . '.pdf');
    }

    public function pdfTypeDownload($id)
    {
        $file = Filepond::where('id_file', $id)->first();

        return response()->download($file->path_doc);
    }
}
