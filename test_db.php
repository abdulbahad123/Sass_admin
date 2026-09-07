<?php
$hosts = ['localhost', '127.0.0.1'];
$users = ['root'];
$passes = ['', 'root'];

foreach ($hosts as $host) {
    foreach ($users as $u) {
        foreach ($passes as $p) {
            try {
                $pdo = new PDO("mysql:host={$host}", $u, $p, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                echo "Success with host={$host}, user={$u}, pass='{$p}'!\n";
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `sass_admin` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $pdo->exec("USE `sass_admin`;");
                
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

                // Seed Launchshop
                $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
                $stmt->execute(['launchshop']);
                if (!$stmt->fetch()) {
                    $ins = $pdo->prepare("INSERT INTO products (name, slug, tagline, description, icon, app_url, is_active, is_featured, api_key, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, NOW(), NOW())");
                    $ins->execute(['Launchshop', 'launchshop', 'E-Commerce Store Builder', 'White-label SaaS e-commerce platform for agencies', 'fas fa-store', 'https://launchshop.in', 'pk_' . bin2hex(random_bytes(12))]);
                    echo "Seeded 'Launchshop' product.\n";
                }

                // Seed Website Builder
                $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
                $stmt->execute(['website-builder']);
                $wb = $stmt->fetch();
                if ($wb) {
                    $wbId = $wb->id;
                    echo "Product 'Website Builder' already exists (ID: {$wbId}).\n";
                } else {
                    $ins = $pdo->prepare("INSERT INTO products (name, slug, tagline, description, icon, app_url, is_active, is_featured, api_key, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, NOW(), NOW())");
                    $ins->execute([
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

                // Check agency_products table and assign
                $hasAgencies = $pdo->query("SHOW TABLES LIKE 'agencies'")->fetch();
                $hasPivot    = $pdo->query("SHOW TABLES LIKE 'agency_products'")->fetch();
                if ($hasAgencies && $hasPivot && $wbId) {
                    $agencies = $pdo->query("SELECT id, name FROM agencies")->fetchAll(PDO::FETCH_OBJ);
                    foreach ($agencies as $a) {
                        $chk = $pdo->prepare("SELECT agency_id FROM agency_products WHERE agency_id = ? AND product_id = ?");
                        $chk->execute([$a->id, $wbId]);
                        if (!$chk->fetch()) {
                            $insP = $pdo->prepare("INSERT INTO agency_products (agency_id, product_id, status, created_at, updated_at) VALUES (?, ?, 'enabled', NOW(), NOW())");
                            $insP->execute([$a->id, $wbId]);
                            echo "Assigned Website Builder to Agency: {$a->name}\n";
                        }
                    }
                }
                break 3;
            } catch (\Throwable $e) {
                echo "Failed host={$host}, u={$u}, p='{$p}': " . $e->getMessage() . "\n";
            }
        }
    }
}
