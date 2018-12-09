<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Cliente;
use App\Producto;
use App\Orden;
use App\Factura;
use Codedge\Fpdf\Facades\Fpdf;

class DashboardController extends Controller
{
    public function dashboard(){

        //Notas de entrega totales
        $ordenes_totales = Orden::all()->count();

        // Cliente con mas ordenes
        // El siguiente query retorna un objeto con 2 valores (idCliente, count)
        $cliente_mas_ordenes = Orden::select('idCliente', DB::raw('count(idCliente) as count'))
                        ->groupBy('idCliente')
                        ->orderBy('count', 'desc')->first();
        //Checkear si se tiene el cliente (evitar errores en el dashboard por DB vacia)
        if(isset($cliente_mas_ordenes)){
            $cliente = Cliente::findorFail($cliente_mas_ordenes->idCliente);
        }

        // Monto Total por cobrar
        $facturas = Factura::whereIn('idFacturaEstado',[1,3])->get();

        foreach ($facturas as $factura) {
            if($factura->idFacturaEstado == 3){
                $monto_deducible = 0;
                foreach ($factura->pagos as $pago) {
                    $monto_deducible += $pago->pivot->monto_pago;
                }
                $factura->monto_factura -= $monto_deducible;
            }
            $monto_por_cobrar = $facturas->sum('monto_factura');
        }

        //Repartidor con mas ordenes
        $idrepartidor = DB::table('ordenes_repartidores')->select('idRepartidor', DB::raw('count(idRepartidor) as count'))
                        ->groupBy('idRepartidor')
                        ->orderBy('count', 'desc')->first();
        //Checkear si el array esta vacio (evitar errores en el dashboard por DB vacia)
        if(!is_null($idrepartidor)){
            $repartidor = User::findOrFail($idrepartidor->idRepartidor);
        }


        // Top 5 clientes con mas ordenes, Relacion Clientes - # de ordenes
        $idclientes =   DB::table('ordens')
                        ->select('idCliente', DB::raw('count(idCliente) as count'))
                        ->groupBy('idCliente')
                        ->orderBy('count', 'desc')
                        ->get();
        //Checkear si el array esta vacio (evitar errores en el dashboard por DB vacia)                    
        if(!is_null($idclientes)){
            foreach ($idclientes as $key => $value) {
                // $orden->count representa el count de cuantas ordenes tiene dicho cliente
                $clientes_top5[$value->count] = Cliente::find($value->idCliente);
            }
        }

        // Top 5 productos, Relacion Producto - cantidad vendida en ordenes

        $idproductos = DB::table('ordenes_productos')->selectRaw('idProducto, sum(cantidad_producto) as sp')
                                                    ->join('productos', 'ordenes_productos.idProducto','=','productos.id')
                                                    // ->where('idProducto', DB::raw('idProducto'))
                                                    ->groupBy('idProducto')
                                                    ->orderBy('sp', 'desc')
                                                    ->get();
        //Checkear si el array esta vacio (evitar errores en el dashboard por DB vacia)
        if(!is_null($idproductos)){
            foreach ($idproductos as $producto) {
                // $$producto->sp representa la suma total de dicho producto
                $productos_top5[$producto->sp] = Producto::find($producto->idProducto);
            }
        }
        return view('index', compact('monto_por_cobrar','ordenes_totales','cliente','repartidor','clientes_top5','productos_top5'));
    }

}