<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Comision;
use App\Factura;
use App\Orden;
use App\User;
use App\Cliente;
use App\Producto;
use Codedge\Fpdf\Facades\Fpdf;

class FacturasController extends Controller
{

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

    //MOSTRAR EL PDF
    public function pdf($idFactura, $idOrden=null){

        if(!is_null($idOrden)){
            $orden = Orden::find($idOrden);
            $factura = Factura::where('idOrden',$idOrden)->first();
            $cliente = Cliente::find($factura->idCliente);
        }
        else{
            $factura = Factura::find($idFactura);
            $cliente = Cliente::find($factura->idCliente);
            $orden = Orden::find($factura->idOrden);
        }
        //formatear el nombre del cliente, direccion en caracteres de castellano
        $converted_cliente = utf8_decode($cliente->empresa);
        $converted_direccion = utf8_decode($cliente->direccion);
        
        Fpdf::SetTopMargin(1);
        Fpdf::AddPage();
        Fpdf::SetFont('Arial','B', 11);
        Fpdf::Image("images/cintillo_control_old.jpg",0,-4,216,45);
        // $nueva_y = Fpdf::GetY();
        Fpdf::SetY(40);
        Fpdf::SetX(160);
        $string = utf8_decode('Control N° ');
        Fpdf::SetFont('Arial','B', 13);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::Cell(40, 15, $string. $factura->num_factura ,0,0,'L',false);
        Fpdf::SetTextColor(0,0,0);
        // Fpdf::SetY($nueva_y);
        Fpdf::Ln(12);
        Fpdf::SetFont('Arial','B', 11);
        Fpdf::Cell(100, 8, 'Cliente: '.$converted_cliente,0,0,'L',false);
        Fpdf::SetFont('Arial','', 11);
        Fpdf::SetX(160);
        Fpdf::Cell(40, 8, 'Fecha:   '.$factura->created_at->format('d-m-Y'),0,0,'L',false);
        Fpdf::Ln(8);
        Fpdf::Cell(100, 8, 'RUC/CI: '.$cliente->ruc,0,0,'L',false);
        Fpdf::Ln(8);
        Fpdf::Cell(135, 8, 'Direccion: '.$converted_direccion,0,0,'L',false);
        Fpdf::SetX(160);
        Fpdf::Cell(40, 8, 'Telf: '.$cliente->tel_local,0,0,'L',false);
        Fpdf::Ln(25);
        // Fpdf::SetFillColor(255,255,255);
        Fpdf::SetFont('Arial','', 10);
        Fpdf::Cell(25, 8, 'Codigo',1,0,'C',false);
        Fpdf::Cell(95, 8, 'Descripcion',1,0,'C',false);
        Fpdf::Cell(20, 8, 'Cantidad',1,0,'C',false);
        Fpdf::Cell(25, 8, 'Precio',1,0,'C',false);
        Fpdf::Cell(25, 8, 'Importe',1,0,'C',false);
        Fpdf::Ln(8);
        Fpdf::SetFont('Arial','', 9);
        foreach ($orden->ordenes_productos as $producto) {
            Fpdf::Cell(25, 10,$producto->codigo,'L,R',0,'C',false);
            Fpdf::Cell(95, 10,$producto->descripcion,'R',0,'L',false);
            $cantidad_producto = $producto->pivot->cantidad_producto;
            $precio_final = $producto->pivot->precio_final;
            Fpdf::Cell(20, 10,$cantidad_producto,'R',0,'C',false);
            Fpdf::Cell(25, 10,'B/.'.number_format($precio_final,2,'.',','),'R',0,'R',false);
            $precio_x_cantidad = $precio_final*$cantidad_producto;
            $precio_x_cantidad_formateado = number_format($precio_x_cantidad,2);
            Fpdf::Cell(25, 10,'B/.'. $precio_x_cantidad_formateado,'R',0,'R',false);
            Fpdf::Ln(10);
        }
        Fpdf::Cell(25,8,'','T',0,'C',false);
        Fpdf::Cell(95, 8,'Condicion de pago: '.$factura->condicion,'T',0,'L',false);
        Fpdf::Cell(20,8,'','T',0,'C',false);
        Fpdf::Cell(25, 8,'SUB-TOTAL:','T',0,'L',false);
        Fpdf::Cell(25, 8,'B/.'.number_format($orden->monto_orden,2,'.',','),1,0,'R',false);
        Fpdf::Ln(8);
        Fpdf::Cell(140,8,'',0,0,'C',false);
        Fpdf::Cell(25, 8,'ITBMS '.$factura->itbms.'% :',0,0,'L',false);
        $impuesto = ($orden->monto_orden * $factura->itbms)/100;
        $impuesto_formateado = number_format($impuesto,2,'.',',');
        Fpdf::Cell(25, 8,'B/.'.$impuesto_formateado,1,0,'R',false);
        Fpdf::Ln(8);
        Fpdf::Cell(140,8,'',0,0,'C',false);
        Fpdf::Cell(25, 8,'TOTAL:',0,0,'L',false);
        $total = number_format($orden->monto_orden + $impuesto,2,'.',',');
        Fpdf::Cell(25, 8,'B/.'.$total,1,0,'R',false);

        // FOOTER
        Fpdf::SetY(-32);
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85, 10, 'Favor emitir cheques a nombre de BF Services, s.a','T',0,'L',false);
        Fpdf::Cell(60, 10, '','T',0,'L',false);
        Fpdf::Cell(45, 10, 'Copia-Documento no fiscal','T',0,'L',false);
        $sting = Fpdf::Output('S','Control_'.$factura->num_factura.'.pdf',true);
        Fpdf::Output('I','Control_'.$factura->num_factura.'.pdf',true);
    }



    public function nueva_factura(Request $request){
        $pago_total = 0;
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
        $ordenes = DB::table('ordens')->where('idOrdenEstado', '1')->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($ordenes as $orden) {
             $ordenes_fmt[$orden->id] = 'Orden #'.$orden->num_orden;
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
        return redirect()->route('facturas.index')
                        ->with('success','Factura Agregada!');
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
        $ordenes = DB::table('ordens')->where('idOrdenEstado', '1')->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($ordenes as $orden) {
             $ordenes_fmt[$orden->id] = 'Orden #'.$orden->num_orden;
         }
        $orden = Orden::findorFail($id);
        return view('facturas.create-by-id',compact('ordenes_fmt','orden'));
    }
    public function edit(Request $request, $id)
    {
        $orden = Orden::findorFail($id);
        $ordenes = DB::table('ordens')->where('idOrdenEstado', '1')->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($ordenes as $orden) {
             $ordenes_fmt[$orden->id] = 'Orden #'.$orden->num_orden;
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
        $this->validate($request, [
            'codigo' => 'required',
            'nombre_producto' => 'required',
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'cantidad' => 'required',
        ]);

        Factura::find($id)->update($request->all());
        return redirect()->route('facturas.index')
                        ->with('success','Factura Modificada!');
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
        return redirect()->route('facturas.index')
                        ->with('success','Factura Borrada!');
    }
}