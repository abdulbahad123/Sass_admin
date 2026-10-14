<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $slug = 'whatsappbulk';
        $product = DB::table('products')->where('slug', $slug)->first();

        if (!$product) {
            $productId = DB::table('products')->insertGetId([
                'name' => 'WhatsApp Bulk',
                'slug' => $slug,
                'tagline' => 'AI WhatsApp Bulk Sender, Chatbot & Automation SaaS',
                'description' => 'Multi-device WhatsApp automation, bulk messaging campaigns, AI chatbot autoresponder & CRM integration suite',
                'icon' => 'message-square',
                'app_url' => 'https://whatsappbulk.saasreselling.com',
                'is_active' => 1,
                'is_featured' => 1,
                'api_key' => 'pk_' . bin2hex(random_bytes(12)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $productId = $product->id;
            DB::table('products')->where('id', $productId)->update([
                'app_url' => 'https://whatsappbulk.saasreselling.com',
            ]);
        }

        // Attach to existing agencies in agency_products pivot
        $agencies = DB::table('agencies')->get();
        foreach ($agencies as $agency) {
            $exists = DB::table('agency_products')
                ->where('agency_id', $agency->id)
                ->where('product_id', $productId)
                ->exists();

            if (!$exists) {
                DB::table('agency_products')->insert([
                    'agency_id' => $agency->id,
                    'product_id' => $productId,
                    'status' => 'enabled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $product = DB::table('products')->where('slug', 'whatsappbulk')->first();
        if ($product) {
            DB::table('agency_products')->where('product_id', $product->id)->delete();
            DB::table('products')->where('id', $product->id)->delete();
        }
    }
};