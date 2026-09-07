<?php

$host = '127.0.0.1';
$port = '3306';
$dbName = 'sass_admin';
$user = 'root';
$passwords = ['', 'root'];

$pdo = null;
foreach ($passwords as $pass) {
    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ]);
        break;
    } catch (\Throwable $e) {
        // try next password
    }
}

if (!$pdo) {
    // Try creating database if sass_admin does not exist yet
    foreach ($passwords as $pass) {
        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdoRaw = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdoRaw->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdoRaw->exec("USE `{$dbName}`;");
            $pdo = $pdoRaw;
            break;
        } catch (\Throwable $e) {}
    }
}

if (!$pdo) {
    die("Error: Could not connect to MySQL server.\n");
}

echo "=== SEEDING Website Builder Product ===\n";

// Check if products table exists
$stmt = $pdo->query("SHOW TABLES LIKE 'products'");
$hasProductsTable = $stmt->fetch();

if (!$hasProductsTable) {
    echo "Creating 'products' table in {$dbName}...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL,
        `tagline` varchar(255) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `icon` varchar(50) DEFAULT 'fas fa-cubes',
        `app_url` varchar(255) DEFAULT NULL,
        `api_key` varchar(255) DEFAULT NULL,
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `is_featured` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `products_slug_unique` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

// 1. Seed/Insert Launchshop if missing
$stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
$stmt->execute(['launchshop']);
$launchshop = $stmt->fetch();
if (!$launchshop) {
    $stmtIns = $pdo->prepare("INSERT INTO products (name, slug, tagline, description, icon, app_url, is_active, is_featured, api_key, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, NOW(), NOW())");
    $stmtIns->execute(['Launchshop', 'launchshop', 'E-Commerce Store Builder', 'White-label SaaS e-commerce platform for agencies', 'fas fa-store', 'https://launchshop.in', 'pk_' . bin2hex(random_bytes(12))]);
    echo "Seeded 'Launchshop' product.\n";
}

// 2. Seed/Insert Website Builder if missing
$stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
$stmt->execute(['website-builder']);
$wbProduct = $stmt->fetch();

if ($wbProduct) {
    echo "Product 'Website Builder' already exists (ID: {$wbProduct->id}).\n";
    $wbId = $wbProduct->id;
} else {
    $stmtIns = $pdo->prepare("INSERT INTO products (name, slug, tagline, description, icon, app_url, is_active, is_featured, api_key, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, NOW(), NOW())");
    $stmtIns->execute([
        'Website Builder',
        'website-builder',
        'No-Code Multi-Theme Page Builder & Portfolio Suite',
        'Complete digital agency website builder & custom domain suite for agencies and clients',
        'fas fa-cubes',
        'https://cockroachjantaparty.top/website-builder',
        'pk_' . bin2hex(random_bytes(12))
    ]);
    $wbId = $pdo->lastInsertId();
    echo "Created product 'Website Builder' with ID: {$wbId}\n";
}

// 3. Assign Website Builder to existing agencies in agency_products pivot table
$stmt = $pdo->query("SHOW TABLES LIKE 'agency_products'");
$hasAgencyProducts = $stmt->fetch();

if ($hasAgencyProducts && $wbId) {
    $agencies = $pdo->query("SELECT id, name FROM agencies")->fetchAll();
    foreach ($agencies as $a) {
        $chk = $pdo->prepare("SELECT agency_id FROM agency_products WHERE agency_id = ? AND product_id = ?");
        $chk->execute([$a->id, $wbId]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO agency_products (agency_id, product_id, status, created_at, updated_at) VALUES (?, ?, 'enabled', NOW(), NOW())");
            $ins->execute([$a->id, $wbId]);
            echo "Assigned 'Website Builder' product to Agency: {$a->name}\n";
        }
    }
}

echo "=== SUCCESS! Website Builder product is seeded & assigned ===\n";
