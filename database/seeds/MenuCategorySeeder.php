<?php

use App\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuCategory::create(['title' => 'Makanan']);
        MenuCategory::create(['title' => 'Minuman']);
        MenuCategory::create(['title' => 'Snack']);
    }
}
