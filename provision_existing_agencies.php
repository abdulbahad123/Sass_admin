<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PROVISIONING DBs FOR EXISTING WHITE LABEL AGENCIES ===\n\n";

$product = DB::table('products')->where('slug', 'launchshop')->first();
if (!$product) {
    echo "ERROR: Launchshop product not found! Run seed_launchshop_product.php first.\n";
    exit(1);
}
echo "Product: {$product->name} (ID: {$product->id})\n\n";

$agencies = DB::table('agencies')->where('type', 'white_label')->get();
if ($agencies->isEmpty()) {
    echo "No white_label agencies found.\n";
    exit(0);
}

$dbService = new \App\Services\DatabaseProvisioningService();
$hasDbCol  = \Illuminate\Support\Facades\Schema::hasColumn('agency_products', 'db_name');

foreach ($agencies as $agencyRow) {
    // Load as Eloquent model so DatabaseProvisioningService works
    $agency  = \App\Models\Agency::find($agencyRow->id);
    $prodObj = \App\Models\Product::find($product->id);

    echo "Agency: {$agency->name} (slug: {$agency->slug}, domain: {$agency->custom_domain})\n";

    // Check if already linked
    $existing = DB::table('agency_products')
        ->where('agency_id', $agency->id)
        ->where('product_id', $product->id)
        ->first();

    try {
        $dbName   = $dbService->provisionDatabaseForAgencyProduct($agency, $prodObj);
        $dbStatus = 'active';
        echo "  -> Provisioned DB: {$dbName}\n";
    } catch (\Throwable $e) {
        $dbName   = null;
        $dbStatus = 'failed';
        echo "  -> Provisioning FAILED: " . $e->getMessage() . "\n";
    }

    // Upsert agency_products pivot
    if ($existing) {
        $updateData = ['status' => 'enabled'];
        if ($hasDbCol) {
            $updateData['db_name']   = $dbName;
            $updateData['db_status'] = $dbStatus;
        }
        DB::table('agency_products')
            ->where('agency_id', $agency->id)
            ->where('product_id', $product->id)
            ->update($updateData);
        echo "  -> Updated existing agency_products row.\n";
    } else {
        $insertData = ['agency_id' => $agency->id, 'product_id' => $product->id, 'status' => 'enabled'];
        if ($hasDbCol) {
            $insertData['db_name']   = $dbName;
            $insertData['db_status'] = $dbStatus;
        }
        DB::table('agency_products')->insert($insertData);
        echo "  -> Inserted new agency_products row.\n";
    }

    echo "  -> db_name saved: " . ($dbName ?? 'NULL') . "\n\n";
}

echo "=== FINAL STATE ===\n";
$rows = DB::table('agency_products')
    ->join('agencies',  'agencies.id',  '=', 'agency_products.agency_id')
    ->join('products',  'products.id',  '=', 'agency_products.product_id')
    ->select('agencies.name', 'agencies.slug', 'agencies.custom_domain', 'products.slug as product_slug', 'agency_products.db_name', 'agency_products.db_status')
    ->get();

foreach ($rows as $r) {
    echo "Agency: {$r->name} | Domain: {$r->custom_domain} | Product: {$r->product_slug} | DB: " . ($r->db_name ?? 'NULL') . " | Status: {$r->db_status}\n";
}
