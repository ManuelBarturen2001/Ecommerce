<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GenderDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gender;
use Str;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GenderDataTable $dataTable)
    {
        return $dataTable->render('admin.gender.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gender.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200', 'unique:genders,name'],
            'status' => ['required']
        ]);

        $gender = new Gender();

        $gender->name = $request->name;
        $gender->slug = Str::slug($request->name);
        $gender->status = $request->status;
        $gender->save();

        toastr('Creado Exitosamente!', 'success');

        return redirect()->route('admin.gender.index');
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
        $gender = Gender::findOrFail($id);
        return view('admin.gender.edit',compact('gender'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'max:200', 'unique:genders,name,'.$id],
            'status' => ['required']
        ]);

        $gender = Gender::findOrFail($id);

        $gender->name = $request->name;
        $gender->slug = Str::slug($request->name);
        $gender->status = $request->status;
        $gender->save();

        toastr('Modificado Exitosamente!', 'success');

        return redirect()->route('admin.gender.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gender = Gender::findOrFail($id);
        $gender->delete();

        return response(['status' => 'success', 'Eliminado Exitosamente!']);
    }

    public function changeStatus(Request $request)
    {
        $gender = Gender::findOrFail($request->id);
        $gender->status = $request->status == 'true' ? 1 : 0;
        $gender->save();

        return response(['message' => 'El estado ha sido actualizado!']);
    }
}
