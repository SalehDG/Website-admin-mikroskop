<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SerialCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    public function activate(Request $request)
    {
        $data = $request->validate(['serial_code' => ['required', 'string', 'max:32'], 'device_id' => ['required', 'string', 'max:255'], 'device_name' => ['nullable', 'string', 'max:120']]);
        $deviceHash = hash('sha256', $data['device_id']);
        $result = DB::transaction(function () use ($data, $deviceHash) {
            $serial = SerialCode::where('code', $data['serial_code'])->lockForUpdate()->first();
            if (!$serial) return ['ok' => false, 'code' => 'SERIAL_NOT_FOUND', 'message' => 'Serial code tidak ditemukan.', 'status' => 404];
            if ($serial->status === 'used' && !hash_equals((string) $serial->device_id_hash, $deviceHash)) return ['ok' => false, 'code' => 'SERIAL_ALREADY_USED', 'message' => 'Serial code sudah digunakan perangkat lain.', 'status' => 409];
            $serial->status = 'used';
            $serial->device_id_hash = $deviceHash;
            $serial->device_name = $data['device_name'] ?? null;
            $serial->used_at = $serial->used_at ?? now();
            $serial->save();
            return ['ok' => true, 'code' => 'SERIAL_ACTIVATED', 'message' => 'Serial code berhasil diaktivasi.', 'serial' => $serial];
        });
        if (!$result['ok']) return response()->json(['success' => false, 'code' => $result['code'], 'message' => $result['message']], $result['status']);
        return response()->json(['success' => true, 'code' => $result['code'], 'message' => $result['message'], 'data' => ['serial_status' => $result['serial']->status, 'activated_at' => $result['serial']->used_at]]);
    }

    public function validateSerial(Request $request)
    {
        $data = $request->validate(['serial_code' => ['required', 'string', 'max:32'], 'device_id' => ['required', 'string', 'max:255']]);
        $serial = SerialCode::where('code', $data['serial_code'])->first();
        if (!$serial) return response()->json(['success' => false, 'code' => 'SERIAL_NOT_FOUND', 'message' => 'Serial code tidak ditemukan.'], 404);
        if ($serial->status !== 'used' || !hash_equals((string) $serial->device_id_hash, hash('sha256', $data['device_id']))) return response()->json(['success' => false, 'code' => 'DEVICE_MISMATCH', 'message' => 'Serial code belum aktif pada perangkat ini.'], 403);
        $serial->forceFill(['last_validated_at' => now()])->save();
        return response()->json(['success' => true, 'code' => 'SERIAL_VALID', 'data' => ['status' => $serial->status, 'validated_at' => $serial->last_validated_at]]);
    }

    public function deactivate(Request $request)
    {
        $data = $request->validate(['serial_code' => ['required', 'string', 'max:32'], 'device_id' => ['required', 'string', 'max:255']]);
        $serial = SerialCode::where('code', $data['serial_code'])->first();
        if (!$serial) return response()->json(['success' => false, 'code' => 'SERIAL_NOT_FOUND'], 404);
        if ($serial->status !== 'used' || !hash_equals((string) $serial->device_id_hash, hash('sha256', $data['device_id']))) return response()->json(['success' => false, 'code' => 'DEVICE_MISMATCH'], 403);
        $serial->update(['status' => 'unused', 'device_id_hash' => null, 'device_name' => null, 'used_at' => null]);
        return response()->json(['success' => true, 'code' => 'SERIAL_DEACTIVATED', 'message' => 'Binding perangkat dilepas.']);
    }
}
