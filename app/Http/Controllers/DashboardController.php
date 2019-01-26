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
use Carbon\Carbon;
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
            $cliente_top1 = Cliente::findorFail($cliente_mas_ordenes->idCliente);
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
        $repartidor_top1 = DB::table('ordenes_repartidores')->select('nombre', DB::raw('count(idRepartidor) as count'))
                        ->join('users', 'ordenes_repartidores.idRepartidor','=','users.id')
                        ->groupBy('nombre')
                        ->orderBy('count', 'desc')->first();

        // Top 5 clientes con mas ordenes, Relacion Clientes - # de ordenes
        // $clientes_top =   DB::table('ordens')
        //                 ->join('clientes','ordens.idCliente', '=', 'clientes.id')
        //                 ->select('clientes.empresa', DB::raw('count(idCliente) as count'))
        //                 ->groupBy('idCliente','clientes.empresa')
        //                 ->orderBy('count', 'desc')
        //                 ->get();
        // $clientes_top = DB::table('clientes')
        //                 ->join('ordens', 'clientes.id', '=', 'ordens.idCliente')
        //                 ->join('pagos', 'clientes.id', '=', 'pagos.idCliente')
        //                 ->select('empresa', DB::raw('sum(monto_pago) as pagado'),DB::raw('count(ordens.id) as count'))
        //                 ->groupBy('clientes.id','empresa')
        //                 ->orderBy('count', 'desc')
        //                 ->get();

        $clientes_top = Cliente::all();

        //Calculo de la deuda - Inicio
        foreach($clientes_top as $cliente){
            $facturas_pendientes = $cliente->facturas->whereIN('idFacturaEstado',[1,3]);
            $monto_total = 0;
            foreach($facturas_pendientes as $factura){
                if($factura->idFacturaEstado == 3){
                    $monto_deducible = 0;
                    foreach ($factura->pagos as $pago) {
                        $monto_deducible += $pago->pivot->monto_pago;
                    }
                    $factura->monto_factura -= $monto_deducible;
                }
                $monto_total += $factura->monto_factura;
            }
            $cliente->setAttribute('deuda',$monto_total);
        }
        // sortear el collection por deuda desc
        $clientes_top = $clientes_top->sortByDesc('deuda');
        //Calculo de la deuda - Fin

        $facturas = Factura::where('idFacturaEstado','2')
                    ->whereYear('created_at', 2018)
                    ->whereMonth('created_at', 10)
                    ->get();
        $sum_subtotal = $facturas->sum('subtotal');
        $sum_itbms = $sum_subtotal * 0.07;
        $sum_total = $facturas->sum('monto_factura');

        // Top 5 productos, Relacion Producto - cantidad vendida en ordenes
        $productos_top = DB::table('ordenes_productos')->selectRaw('codigo, descripcion, sum(cantidad_producto) as sp')
                                                    ->join('productos', 'ordenes_productos.idProducto','=','productos.id')
                                                    // ->where('idProducto', DB::raw('idProducto'))
                                                    ->groupBy('codigo', 'descripcion')
                                                    ->orderBy('sp', 'desc')
                                                    ->get();

        return view('index', compact('monto_por_cobrar','ordenes_totales','cliente_top1','repartidor_top1','clientes_top','productos_top','facturas','sum_subtotal','sum_itbms','sum_total'));
    }

}