<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::create([
            'type_name' => 'type 1',
            'details' => 'type 1 details',
        ]);

        Type::create([
            'type_name' => 'type 2',
            'details' => 'type 2 details',
        ]);

        Type::create([
            'type_name' => 'type 3',
            'details' => 'type 3 details',
        ]);
    }
}
