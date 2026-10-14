<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "=== SEEDING WhatsApp Bulk Product ===\n";

try {
    $pdo = DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected to database: {$dbName}\n";
} catch (\Throwable $e) {
    die("Error connecting to database: " . $e->getMessage() . "\n");
}

$slug = 'whatsappbulk';
$waProduct = DB::table('products')->where('slug', $slug)->first();

if ($waProduct) {
    echo "Product 'WhatsApp Bulk' already exists (ID: {$waProduct->id}).\n";
    $waId = $waProduct->id;
} else {
    $waId = DB::table('products')->insertGetId([
        'name' => 'WhatsApp Bulk',
        'slug' => $slug,
        'tagline' => 'AI WhatsApp Bulk Sender, Chatbot & Automation SaaS',
        'description' => 'Multi-device WhatsApp automation, bulk messaging campaigns, AI chatbot autoresponder & CRM integration suite',
        'icon' => 'fab fa-whatsapp',
        'app_url' => 'https://waziper.nooryak.in',
        'is_active' => 1,
        'is_featured' => 1,
        'api_key' => 'pk_' . bin2hex(random_bytes(12)),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Created product 'WhatsApp Bulk' with ID: {$waId}\n";
}

$agencies = DB::table('agencies')->get();
foreach ($agencies as $a) {
    $exists = DB::table('agency_products')->where('agency_id', $a->id)->where('product_id', $waId)->exists();
    if (!$exists) {
        DB::table('agency_products')->insert([
            'agency_id' => $a->id,
            'product_id' => $waId,
            'status' => 'enabled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Assigned 'WhatsApp Bulk' product to Agency: {$a->name}\n";
    }
}

echo "=== SUCCESS! WhatsApp Bulk product is seeded & assigned ===\n";