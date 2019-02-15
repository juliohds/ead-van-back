<?php

use Illuminate\Database\Seeder;

class NetworkCustomPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('INSERT INTO network_custom_page(title, body, published, image, network_id, slug, created_at, updated_at) SELECT custom_page.title, custom_page.body, custom_page.published, custom_page.image, custom_page.network_id, page_slug.slug, custom_page.created_at, custom_page.updated_at FROM custom_page INNER JOIN page_slug ON page_slug.custom_page_id = custom_page.id');
        
    }
}
