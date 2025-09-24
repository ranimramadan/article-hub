<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL'); // ضعي ADMIN_EMAIL بالـ .env إن حابة تختاري مستخدم بعينه

        if ($email) {
            $user = User::where('email', $email)->first();
            if (! $user) {
                $this->command->warn("No user found with ADMIN_EMAIL={$email}. Creating one...");
                $password = Str::random(12);
                $user = User::create([
                    'name' => 'Admin',
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);
                $this->command->info("Admin user created: {$email} / password: {$password}");
            }
        } else {
            $user = User::first();
            if (! $user) {
                $this->command->warn('No users found. Creating default admin: admin@example.com');
                $password = 'password'; // للتجربة فقط
                $user = User::create([
                    'name' => 'Admin',
                    'email' => 'admin@example.com',
                    'password' => Hash::make($password),
                ]);
                $this->command->info("Admin user created: admin@example.com / password: {$password}");
            }
        }

        // عيّن دور الأدمن
        if ($user && ! $user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->command->info("Assigned 'admin' role to user: {$user->email}");
        }
    }
}
