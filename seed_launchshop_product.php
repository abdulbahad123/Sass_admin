<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== SEEDING Launchshop Product ===\n";

// Check if already exists
$existing = DB::table('products')->where('slug', 'launchshop')->first();
if ($existing) {
    echo "Product 'launchshop' already exists (ID: {$existing->id}). Skipping.\n";
} else {
    $id = DB::table('products')->insertGetId([
        'name'        => 'Launchshop',
        'slug'        => 'launchshop',
        'tagline'     => 'E-Commerce Store Builder',
        'description' => 'White-label SaaS e-commerce platform for agencies',
        'icon'        => 'fas fa-store',
        'app_url'     => 'https://launchshop.in',
        'is_active'   => 1,
        'is_featured' => 1,
        'api_key'     => 'pk_' . bin2hex(random_bytes(12)),
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);
    echo "Created product 'Launchshop' with ID: {$id}\n";
}

// Show current agencies and prompt to re-provision
echo "\n=== AGENCIES THAT NEED DB PROVISIONING ===\n";
$agencies = DB::table('agencies')->where('type', 'white_label')->get();
foreach ($agencies as $a) {
    $hasProd = DB::table('agency_products')->where('agency_id', $a->id)->count();
    echo "Agency: {$a->name} | Slug: {$a->slug} | Domain: {$a->custom_domain} | Products assigned: {$hasProd}\n";
}

if ($agencies->isEmpty()) {
    echo "No white_label agencies found yet.\n";
}
echo "\nDone! Now go to Sass Admin -> Agencies and use 'Re-Provision DB' button,\n";
echo "OR create a new agency and select the Launchshop product.\n";
