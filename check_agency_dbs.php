<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = DB::table('agency_products')
    ->join('agencies', 'agencies.id', '=', 'agency_products.agency_id')
    ->join('products', 'products.id', '=', 'agency_products.product_id')
    ->select('agencies.name', 'agencies.custom_domain', 'products.slug', 'agency_products.db_name', 'agency_products.db_status')
    ->get();

if ($rows->isEmpty()) {
    echo "No agency_products rows found.\n";
} else {
    foreach ($rows as $r) {
        echo "Agency: " . $r->name . " | Domain: " . $r->custom_domain . " | Product: " . $r->slug . " | db_name: " . ($r->db_name ?? 'NULL') . " | status: " . $r->db_status . "\n";
    }
}
