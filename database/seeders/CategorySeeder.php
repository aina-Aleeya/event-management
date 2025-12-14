<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $defaults = [
            'Adult Male',
            'Adult Female',
            'Open'
        ];
    
        foreach ($defaults as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }
    }
}
