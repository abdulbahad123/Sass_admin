<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $username = env('SUPER_ADMIN_USERNAME', 'X9_SecOps#GaTeWaY_2026');
        $email = env('SUPER_ADMIN_EMAIL', 'X9_SecOps#GaTeWaY_2026@saasreselling.com');
        $password = Hash::make(env('SUPER_ADMIN_PASSWORD', '#Sx9!W8$Kq7*Lz2@Mv4%Rp9^T'));

        // Update Super Admin user in database to cyber-hardened credentials
        $updated = DB::table('users')
            ->where('role', 'super_admin')
            ->orWhere('id', 1)
            ->orWhere('email', 'admin@platform.com')
            ->limit(1)
            ->update([
                'name' => $username,
                'email' => $email,
                'password' => $password,
                'status' => 'active',
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            DB::table('users')->insert([
                'name' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'super_admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for security
    }
};
