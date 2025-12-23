<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\BDMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function show(BDM $employee)
    {
        $employee->load('documents');
        return view('admin.documents.show', compact('employee'));
    }

    public function upload(Request $request, BDM $employee)
    {
        $request->validate([
            'document_type' => 'required|in:aadhaar_card,pan_card,10th_admit_card,12th_marksheet,graduation_certificate,last_company_offer_letter,salary_slip,reference_contact',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $documentPath = $request->file('document_file')->store('bdm/documents', 'public');

        BDMDocument::create([
            'bdm_id' => $employee->id,
            'document_type' => $request->document_type,
            'file_path' => $documentPath,
            'original_filename' => $request->file('document_file')->getClientOriginalName(),
            'uploaded_at' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully!');
    }

    public function download(BDMDocument $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Document not found!');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_filename);
    }

    public function destroy(BDMDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully!');
    }
}
