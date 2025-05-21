<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RetiroTienda;
use Illuminate\Http\Request;

class RetiroTiendaFrontendController extends Controller
{
    public function index()
    {
        $tiendas = RetiroTienda::where('estado', 1)->orderBy('nombre_tienda', 'asc')->get();
        return view('frontend.pages.retiro-en-tienda', compact('tiendas'));
    }
    
    public function detalles($id)
    {
        $tienda = RetiroTienda::where('estado', 1)->findOrFail($id);
        return view('frontend.pages.retiro-en-tienda-detalles', compact('tienda'));
    }
}