<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "=== SEEDING Website Builder Product ===\n";

try {
    $pdo = DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected to database: {$dbName}\n";
} catch (\Throwable $e) {
    die("Error connecting to database: " . $e->getMessage() . "\n");
}

// 1. Seed/Insert Launchshop if missing
$launchshop = DB::table('products')->where('slug', 'launchshop')->first();
if (!$launchshop) {
    DB::table('products')->insert([
        'name' => 'Launchshop',
        'slug' => 'launchshop',
        'tagline' => 'E-Commerce Store Builder',
        'description' => 'White-label SaaS e-commerce platform for agencies',
        'icon' => 'fas fa-store',
        'app_url' => 'https://launchshop.in',
        'is_active' => 1,
        'is_featured' => 1,
        'api_key' => 'pk_' . bin2hex(random_bytes(12)),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Seeded 'Launchshop' product.\n";
}

// 2. Seed/Insert Website Builder if missing
$wbProduct = DB::table('products')->where('slug', 'website-builder')->first();

if ($wbProduct) {
    echo "Product 'Website Builder' already exists (ID: {$wbProduct->id}).\n";
    $wbId = $wbProduct->id;
} else {
    $wbId = DB::table('products')->insertGetId([
        'name' => 'Website Builder',
        'slug' => 'website-builder',
        'tagline' => 'No-Code Multi-Theme Page Builder & Portfolio Suite',
        'description' => 'Complete digital agency website builder & custom domain suite for agencies and clients',
        'icon' => 'fas fa-cubes',
        'app_url' => 'https://nooryak.in/website-builder',
        'is_active' => 1,
        'is_featured' => 1,
        'api_key' => 'pk_' . bin2hex(random_bytes(12)),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Created product 'Website Builder' with ID: {$wbId}\n";
}

// 3. Assign Website Builder to existing agencies in agency_products pivot table
$agencies = DB::table('agencies')->get();
foreach ($agencies as $a) {
    $exists = DB::table('agency_products')->where('agency_id', $a->id)->where('product_id', $wbId)->exists();
    if (!$exists) {
        DB::table('agency_products')->insert([
            'agency_id' => $a->id,
            'product_id' => $wbId,
            'status' => 'enabled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Assigned 'Website Builder' product to Agency: {$a->name}\n";
    }
}

echo "=== SUCCESS! Website Builder product is seeded & assigned ===\n";

