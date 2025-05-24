<?php

namespace Database\Seeders;

use App\Models\IziPaySettings;
use Illuminate\Database\Seeder;

class IziPaySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IziPaySettings::create([
            'status' => 1,
            'mode' => 'sandbox',
            'country_name' => 'Peru',
            'currency_name' => 'PEN',
            'currency_rate' => 1.00,
            'shop_id' => '83761009',
            'public_key' => '83761009:testpublickey_4A6NIV6shMoLfvTxAXeGQUVsZ2TxCdYvZnlxesZVl68HT',
            'private_key' => 'testpassword_ACYvXrf76EcW8AmnXvx2tOgGqEelSdR4apsmczpo9yfJc',
            'hmac_sha256_key' => 'g89eIygzgvg0c8H6R35asupRaVDjRnyNrPvPxrH1Lf83I',
            'javascript_client_key' => 'https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js'
        ]);
    }
}