<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('frontend.dashboard.address.index', compact('addresses', 'departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('frontend.dashboard.address.create', compact('departamentos'));
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

        return redirect()->route('user.address.index');
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'email' => ['required', 'max:200', 'email'],
            'dep_id'=>['required','max:200'],
            'prov_id'=>['required','max:200'],
            'dist_id'=>['required','max:200'],
            'phone' => ['required', 'max:200'],
            'zip' => ['required', 'max:200'],
            'address' => ['required'],
        ]);

        $departamento = Departamento::findOrFail($request->dep_id);
        $provincia = Provincia::findOrFail($request->prov_id);
        $distrito = Distrito::findOrFail($request->dist_id);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->email = $request->email;
        $address->dep_id = $request->dep_id;
        $address->prov_id = $request->prov_id;
        $address->dist_id = $request->dist_id;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Created Successfully!', 'success', 'Success');

        return redirect()->route('user.address.index');

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
        $address = UserAddress::findOrFail($id);
        $departamentos = Departamento::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $distritos = Distrito::orderBy('nombre')->get();
        return view('frontend.dashboard.address.edit', compact('address', 'departamentos','provincias','distritos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
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

        $address = UserAddress::where('id', $request->id)
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
        return redirect()->route('user.address.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = UserAddress::findOrFail($id);
        $address->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
}
