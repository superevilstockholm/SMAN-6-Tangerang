<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

// Models
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate([
            'email' => config('admin.email'),
        ], [
            'name' => config('admin.name'),
            'email' => config('admin.email'),
            'password' => Hash::make(config('admin.password')),
            'role' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        // Student / Teacher
        User::factory(50)->create();
    }
}
