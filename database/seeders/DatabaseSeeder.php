<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Platform Settings
        \App\Models\Setting::set('currency_symbol', '₹');
        \App\Models\Setting::set('currency_code', 'INR');
        \App\Models\Setting::set('platform_name', 'Master SaaS Engine');

        // 1. Create Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@platform.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );

        // 2. Create Master Agency: Apex Master Ventures
        $masterAgency = Agency::updateOrCreate(
            ['email' => 'rahul@apexmaster.com'],
            [
                'name' => 'Apex Master Ventures',
                'slug' => 'apex-master-ventures',
                'type' => 'master',
                'owner_name' => 'Rahul Sharma',
                'custom_domain' => 'app.apexmaster.com',
                'status' => 'active',
                'max_clients' => 500,
            ]
        );

        // Create Master Admin User: Rahul Sharma
        $masterUser = User::updateOrCreate(
            ['email' => 'rahul@apexmaster.com'],
            [
                'name' => 'Rahul Sharma',
                'password' => Hash::make('master123'),
                'role' => 'master_agency',
                'agency_id' => $masterAgency->id,
                'status' => 'active',
            ]
        );
    }
}
