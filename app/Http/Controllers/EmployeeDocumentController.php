<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeDocumentController extends Controller
{
    private function getEmployee()
    {
        return auth()->user()->employee;
    }

    public function index()
    {
        $employee = $this->getEmployee();

        abort_if(!$employee, 403, 'Employee profile not found.');

        $documents = EmployeeDocument::where('employee_id', $employee->id)
            ->latest()
            ->get();

        return view('employee_portal.documents', compact('documents'));
    }

    public function store(Request $request)
    {
        $employee = $this->getEmployee();

        abort_if(!$employee, 403, 'Employee profile not found.');

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:Aadhaar Card,PAN Card,Education Certificate,Experience Letter,Other',
            ],
            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $file = $request->file('document');

        $filename = Str::uuid() . '.' . $file->extension();

        $path = $file->storeAs(
            'employee-documents/' . $employee->id,
            $filename,
            'local'
        );

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'document_type' => $validated['document_type'],
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('employee.documents')
            ->with('success', 'Document uploaded successfully.');
    }

    public function download(EmployeeDocument $document)
    {
        $employee = $this->getEmployee();

        abort_if(!$employee, 403);

        abort_unless(
            $document->employee_id === $employee->id,
            403,
            'You cannot access this document.'
        );

        abort_unless(
            Storage::disk('local')->exists($document->file_path),
            404,
            'Document file not found.'
        );

        return Storage::disk('local')->download(
            $document->file_path,
            basename($document->original_name)
        );
    }
}
