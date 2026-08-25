<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== AGENCIES ===\n";
$agencies = DB::table('agencies')->get();
if ($agencies->isEmpty()) {
    echo "No agencies found.\n";
} else {
    foreach ($agencies as $a) {
        echo "ID: {$a->id} | Name: {$a->name} | Slug: {$a->slug} | Type: {$a->type} | Domain: {$a->custom_domain}\n";
    }
}

echo "\n=== PRODUCTS ===\n";
$products = DB::table('products')->get();
if ($products->isEmpty()) {
    echo "No products found.\n";
} else {
    foreach ($products as $p) {
        echo "ID: {$p->id} | Name: {$p->name} | Slug: {$p->slug} | Active: {$p->is_active}\n";
    }
}

echo "\n=== AGENCY_PRODUCTS (pivot) ===\n";
$ap = DB::table('agency_products')->get();
if ($ap->isEmpty()) {
    echo "No agency_products rows found. <-- THIS IS THE PROBLEM\n";
} else {
    foreach ($ap as $r) {
        echo "agency_id: {$r->agency_id} | product_id: {$r->product_id} | db_name: " . ($r->db_name ?? 'NULL') . " | db_status: " . ($r->db_status ?? 'NULL') . "\n";
    }
}

echo "\n=== EXISTING DATABASES (matching launchshop pattern) ===\n";
$dbs = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%launchshop%' OR SCHEMA_NAME LIKE '%ps_%'");
foreach ($dbs as $db) {
    echo "DB: {$db->SCHEMA_NAME}\n";
}
