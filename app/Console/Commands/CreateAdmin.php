<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {name} {email} {password}';
    protected $description = 'Create an administrator account for the License Management System';

    public function handle(): int
    {
        $email = $this->argument('email');
        if (User::where('email', $email)->exists()) {
            $this->components->error('Admin dengan email tersebut sudah ada.');
            return self::FAILURE;
        }
        User::create([
            'name' => $this->argument('name'),
            'email' => $email,
            'password' => Hash::make($this->argument('password')),
            'role' => 'admin',
        ]);
        $this->components->success('Administrator berhasil dibuat.');
        return self::SUCCESS;
    }
}
