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
        return view('frontend.dashboard.address.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend.dashboard.address.create');
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
        return view('frontend.dashboard.address.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

        $address = UserAddress::findOrFail($id);
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

        toastr('Updated Successfully!', 'success', 'Success');

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
