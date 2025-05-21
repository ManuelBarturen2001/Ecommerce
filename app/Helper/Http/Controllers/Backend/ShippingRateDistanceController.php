<?php

namespace App\Http\Controllers\Backend;
use App\DataTables\ShippingRateDistanceDataTable;
use App\Models\ShippingRateDistance;
use App\Models\ShippingRule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingRateDistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShippingRateDistanceDataTable $dataTable)
{
    return $dataTable->render('admin.shipping-rate-distance.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shippingRules = ShippingRule::all(); 
        return view('admin.shipping-rate-distance.create', compact('shippingRules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los campos
        $request->validate([
            'shipping_rule_id' => ['required', 'exists:shipping_rules,id'], // Validar que el ID de la regla de envío exista
            'min_km' => ['required', 'numeric', 'min:0'], // Validar min_km
            'max_km' => ['required', 'numeric', 'min:0'], // Validar max_km
            'price' => ['required', 'numeric', 'min:0'], // Validar price (suponiendo que es el precio)
            'status' => ['required', 'in:0,1'], // Validar status
        ]);

        // Crear un nuevo registro
        $rate = new ShippingRateDistance();
        $rate->shipping_rule_id = $request->shipping_rule_id; // Guardar el ID de la regla de envío
        $rate->min_km = $request->min_km; // Guardar el valor de min_km
        $rate->max_km = $request->max_km; // Guardar el valor de max_km
        $rate->price = $request->price; // Guardar el precio (prince)
        $rate->status = $request->status; // Guardar el status
        $rate->save(); // Guardar el registro

        toastr()->success('Tarifa creada exitosamente'); // Mensaje de éxito

        return redirect()->route('admin.shipping-rate-distance.index'); // Redirigir al listado de tarifas
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rate = ShippingRateDistance::findOrFail($id);
        return view('admin.shipping-rate-distance.edit', compact('rate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los campos
        $request->validate([
            'shipping_rule_id' => ['required', 'exists:shipping_rules,id'], // Validar que el ID de la regla de envío exista
            'min_km' => ['required', 'numeric', 'min:0'], // Validar min_km
            'max_km' => ['required', 'numeric', 'min:0'], // Validar max_km
            'price' => ['required', 'numeric', 'min:0'], // Validar price (suponiendo que es el precio)
            'status' => ['required', 'in:0,1'], // Validar status
        ]);

        // Buscar el registro a actualizar
        $rate = ShippingRateDistance::findOrFail($id);
        $rate->shipping_rule_id = $request->shipping_rule_id; // Actualizar el ID de la regla de envío
        $rate->min_km = $request->min_km; // Actualizar el valor de min_km
        $rate->max_km = $request->max_km; // Actualizar el valor de max_km
        $rate->price = $request->price; // Actualizar el precio (price)
        $rate->status = $request->status; // Actualizar el status
        $rate->save(); // Guardar los cambios

        toastr()->success('Tarifa actualizada exitosamente'); // Mensaje de éxito

        return redirect()->route('admin.shipping-rate-distance.index'); // Redirigir al listado de tarifas
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rate = ShippingRateDistance::findOrFail($id);
        $rate->delete();

        return response(['status' => 'success', 'message' => 'Eliminado exitosamente']);
    }

    public function changeStatus(Request $request)
    {
        $rate = ShippingRateDistance::findOrFail($request->id);
        $rate->status = $request->status == 'true' ? 1 : 0;
        $rate->save();

        return response(['message' => 'Estado actualizado correctamente']);
    }
}
