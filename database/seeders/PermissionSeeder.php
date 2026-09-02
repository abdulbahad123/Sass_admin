<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'category' => 'dashboard', 'description' => 'Access to dashboard overview'],
            
            // Agencies
            ['name' => 'View Agencies', 'slug' => 'view-agencies', 'category' => 'agencies', 'description' => 'View agency listings'],
            ['name' => 'Create Agencies', 'slug' => 'create-agencies', 'category' => 'agencies', 'description' => 'Create new agencies'],
            ['name' => 'Edit Agencies', 'slug' => 'edit-agencies', 'category' => 'agencies', 'description' => 'Edit agency details'],
            ['name' => 'Delete Agencies', 'slug' => 'delete-agencies', 'category' => 'agencies', 'description' => 'Delete agencies'],
            
            // Products
            ['name' => 'View Products', 'slug' => 'view-products', 'category' => 'products', 'description' => 'View product listings'],
            ['name' => 'Manage Product Access', 'slug' => 'manage-product-access', 'category' => 'products', 'description' => 'Toggle product access for agencies'],
            
            // Plans
            ['name' => 'View Plans', 'slug' => 'view-plans', 'category' => 'plans', 'description' => 'View pricing plans'],
            ['name' => 'Create Plans', 'slug' => 'create-plans', 'category' => 'plans', 'description' => 'Create pricing plans'],
            ['name' => 'Edit Plans', 'slug' => 'edit-plans', 'category' => 'plans', 'description' => 'Edit pricing plans'],
            ['name' => 'Delete Plans', 'slug' => 'delete-plans', 'category' => 'plans', 'description' => 'Delete pricing plans'],
            
            // Billing
            ['name' => 'View Billing', 'slug' => 'view-billing', 'category' => 'billing', 'description' => 'View billing and invoices'],
            ['name' => 'Manage Billing', 'slug' => 'manage-billing', 'category' => 'billing', 'description' => 'Manage billing settings'],
            
            // Subscriptions
            ['name' => 'View Subscriptions', 'slug' => 'view-subscriptions', 'category' => 'subscriptions', 'description' => 'View subscriptions'],
            ['name' => 'Manage Subscriptions', 'slug' => 'manage-subscriptions', 'category' => 'subscriptions', 'description' => 'Update subscription status'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'view-reports', 'category' => 'reports', 'description' => 'Access reports and analytics'],
            
            // Branding
            ['name' => 'View Branding', 'slug' => 'view-branding', 'category' => 'branding', 'description' => 'View branding settings'],
            ['name' => 'Manage Branding', 'slug' => 'manage-branding', 'category' => 'branding', 'description' => 'Update branding settings'],
            
            // Team & Staff
            ['name' => 'View Team & Staff', 'slug' => 'view-team', 'category' => 'team', 'description' => 'View team members and staff roster'],
            ['name' => 'Manage Team & Staff', 'slug' => 'manage-team', 'category' => 'team', 'description' => 'Add, edit, or remove team members'],
            ['name' => 'Manage Roles & Permissions', 'slug' => 'manage-roles', 'category' => 'team', 'description' => 'Create and edit roles & menu permissions'],

            // Support Tickets
            ['name' => 'View Support Tickets', 'slug' => 'view-tickets', 'category' => 'tickets', 'description' => 'View support tickets list and conversations'],
            ['name' => 'Reply Support Tickets', 'slug' => 'reply-tickets', 'category' => 'tickets', 'description' => 'Reply to agency tickets'],
            ['name' => 'Assign Staff to Tickets', 'slug' => 'assign-tickets', 'category' => 'tickets', 'description' => 'Assign support agents to tickets'],
            ['name' => 'Manage Ticket Status', 'slug' => 'manage-tickets', 'category' => 'tickets', 'description' => 'Change ticket status and priority'],

            // Audit Logs
            ['name' => 'View Audit Logs', 'slug' => 'view-audit-logs', 'category' => 'audit_logs', 'description' => 'Access platform activity and audit logs'],
            
            // Settings
            ['name' => 'View Settings', 'slug' => 'view-settings', 'category' => 'settings', 'description' => 'View platform settings'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'category' => 'settings', 'description' => 'Update platform settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
