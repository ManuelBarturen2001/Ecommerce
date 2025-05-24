<?php

namespace Database\Seeders;

use App\Models\CulqiSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CulqiSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CulqiSettings::create([
            'status' => 1,
            'mode' => 'sandbox',
            'country_name' => 'Peru',
            'currency_name' => 'PEN',
            'currency_rate' => '1',
            'public_key' => 'pk_test_xxxxxxxxxxxxxxxx', // Reemplazar con tu clave pública de prueba
            'secret_key' => 'sk_test_xxxxxxxxxxxxxxxx'  // Reemplazar con tu clave secreta de prueba
        ]);
    }
}