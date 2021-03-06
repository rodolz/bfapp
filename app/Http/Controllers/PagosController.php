<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Cliente;
use App\Factura;
use App\Pago;
use Yajra\Datatables\Datatables;
use PDF;
use Validator;
use Alert;

class PagosController extends Controller
{

    public function getdata(){
        // $pagos = Pago::orderBy('id','DESC')->get();
        $pagos = Pago::with('cliente')->orderBy('id','desc');

        // foreach ($pagos as $pago) {
        //      foreach ($pago->cliente as $cliente) {
        //          // Se agrega un attributo cliente al objeto pago, con el nombre del cliente que hizo el pago 
        //         $pago['cliente'] = $asd;
        //         $pago['fecha'] = date_format($pago->created_at, "d-m-Y"); 
        //     }
                
        // }
            
        
        return Datatables::of($pagos)
                            ->addColumn('action', function ($pago) {
                                $token = csrf_token();
                            return "
                            <div class='acciones-btn'>
                            <a class='btn btn-info' href='pagos/{$pago->id}'><i class='fa fa-list-alt'></i></a>
                            <form id='delete_pago' name='deleteForm' onclick='deletePrompt()' method='POST' action='pagos/{$pago->id}' accept-charset='UTF-8' style='display:inline'>
                                <input name='_method' type='hidden' value='DELETE'>
                                <input type='hidden' name='_token' value='{$token}'>
                                <button id='delete' type='submit' class='btn btn-danger'>
                                    <i class='fas fa-trash-alt' aria-hidden='true'></i>
                                 </button>
                            </form>
                            </div>";
                             })
                            ->editColumn('created_at', function ($pago) {
                                return $pago->created_at->format('d/m/Y');
                            })
                            ->make(true);
    }

    public function index()
    {
        return view('pagos.index');
    }

    public function check_monto (Request $request){


        $facturas = Factura::whereIn('id', $request->idFacturas)->get();
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


        $this->validate($request, [
            'clientes' => 'required',
        ],
        [
            'clientes.required' => 'Debe seleccionar un cliente',
        ]);  

        // Se buscan las facturas por cobrar
        $facturas = Factura::whereIn('idCliente', $request->clientes)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->orderBy('idCliente','ASC')
                            ->orderBy('created_at','ASC')
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

        if(count($request->clientes) == 1){
            $cliente = Cliente::findorFail($request->clientes)->first();
            return view('pagos.cuentas_por_cobrar', compact('cliente','facturas','monto_total'));
        }
        else{
            $clientes = urlencode(serialize($request->clientes));
            return view('pagos.cuentas_por_cobrar_multiple', compact('clientes','facturas','monto_total'));
        }
    }

    public function estado_cuenta_pdf($id){

        $cliente = Cliente::findorFail($id);
        // Se buscan las facturas por cobrar
        $facturas = Factura::where('idCliente', $cliente->id)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->orderBy('idCliente','ASC')
                            ->orderBy('created_at','ASC')
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
        
        $pdf = PDF::loadView('pagos.estado_cuenta_pdf', compact('cliente','facturas','monto_total'));
        
        return $pdf->stream();
    }

    public function estado_cuenta_pdf_multiple($ids){

        $ids = unserialize(urldecode($ids));

        // Se buscan las facturas por cobrar
        $facturas = Factura::whereIn('idCliente', $ids)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->orderBy('idCliente','ASC')
                            ->orderBy('created_at','ASC')
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
        
        $pdf = PDF::loadView('pagos.estado_cuenta_pdf_multiple', compact('facturas','monto_total'));
        return $pdf->stream('estado_de_cuenta_multiple.pdf');
    }
    public function nuevo_pago_index(Request $request){

        $clientes = Cliente::pluck('empresa','id');
        return view('pagos.nuevo_pago_index', compact('clientes'));
    }

    public function nuevo_pago(Request $request){

        $this->validate($request, [
            'idCliente' => 'required',
        ],
        [
            'idCliente.required' => 'Debe seleccionar a un cliente',
        ]);
        
        $cliente = Cliente::findOrfail($request->idCliente);

        // Se buscan las facturas por cobrar
        $facturas = Factura::where('idCliente', '=', $cliente->id)
                            ->whereIn('idFacturaEstado', [1,3])
                            ->pluck('num_fiscal','id');

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
        $pago = Pago::find($id);

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
        Alert::success('Pago Borrado')->autoclose(1000);
        return redirect()->back();
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
                    $pago->idCliente = $cliente->id;
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
                    $pago->idCliente = $cliente->id;
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
                    $pago->idCliente = $cliente->id;
                    $pago->idTipoPago =  $tipo_pago;
                    $pago->banco = $banco;
                    $pago->numero_referencia = $numero_referencia;
                    $pago->monto_pago = $monto_pago;
                    $pago->descripcion = 'N/A';
                    $pago->created_at = $fecha;
                    $pago->save();
                }
                else if($tipo_pago == 4){
                    $descripcion = $request->descripcion;
                    $pago = new Pago();
                    $pago->idTipoPago =  $tipo_pago;
                    $pago->idCliente = $cliente->id;
                    $pago->banco = "N/A";
                    $pago->numero_referencia = "N/A";
                    $pago->monto_pago = $monto_pago;
                    $pago->descripcion = $descripcion;
                    $pago->created_at = $fecha;
                    $pago->save();
                }
                // Se debe hacer un nuevo pull de las facturas para no sobreescribir el monto modificado anteriomente
                $facturas_original = Factura::whereIn('id', $request->facturas)->get();
                //Actualizar el estado de la factura a 'Cobrado' o 'Abonado'

                if($monto_total == $monto_pago){
                    foreach ($facturas_original as $factura_original) {
                        $factura_original->update(['idFacturaEstado' => 2]);
                        $factura_original->pagos()->attach($pago->id, ['monto_pago' => $monto_pago]);
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
                                            ->attach($pago->id, ['monto_pago' => $factura->monto_factura]);
                        }
                        else if($factura->monto_factura >= $monto_pago && $monto_pago != 0){
                            $facturas_original->where('id', $factura->id)
                                            ->first()
                                            ->update(['idFacturaEstado' => 3]);

                            $facturas_original->where('id', $factura->id)
                                                ->first()
                                                ->pagos()
                                                ->attach($pago->id, ['monto_pago' => $monto_pago]);
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