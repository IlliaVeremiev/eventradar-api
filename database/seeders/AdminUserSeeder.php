<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = new User();

        $admin->email = 'admin@example.com';

        $admin->first_name        = 'Super';
        $admin->last_name         = 'Admin';
        $admin->password          = Hash::make('nimda');
        $admin->is_active         = true;
        $admin->email_verified_at = now();

        $this->command->info('Admin user created: admin@example.com / nimda');
    }
}
