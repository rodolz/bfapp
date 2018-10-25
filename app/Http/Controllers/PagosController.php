<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Cliente;
use App\Orden;
use App\Factura;
use App\Pago;
use Yajra\Datatables\Datatables;
use PDF;
use Validator;
use Alert;
class PagosController extends Controller
{

    public function getdata(){
        $pagos = Pago::orderBy('id','DESC')->get();

        foreach ($pagos as $pago) {
             foreach ($pago->facturas as $factura) {
                $cliente = Cliente::where('id',$factura->pivot->idCliente)->get()->first();
            }
            // Se agrega un attributo cliente al objeto pago, con el nombre del cliente que hizo el pago
            $pago['cliente'] = $cliente->empresa;
            $pago['fecha'] = date_format($pago->created_at, "d-m-Y");
        }
        return Datatables::of($pagos)
                            ->addColumn('action', function ($pago) {
                                $token = csrf_token();
                            return "
                            <a class='btn btn-info' href='pagos/{$pago->id}'><i class='fa fa-list-alt' aria-hidden='true'></i></a>
                            <form id='delete_pago' method='POST' action='pagos/{$pago->id}' accept-charset='UTF-8' style='display:inline'>
                                <input name='_method' type='hidden' value='DELETE'>
                                <input type='hidden' name='_token' value='{$token}'>
                                <button id='delete' type='submit' class='btn btn-danger'>
                                    <i class='fa fa-trash-o' aria-hidden='true'></i>
                                 </button>
                            </form>";
                             })
                            ->make(true);
    }

    public function index(Request $request)
    {
        return view('pagos.index');
    }

    public function check_monto (Request $request){


        $facturas = Factura::whereIn('id', $request->idFacturas)
                            ->get();
        $monto_total = 0;
        foreach ($facturas as $factura) {
            if($factura->idFacturaEstado == 3){
                $monto_deducible = 0;
                foreach ($factura->pagos as $pago) {
                    $monto_deducible += $pago->pivot->monto_pago;
                }
                $factura->monto_factura -= $monto_deducible;
            }
            $monto_total += $factura->monto_factura; 
        }                                    
        return number_format($monto_total,2,'.',',');
    }

    public function cuentas_por_cobrar_index(){
        $clientes = Cliente::pluck('empresa','id');
        return view('pagos.cuentas_por_cobrar_index', compact('clientes'));
    }

    public function cuentas_por_cobrar(Request $request){

        $customMessages = [
            'idCliente.required' => 'Debe escojer a un cliente',
        ];

        $validator = Validator::make($request->all(), [
            'idCliente' => 'required',
        ],$customMessages);

        if ($validator->fails()) {
            $errores = $validator->errors();
            // dd($errores->get('idCliente'));
            Alert::error($errores->first('idCliente'));
            return redirect()->back();
        }     


        $cliente = Cliente::findOrfail($request->idCliente);

        // Se buscan las facturas por cobrar
        $facturas = Factura::where('idCliente', '=', $cliente->id)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->get();
        // $monto_total =      Factura::where('idCliente', '=', $cliente->id)
        //                     ->where('idFacturaEstado', '=', 1)
        //                     ->orWhere('idFacturaEstado', '=', 3)
        //                     ->sum('monto_factura');       
        $monto_total = 0;
        foreach ($facturas as $factura) {
            if($factura->idFacturaEstado == 3){
                $monto_deducible = 0;
                foreach ($factura->pagos as $pago) {
                    $monto_deducible += $pago->pivot->monto_pago;
                }
                $factura->monto_factura -= $monto_deducible;
            }
            $monto_total += $factura->monto_factura; 
        }
        return view('pagos.cuentas_por_cobrar', compact('cliente','facturas','monto_total'));
    }

    public function estado_cuenta_pdf($id){
        $cliente = Cliente::findOrfail($id);
        // Se buscan las facturas por cobrar
        $facturas = Factura::where('idCliente', '=', $cliente->id)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->get();

        // $monto_factura =    Factura::where('idCliente', '=', $cliente->id)
        //                     ->where('idFacturaEstado', '=', 1)
        //                     ->orWhere('idFacturaEstado', '=', 3)
        //                     ->sum('monto_factura');       

        $monto_factura = 0;
        foreach ($facturas as $factura) {
            if($factura->idFacturaEstado == 3){
                $monto_deducible = 0;
                foreach ($factura->pagos as $pago) {
                    $monto_deducible += $pago->pivot->monto_pago;
                }
                $factura->monto_factura -= $monto_deducible;
            }
            $monto_factura += $factura->monto_factura; 
        }
        $pdf = PDF::loadView('pagos.estado_cuenta_pdf', compact('cliente','facturas','monto_factura'));
        return $pdf->stream();
    }

    public function nuevo_pago_index(Request $request){

        $clientes = Cliente::pluck('empresa','id');
        return view('pagos.nuevo_pago_index', compact('clientes'));
    }

    public function nuevo_pago(Request $request){

        $customMessages = [
            'idCliente.required' => 'Debe escojer a un cliente',
        ];

        $this->validate($request, [
            'idCliente' => 'required',
        ], $customMessages);
        
        $cliente = Cliente::findOrfail($request->idCliente);

        // Se buscan las facturas por cobrar
        $facturas = Factura::where('idCliente', '=', $cliente->id)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->pluck('num_factura','id');

        return view('pagos.nuevo_pago_facturas', compact('cliente','facturas'));
    }

    public function nuevo_pago_resumen(Request $request, $id){

        if(!isset($request->facturas)){
            return redirect()->action('PagosController@nuevo_pago_index')->withErrors('Debe seleccionar al menos una factura, intente de nuevo');
        }

        $cliente = Cliente::findOrfail($id);
        $facturas = Factura::whereIn('id',$request->facturas)
                            ->orderBy('id','asc')
                            ->get();

        $monto_total = 0;
        foreach ($facturas as $factura) {
            if($factura->idFacturaEstado == 3){
                $monto_deducible = 0;
                foreach ($factura->pagos as $pago) {
                    $monto_deducible += $pago->pivot->monto_pago;
                }
                $factura->monto_factura -= $monto_deducible;
            }
            $monto_total += $factura->monto_factura; 
        }
        // Ordenar de mayor a menos amount
        // $facturas = $facturas->sortByDesc('monto_factura');

        $monto_total = number_format($monto_total,2,'.',',');

        return view('pagos.nuevo_pago_resumen', compact('cliente','facturas','monto_total'));
    }

    public function show($id)
    {
        $monto_total = 0;
        $pago = pago::find($id);

        foreach ($pago->facturas as $factura) {
            $monto_total += $factura->monto_factura;
        }
        $monto_total = number_format($monto_total,2,'.',',');
        return view('pagos.show',compact('pago','monto_total'));
    }

    public function destroy($id)
    {
        $pago = Pago::find($id);

        foreach ($pago->facturas as $factura) {
            //Modificar el estado de cada factura
            $count = DB::table('facturas_pagos')
                    ->select(DB::raw('count(idFactura) as count'))
                    ->where('facturas_pagos.idFactura','=',$factura->id)
                    ->get();
                    
            if($count[0]->count > 1){
                $factura->idFacturaEstado = 3;
                $factura->save();
            }else{
                $factura->idFacturaEstado = 1;
                $factura->save();
            }
        }

        //Borrar el pago
        $pago->delete();
        return redirect()->route('pagos.index')
                        ->with('success','Pago Borrado!');
    }

    public function guardar_pago(Request $request){

        $cliente = Cliente::findOrfail($request->idCliente);
        $tipo_pago = $request->tipo_pago;
        $monto_pago = $request->monto_pago;
        $facturas = Factura::whereIn('id', $request->facturas)
                    ->orderBy('id','asc')
                    ->get();

        $monto_total = 0;
        foreach ($facturas as $factura) {
            if($factura->idFacturaEstado == 3){
                $monto_deducible = 0;
                foreach ($factura->pagos as $pago) {
                    $monto_deducible += $pago->pivot->monto_pago;
                }
                $factura->monto_factura -= $monto_deducible;
            }
            $monto_total += $factura->monto_factura; 
        }

        // Ordenar de mayor a menor el amount
        // $facturas = $facturas->sortByDesc('monto_factura');

        $monto_pago = number_format($monto_pago, 2, ".","");
        $monto_total = number_format($monto_total, 2, ".","");
        
        //Cambiar el string de la fecha a  DateTime Format
        $fecha = date_create($request->created_at);
        //Formatear la fecha
        $fecha = date_format($fecha, 'Y-m-d');
        // Inicio de la transaccion
        DB::beginTransaction();
            try{
                if($tipo_pago == 1){
                    $banco = $request->banco;
                    $numero_referencia = $request->numero_referencia;
                    $pago = new Pago();
                    $pago->idTipoPago =  $tipo_pago;
                    $pago->banco = $banco;
                    $pago->numero_referencia = $numero_referencia;
                    $pago->monto_pago = $monto_pago;
                    $pago->descripcion = 'N/A';
                    $pago->created_at = $fecha;
                    $pago->save();
                }
                else if($tipo_pago == 2){
                    $pago = new Pago();
                    $pago->idTipoPago =  $tipo_pago;
                    $pago->banco = "N/A";
                    $pago->numero_referencia = "N/A";
                    $pago->monto_pago = $monto_pago;
                    $pago->descripcion = 'Pago Recibido en efectivo';
                    $pago->created_at = $fecha;
                    $pago->save();
                }
                else if($tipo_pago == 3){
                    $banco = $request->banco;
                    $numero_referencia = $request->numero_referencia;
                    $pago = new Pago();
                    $pago->idTipoPago =  $tipo_pago;
                    $pago->banco = $banco;
                    $pago->numero_referencia = $numero_referencia;
                    $pago->monto_pago = $monto_pago;
                    $pago->descripcion = 'N/A';
                    $pago->save();
                }
                else if($tipo_pago == 4){
                    $descripcion = $request->descripcion;
                    $pago = new Pago();
                    $pago->idTipoPago =  $tipo_pago;
                    $pago->banco = "N/A";
                    $pago->numero_referencia = "N/A";
                    $pago->monto_pago = $monto_pago;
                    $pago->descripcion = $descripcion;
                    $pago->save();
                }
                // Se debe hacer un nuevo pull de las facturas para no sobreescribir el monto modificado anteriomente
                $facturas_original = Factura::whereIn('id', $request->facturas)->get();
                //Actualizar el estado de la factura a 'Cobrado' o 'Abonado'

                if($monto_total == $monto_pago){
                    foreach ($facturas_original as $factura_original) {
                        $factura_original->update(['idFacturaEstado' => 2]);
                        $factura_original->pagos()->attach($pago->id, ['idCliente' => $cliente->id, 'monto_pago' => $monto_pago]);
                    }
                }
                else{
                    foreach ($facturas as $factura) {
                        if($monto_pago >= $factura->monto_factura){
                            $facturas_original->where('id', $factura->id)
                                            ->first()
                                            ->update(['idFacturaEstado' => 2]);
                            $monto_pago = $monto_pago - $factura->monto_factura;
                            $facturas_original->where('id', $factura->id)
                                            ->first()
                                            ->pagos()
                                            ->attach($pago->id, ['idCliente' => $cliente->id, 'monto_pago' => $factura->monto_factura]);
                        }
                        else if($factura->monto_factura >= $monto_pago && $monto_pago != 0){
                            $facturas_original->where('id', $factura->id)
                                            ->first()
                                            ->update(['idFacturaEstado' => 3]);

                            $facturas_original->where('id', $factura->id)
                                                ->first()
                                                ->pagos()
                                                ->attach($pago->id, ['idCliente' => $cliente->id, 'monto_pago' => $monto_pago]);
                            $monto_pago = 0;
                        }
                    }

                }
            } catch(\Exception $e){
                DB::rollback();
                return $e->getMessage();
            }
        DB::commit();
        return "ok";     
    } 
}