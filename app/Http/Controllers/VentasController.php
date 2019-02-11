<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Cliente;
use App\Producto;
use App\Categoria;
use PDF;

class VentasController extends Controller
{

    public function lista_precios(Request $request)
    {
        $productos = Producto::where('cantidad','>','0')
                                ->orderBy('idCategoria','desc')
                                ->get();
        // Preparar las categorias de solo los productos disponibles
        foreach ($productos as $producto) {
            $categorias[$producto->categoria->id] = $producto->categoria->nombre_categoria;
        }
        return view('ventas.lista_precios',compact('productos','categorias'));
    }

    public function lista_precios_pdf(Request $request){
        $this->validate($request, [
            'categorias' => 'required'
        ]);

        $productos_disponibles = Producto::where('cantidad','>','0')
                                ->whereIn('idCategoria',$request->categorias)
                                ->orderBy('idCategoria','desc')
                                ->get();

        $pdf = PDF::loadView('ventas.pdf_lista_precios', compact('productos_disponibles'));
        return $pdf->stream();
    }
}