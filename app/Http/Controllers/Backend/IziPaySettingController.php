<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IziPaySettings;
use Illuminate\Http\Request;

class IziPaySettingController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'status' => ['required', 'integer'],
            'mode' => ['required', 'string', 'in:sandbox,live'],
            'country_name' => ['required', 'string', 'max:200'],
            'currency_name' => ['required', 'string', 'max:20'],
            'currency_rate' => ['required', 'numeric'],
            'shop_id' => ['required', 'string'],
            'public_key' => ['required', 'string'],
            'private_key' => ['required', 'string'],
            'hmac_sha256_key' => ['required', 'string'],
            'javascript_client_key' => ['required', 'string']
        ]);

        IziPaySettings::updateOrCreate(
            ['id' => 1],
            [
                'status' => $request->status,
                'mode' => $request->mode,
                'country_name' => $request->country_name,
                'currency_name' => $request->currency_name,
                'currency_rate' => $request->currency_rate,
                'shop_id' => $request->shop_id,
                'public_key' => $request->public_key,
                'private_key' => $request->private_key,
                'hmac_sha256_key' => $request->hmac_sha256_key,
                'javascript_client_key' => $request->javascript_client_key
            ]
        );

        toastr('Configuración de IziPay actualizada exitosamente!', 'success');
        return redirect()->back();
    }
}