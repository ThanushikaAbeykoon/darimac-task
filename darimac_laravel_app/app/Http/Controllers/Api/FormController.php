<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\Dompdf\Facade\Pdf;

class FormController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->forms()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
        ]);

        $form = $request->user()->forms()->create([
            'name' => $request->name,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        return response()->json($form, 201);
    }

    public function downloadPdf(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        if ($form->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pdf = Pdf::loadView('pdf.form', ['form' => $form]);
        return $pdf->download('form_' . $id . '.pdf');
    }

    public function getPdfUrl(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        if ($form->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pdf = Pdf::loadView('pdf.form', ['form' => $form]);
        $path = 'pdfs/form_' . $id . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        $url = Storage::disk('public')->url($path);

        return response()->json(['url' => $url]);
    }

    public function getQrCode(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        if ($form->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $qrData = json_encode([
            'id' => $form->id,
            'name' => $form->name,
            'address' => $form->address,
            'contact_number' => $form->contact_number,
        ]);
        $qrCode = QrCode::format('png')->size(200)->generate($qrData);
        return response($qrCode)->header('Content-Type', 'image/png');
    }

    public function getQrCodeUrl(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        if ($form->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $qrData = json_encode([
            'id' => $form->id,
            'name' => $form->name,
            'address' => $form->address,
            'contact_number' => $form->contact_number,
        ]);
        $path = 'qrcodes/form_' . $id . '.png';
        Storage::disk('public')->put($path, QrCode::format('png')->size(200)->generate($qrData));
        $url = Storage::disk('public')->url($path);

        return response()->json(['url' => $url]);
    }
}
