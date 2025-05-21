<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use App\Models\ShippingRateDistance;
use App\Models\UserAddress;
use App\Models\GeneralSetting;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::with(['departamento', 'provincia', 'distrito'])
            ->where('user_id', Auth::user()->id)
            ->get();
        $shippingMethods = ShippingRule::where('status', 1)->get();
        $settings = GeneralSetting::first();
        $departamentos = Departamento::orderBy('nombre')->get();
        
        return view('frontend.pages.checkout', compact('addresses', 'shippingMethods', 'settings', 'departamentos'));
    }

    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'dep_id' => ['required', 'integer'],
            'prov_id' => ['required', 'integer'],
            'dist_id' => ['required', 'integer'],
            'zip' => ['required', 'max:20'],
            'address' => ['required', 'max:200'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
        ]);

        // Obtener nombres para almacenar en la dirección
        $departamento = Departamento::findOrFail($request->dep_id);
        $provincia = Provincia::findOrFail($request->prov_id);
        $distrito = Distrito::findOrFail($request->dist_id);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->dep_id = $request->dep_id;
        $address->prov_id = $request->prov_id;
        $address->dist_id = $request->dist_id;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();

        toastr('Dirección creada exitosamente!', 'success', 'Éxito');

        return redirect()->back();
    }

    public function getProvincias(Request $request)
    {
        try {
            $request->validate([
                'dep_id' => ['required', 'integer']
            ]);

            $provincias = Provincia::where('dep_id', $request->dep_id)
                        ->orderBy('nombre')
                        ->get();

            return response()->json($provincias);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDistritos(Request $request)
    {
        $request->validate([
            'prov_id' => ['required', 'integer']
        ]);

        $distritos = Distrito::where('prov_id', $request->prov_id)
                      ->orderBy('nombre')
                      ->get();

        return response()->json($distritos);
    }

    public function checkOutFormSubmit(Request $request)
    {
        $request->validate([
            'shipping_method_id' => ['required', 'integer'],
            'shipping_address_id' => ['nullable', 'integer'], // Cambiado a nullable
            'delivery_fee' => ['nullable', 'numeric'],
            'is_pickup' => ['nullable', 'boolean'],
            'store_pickup_id' => ['nullable', 'integer'], // Añadido para manejar el ID de la tienda
        ]);

        $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
        $isPickup = $request->has('is_pickup') && $request->is_pickup == 1;
        
        // Si es recojo en tienda
        if ($isPickup) {
            Session::put('shipping_method', [
                    'id' => $shippingMethod->id,
                    'name' => $shippingMethod->name,
                    'type' => $shippingMethod->type,
                    'cost' => $shippingMethod->cost,
                    'is_pickup' => true
            ]);
            
            // Obtener la información de la tienda seleccionada
            if ($request->has('store_pickup_id') && $request->store_pickup_id) {
                $storePickup = \App\Models\RetiroTienda::find($request->store_pickup_id);
                
                if ($storePickup) {
                    $storeAddress = [
                        'name' => $storePickup->nombre_tienda,
                        'address' => $storePickup->direccion . ', ' . $storePickup->ciudad,
                        'phone' => $storePickup->telefono,
                        'email' => auth()->user()->email,
                        'is_store_pickup' => true,
                        'store_pickup_id' => $storePickup->id // Guardar el ID de la tienda seleccionada
                    ];
                    
                    Session::put('address', $storeAddress);
                } else {
                    // Fallback a la tienda principal si no se encuentra la tienda seleccionada
                    $storeSettings = GeneralSetting::first();
                    $storeAddress = [
                        'name' => $storeSettings->site_name,
                        'address' => $storeSettings->contact_address ?? 'Dirección de la tienda',
                        'phone' => $storeSettings->contact_phone,
                        'email' => $storeSettings->contact_email,
                        'is_store_pickup' => true
                    ];
                    
                    Session::put('address', $storeAddress);
                }
            } else {
                // Fallback a la tienda principal si no se proporciona un ID de tienda
                $storeSettings = GeneralSetting::first();
                $storeAddress = [
                    'name' => $storeSettings->site_name,
                    'address' => $storeSettings->contact_address ?? 'Dirección de la tienda',
                    'phone' => $storeSettings->contact_phone,
                    'email' => $storeSettings->contact_email,
                    'is_store_pickup' => true
                ];
                
                Session::put('address', $storeAddress);
            }
        }
        // Si es un método de entrega por distancia (Express Delivery)
        else if($shippingMethod->type === 'flat_cost' && $request->has('delivery_fee') && $request->delivery_fee > 0) {
            Session::put('shipping_method', [
                    'id' => $shippingMethod->id,
                    'name' => $shippingMethod->name,
                    'type' => $shippingMethod->type,
                    'cost' => $request->delivery_fee,
                    'is_pickup' => false
            ]);
            
            $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
            if($address){
                $address['is_store_pickup'] = false;
                Session::put('address', $address);
            }
        } else {
            // Otro método de entrega (como envío estándar)
            Session::put('shipping_method', [
                    'id' => $shippingMethod->id,
                    'name' => $shippingMethod->name,
                    'type' => $shippingMethod->type,
                    'cost' => $shippingMethod->cost,
                    'is_pickup' => false
            ]);
            
            $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
            if($address){
                $address['is_store_pickup'] = false;
                Session::put('address', $address);
            }
        }

        return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
    }
    
    // Método para calcular la tarifa por distancia
    public function calculateDeliveryFee(Request $request)
    {
        $request->validate([
            'shipping_method_id' => ['required', 'integer'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);
        
        $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
        
        // Solo calcular para Express Delivery
        if($shippingMethod->type !== 'flat_cost') {
            return response()->json(['fee' => $shippingMethod->cost]);
        }
        
        // Obtener coordenadas de la tienda
        $settings = GeneralSetting::first();
        $storeLatitude = $settings->latitude;
        $storeLongitude = $settings->longitude;
        
        // Calcular distancia en kilómetros
        $distance = $this->calculateDistance(
            $storeLatitude, 
            $storeLongitude, 
            $request->latitude, 
            $request->longitude
        );
        
        // Buscar tarifa según la distancia
        $fee = $this->getDeliveryFeeByDistance($shippingMethod->id, $distance);
        
        return response()->json([
            'fee' => $fee,
            'distance' => round($distance, 2)
        ]);
    }
    
    // Método para calcular la distancia entre dos puntos
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radio de la Tierra en kilómetros
        $earthRadius = 6371;
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        
        return $distance;
    }
    
    // Método para obtener la tarifa según la distancia
    private function getDeliveryFeeByDistance($shippingRuleId, $distance)
    {
        $rate = ShippingRateDistance::where('shipping_rule_id', $shippingRuleId)
            ->where('min_km', '<=', $distance)
            ->where('max_km', '>=', $distance)
            ->where('status', 1)
            ->first();
            
        if($rate) {
            return $rate->price;
        }
        
        // Si no hay rango que coincida, usar la tarifa del rango más alto
        $highestRate = ShippingRateDistance::where('shipping_rule_id', $shippingRuleId)
            ->where('status', 1)
            ->orderBy('max_km', 'desc')
            ->first();
            
        return $highestRate ? $highestRate->price : 0;
    }

    public function getAddress(Request $request)
    {
        $request->validate([
            'address_id' => ['required', 'integer'],
        ]);

        $address = UserAddress::where('id', $request->address_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$address) {
            return response()->json(['status' => 'error', 'message' => 'Dirección no encontrada'], 404);
        }

        return response()->json(['status' => 'success', 'address' => $address]);
    }

    // Método para actualizar una dirección
    public function updateAddress(Request $request)
    {
        $request->validate([
            'address_id' => ['required', 'integer'],
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'dep_id' => ['required', 'integer'],
            'prov_id' => ['required', 'integer'],
            'dist_id' => ['required', 'integer'],
            'zip' => ['required', 'max:20'],
            'address' => ['required', 'max:200'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
        ]);

        $address = UserAddress::where('id', $request->address_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$address) {
            toastr('Dirección no encontrada', 'error', 'Error');
            return redirect()->back();
        }

        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->dep_id = $request->dep_id;
        $address->prov_id = $request->prov_id;
        $address->dist_id = $request->dist_id;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();

        toastr('Dirección actualizada exitosamente!', 'success', 'Éxito');
        return redirect()->back();
    }

    // Método para eliminar una dirección
    public function deleteAddress(Request $request)
    {
        $request->validate([
            'address_id' => ['required', 'integer'],
        ]);

        $address = UserAddress::where('id', $request->address_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$address) {
            return response()->json(['status' => 'error', 'message' => 'Dirección no encontrada'], 404);
        }

        $address->delete();
        
        return response()->json(['status' => 'success', 'message' => 'Dirección eliminada exitosamente']);
    }

}