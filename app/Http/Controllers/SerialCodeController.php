<?php

namespace App\Http\Controllers;

use App\Models\SerialCode;
use App\Services\SerialCodeGenerator;
use Illuminate\Http\Request;

class SerialCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = SerialCode::query();
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        if (in_array($request->query('status'), ['used', 'unused'], true)) {
            $query->where('status', $request->query('status'));
        }
        $serialCodes = $query->latest()->paginate(12)->withQueryString();
        $total = SerialCode::count();
        $used = SerialCode::used()->count();
        $unused = SerialCode::unused()->count();
        return view('serial-codes.index', compact('serialCodes', 'total', 'used', 'unused'));
    }

    public function store(Request $request, SerialCodeGenerator $generator)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
            'notes' => ['nullable', 'string', 'max:160'],
        ]);
        $codes = $generator->generate((int) $validated['quantity'], $validated['notes'] ?? null);
        return redirect()->route('serial-codes.index')->with('generated_codes', collect($codes)->pluck('code')->all())->with('success', count($codes) . ' serial code berhasil dibuat.');
    }

    public function show(SerialCode $serialCode)
    {
        return response()->json($serialCode);
    }

    public function export(Request $request)
    {
        $query = SerialCode::query();
        if (in_array($request->query('status'), ['used', 'unused'], true)) $query->where('status', $request->query('status'));
        $rows = $query->orderBy('created_at')->get(['code', 'status', 'customer_name', 'notes', 'created_at', 'used_at']);
        $filename = 'serial-codes-' . now()->format('Ymd-His') . '.csv';
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Serial Code', 'Status', 'Customer', 'Notes', 'Created At', 'Used At']);
            foreach ($rows as $row) fputcsv($out, [$row->code, $row->status, $row->customer_name, $row->notes, $row->created_at, $row->used_at]);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
