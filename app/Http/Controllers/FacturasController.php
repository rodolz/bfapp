<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Factura;
use App\Orden;
use App\Cliente;
use PDF;
use Alert;

class FacturasController extends Controller
{
    public function factura_pdf($id){

        $factura = Factura::find($id);
        $cliente = Cliente::find($factura->idCliente);   
        $pdf = PDF::loadView('facturas.pdf_factura', compact('cliente','factura'));
        return $pdf->stream();
    }

    public function actualizar_num_fiscal(Request $request){
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            //FORZAR EL UPDATE
            Factura::where('id', $request->idFactura)->update($request->except(['_method', '_token','idFactura']));
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";

    }


    public function nueva_factura(Request $request){
        $condicion = strtoupper($request->condicion);
        $itbms = $request->itbms;
        $orden = Orden::find($request->idOrden);
        $cliente = Cliente::find($orden->idCliente);


        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            //INICIO - INSERTAR la nueva factura en la DB
            $factura_max = DB::table('facturas')->max('num_factura');

            $cond = 1;
            if(is_null($factura_max)){
                $num_factura = 1;
            }
            else{
                $num_factura = $factura_max + 1;
            }
            // Inicio - Crear el obj factura e insertar
            $factura = new Factura();
            $factura->num_factura =  $num_factura;
            $factura->idOrden = $orden->id;
            $factura->idCliente = $cliente->id;
            $factura->condicion = $condicion;
            $factura->itbms = $itbms;
            $factura->subtotal = $orden->monto_orden;
            $factura->monto_factura = $orden->monto_orden + ($orden->monto_orden * $itbms/100);
            $factura->idFacturaEstado = 1;
            $factura->save();
            // FIN - Insertar la factura en la DB


            // Modificar Estado de la orden facturada
            $orden->idOrdenEstado = 2;
            $orden->save();
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";
    }
    public function index(Request $request)
    {
        $facturas = Factura::orderBy('num_factura','DESC')->paginate(10);
        return view('facturas.index',compact('facturas','repartidores'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

        // $facturas = comision::all();
        // return view('facturas.index')->with('facturas', $facturas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // $ordenes = DB::table('ordens')->where('idOrdenEstado', '1')->get();
        $ordenes = Orden::where('idOrdenEstado',1)->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($ordenes as $orden) {
            $ordenes_fmt[$orden->id] = '#'.$orden->num_orden.' - '.$orden->cliente->empresa;
         }
        return view('facturas.create', compact('ordenes_fmt'));//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'codigo' => 'required',
            'idCategoria' => 'required',
            'nombre_producto' => 'required',
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'cantidad' => 'required',
        ]);

        Factura::create($request->all());
        Alert::success('Control Creado')->autoclose(1000);
        return redirect()->route('facturas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factura = Factura::find($id);
        return view('facturas.show',compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function create_by_id($id)
    {
        $ordenes = Orden::where('idOrdenEstado',1)->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($ordenes as $orden) {
            $ordenes_fmt[$orden->id] = '#'.$orden->num_orden.' - '.$orden->cliente->empresa;
         }
        $orden = Orden::findorFail($id);
        return view('facturas.create-by-id',compact('ordenes_fmt','orden'));
    }
    public function edit(Request $request, $id)
    {
        $orden = Orden::findorFail($id);
        $ordenes = Orden::where('idOrdenEstado',1)->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($ordenes as $orden) {
            $ordenes_fmt[$orden->id] = '#'.$orden->num_orden.' - '.$orden->cliente->empresa;
         }
        return view('facturas.edit',compact('ordenes_fmt','orden'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buscar la factura
        $factura = Factura::find($id);

        //Buscar la orden ligada a la factura
        $orden = Orden::find($factura->idOrden);

        //Modificar el estado de la orden
        $orden->idOrdenEstado = 1;
        $orden->save();

        //Borrar la factura
        $factura->delete();
        Alert::success('Control Borrado')->autoclose(1000);
        return redirect()->route('facturas.index');
    }
}