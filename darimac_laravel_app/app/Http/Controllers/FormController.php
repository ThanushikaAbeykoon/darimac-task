<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Services\FormService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormController extends Controller
{
    protected $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    public function create()
    {
        $forms = $this->formService->getForms(auth()->id());
        return view('dashboard', compact('forms'));
    }

    public function store(Request $request)
    {
        $form = $this->formService->storeForm($request, auth()->id());
        $qrCode = $this->formService->generateQrCode($form->id, auth()->id());
        return view('forms.show', compact('form', 'qrCode'));
    }

    public function downloadPdf($id)
    {
        $path = $this->formService->getPdfPath($id, auth()->id());
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="form_' . $id . '.pdf"',
        ]);
    }
}
