<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CulqiSettings;
use Illuminate\Http\Request;

class CulqiSettingController extends Controller
{
    public function index()
    {
        $culqi = CulqiSettings::first();
        return view('admin.payment-settings.sections.culqi-setting', compact('culqi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'status' => ['required', 'integer'],
            'mode' => ['required', 'string'],
            'country_name' => ['required', 'string'],
            'currency_name' => ['required', 'string'],
            'currency_rate' => ['required', 'numeric'],
            'public_key' => ['required', 'string'],
            'secret_key' => ['required', 'string']
        ]);

        CulqiSettings::updateOrCreate(
            ['id' => 1],
            [
                'status' => $request->status,
                'mode' => $request->mode,
                'country_name' => $request->country_name,
                'currency_name' => $request->currency_name,
                'currency_rate' => $request->currency_rate,
                'public_key' => $request->public_key,
                'secret_key' => $request->secret_key
            ]
        );

        toastr('Configuración de Culqi actualizada correctamente', 'success');
        return redirect()->back();
    }
}