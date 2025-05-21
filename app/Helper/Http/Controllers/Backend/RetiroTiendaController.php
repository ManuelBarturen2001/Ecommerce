<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RetiroTienda;
use Illuminate\Http\Request;

class RetiroTiendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiendas = RetiroTienda::orderBy('nombre_tienda', 'asc')->get();
        return view('admin.retiro-en-tienda.index', compact('tiendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.retiro-en-tienda.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_tienda' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'content' => 'nullable'
        ]);

        RetiroTienda::create([
            'nombre_tienda' => $request->nombre_tienda,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
            'horario_apertura' => $request->horario_apertura,
            'horario_cierre' => $request->horario_cierre,
            'dias_disponibles' => $request->dias_disponibles,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'instrucciones_retiro' => $request->instrucciones_retiro,
            'documentos_requeridos' => $request->documentos_requeridos,
            'estado' => $request->has('estado') ? 1 : 0,
            'content' => $request->content
        ]);

        toastr('Tienda creada exitosamente!', 'success', 'success');
        return redirect()->route('admin.retiro-en-tienda.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tienda = RetiroTienda::findOrFail($id);
        return view('admin.retiro-en-tienda.show', compact('tienda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tienda = RetiroTienda::findOrFail($id);
        return view('admin.retiro-en-tienda.edit', compact('tienda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre_tienda' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'content' => 'nullable'
        ]);

        $tienda = RetiroTienda::findOrFail($id);
        $tienda->update([
            'nombre_tienda' => $request->nombre_tienda,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
            'horario_apertura' => $request->horario_apertura,
            'horario_cierre' => $request->horario_cierre,
            'dias_disponibles' => $request->dias_disponibles,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'instrucciones_retiro' => $request->instrucciones_retiro,
            'documentos_requeridos' => $request->documentos_requeridos,
            'estado' => $request->has('estado') ? 1 : 0,
            'content' => $request->content
        ]);

        toastr('Información actualizada exitosamente!', 'success', 'success');
        return redirect()->route('admin.retiro-en-tienda.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tienda = RetiroTienda::findOrFail($id);
        $tienda->delete();
        
        toastr('Tienda eliminada exitosamente!', 'success', 'success');
        return redirect()->route('admin.retiro-en-tienda.index');
    }
    
    /**
     * Cambiar el estado de la tienda
     */
    public function cambiarEstado(string $id)
    {
        $tienda = RetiroTienda::findOrFail($id);
        $tienda->estado = !$tienda->estado;
        $tienda->save();
        
        $status = $tienda->estado ? 'activada' : 'desactivada';
        toastr("Tienda {$status} exitosamente!", 'success', 'success');
        return redirect()->back();
    }
}