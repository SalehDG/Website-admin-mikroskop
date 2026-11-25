<?php

namespace App\Services;

use App\Models\SerialCode;
use Illuminate\Support\Str;
use RuntimeException;

class SerialCodeGenerator
{
    public function generate(int $quantity, ?string $notes = null): array
    {
        if ($quantity < 1 || $quantity > 500) {
            throw new RuntimeException('Jumlah serial harus antara 1 dan 500.');
        }

        $created = [];
        for ($i = 0; $i < $quantity; $i++) {
            $created[] = $this->createUnique($notes);
        }
        return $created;
    }

    private function createUnique(?string $notes): SerialCode
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $code = 'DN-MBD-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            try {
                return SerialCode::create(['code' => $code, 'status' => 'unused', 'notes' => $notes]);
            } catch (\Illuminate\Database\QueryException $e) {
                if (!in_array((string) $e->getCode(), ['23000', '23505'], true) && !str_contains(strtolower($e->getMessage()), 'duplicate') && !str_contains(strtolower($e->getMessage()), 'unique')) {
                    throw $e;
                }
            }
        }
        throw new RuntimeException('Gagal membuat serial unik. Silakan coba kembali.');
    }
}
