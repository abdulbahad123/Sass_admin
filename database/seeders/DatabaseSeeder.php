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
        User::updateOrCreate(
            ['email' => 'admin@platform.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );

        // 2. Create Master Agency: Wezan Technologies / KKK Master
        $masterAgency = Agency::updateOrCreate(
            ['email' => 'abdulbahad.dev@gmail.com'],
            [
                'name' => 'KK Master Agency',
                'slug' => 'kk-master-agency',
                'type' => 'master',
                'owner_name' => 'Mohamed Sharieef',
                'custom_domain' => 'app.kkmaster.com',
                'status' => 'active',
                'max_clients' => 500,
            ]
        );

        User::updateOrCreate(
            ['email' => 'abdulbahad.dev@gmail.com'],
            [
                'name' => 'Mohamed Sharieef',
                'password' => Hash::make('password'),
                'role' => 'master_agency',
                'agency_id' => $masterAgency->id,
                'status' => 'active',
            ]
        );

        // 3. Create White Label Agency: ABC Digital Agency
        $whiteLabelAgency = Agency::updateOrCreate(
            ['email' => 'priya@abcdigital.com'],
            [
                'name' => 'ABC Digital Agency',
                'slug' => 'abc-digital-agency',
                'type' => 'white_label',
                'parent_id' => $masterAgency->id,
                'owner_name' => 'Priya Patel',
                'custom_domain' => 'app.abcdigital.com',
                'primary_color' => '#3b82f6',
                'status' => 'active',
                'max_clients' => 50,
            ]
        );

        User::updateOrCreate(
            ['email' => 'priya@abcdigital.com'],
            [
                'name' => 'Priya Patel',
                'password' => Hash::make('password'),
                'role' => 'white_label_agency',
                'agency_id' => $whiteLabelAgency->id,
                'status' => 'active',
            ]
        );
    }
}
