<?php

namespace App\Services;

use App\Models\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class FormService
{
    public function storeForm(Request $request, $userId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|numeric|digits:10',
        ]);

        return Form::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'address' => $validated['address'],
            'contact_number' => $validated['contact_number'],
        ]);
    }

    public function getForms($userId)
    {
        return Form::where('user_id', $userId)->get();
    }

    public function generatePdf($formId, $userId)
    {
        $form = Form::findOrFail($formId);
        if ($form->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }
        $pdf = Pdf::loadView('forms.pdf', compact('form'));
        $filename = 'form_' . $formId . '.pdf';
        $path = storage_path('app/pdfs/' . $filename);
        $pdf->save($path);
        return $path;
    }

    public function getPdfPath($formId, $userId)
    {
        $form = Form::findOrFail($formId);
        if ($form->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }
        return storage_path('app/pdfs/form_' . $formId . '.pdf');
    }

    public function generateQrCode($formId, $userId)
    {
        $form = Form::findOrFail($formId);
        if ($form->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }
        return QrCode::size(200)->generate(json_encode($form->toArray()));
    }
}
