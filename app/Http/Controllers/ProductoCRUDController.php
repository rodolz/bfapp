<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Producto;
use App\Orden;
use App\Factura;
use App\Categoria;
use Codedge\Fpdf\Facades\Fpdf;
use Yajra\Datatables\Datatables;

class ProductoCRUDController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:crear-producto', ['only' => ['create']]);
    //     $this->middleware('permission:editar-producto',   ['only' => ['edit']]);
    //     $this->middleware('permission:ver-productos',   ['only' => ['show', 'index']]);
    //     $this->middleware('permission:borrar-producto',   ['only' => ['destroy']]);
    // }
    //datatables view

    public function index(){
        return view('productos.index');
    }

    public function actualizar_producto(Request $request){
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            //FORZAR EL UPDATE
            Producto::where('id', $request->idProducto)
                    ->update([$request->type => $request->value]);
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";

    }

    public function getdata(){
        $productos = Producto::with('categoria')->orderBy('id','desc');
        return Datatables::of($productos)
                            ->addColumn('action', function ($producto) {
                                $token = csrf_token();
                            return "<div class='acciones-btn'>
                            <a class='btn btn-orange' href='productos/{$producto->id}'><i class='fa fa-eye' aria-hidden='true'></i></a>
                            <a class='btn btn-info' href='productos/{$producto->id}/edit'><i class='fa fa-pencil' aria-hidden='true'></i></a>
                            <form method='POST' action='productos/{$producto->id}' accept-charset='UTF-8' style='display:inline'>
                                <input name='_method' type='hidden' value='DELETE'>
                                <input type='hidden' name='_token' value='{$token}'>
                                <button type='submit' class='btn btn-danger'>
                                    <i class='fa fa-trash-o' aria-hidden='true'></i>
                                 </button>
                            </form></div>";
                             })
                            ->make(true);
    }
    //
    public function check_inventario(Request $request){
        $producto = Producto::where('id', $request->idProducto)->first();
        if($request->cantidad > $producto->cantidad){
            return "No Disponible";
        }
        else{
            return "Disponible";
        }
    }
    public function check_precio(Request $request){
        $producto = Producto::where('id', $request->idProducto)->first();
        return number_format($producto->precio,2,'.',',');
    }
    public function create()
    {
        //Con pluck() se crea el array asociativo con los atributos deseados
        $categorias = Categoria::pluck('nombre_categoria', 'id');
        return view('productos.create',compact('categorias'));//
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
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'precio_costo' => 'required',
            'cantidad' => 'required',
        ]);

        Producto::create($request->all());
        return redirect()->route('productos.index')
                        ->with('success','Producto Agregado!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $monto_total = 0;
        $cantidad_total = 0;
        $producto = Producto::find($id);
        $ordenes = $producto->ordenes;
        foreach ($producto->ordenes as $orden) {
            $factura = Factura::where('idOrden', $orden->id)->first();
            if(is_null($factura)){
                $orden->id = 0;
                $orden->num_orden = 'N/A';
            }else{
                //cambiar el id de la orden por el de factura
                $orden->id = $factura->id;
                //cambiar el num de la orden por el de factura
                $orden->num_orden = $factura->num_factura;
            }
            
            $monto_total = $monto_total + ($orden->pivot->cantidad_producto*$orden->pivot->precio_final);
            $cantidad_total = $cantidad_total + $orden->pivot->cantidad_producto;
        }
        return view('productos.show',compact('producto','ordenes','monto_total','cantidad_total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $producto = Producto::find($id);
        $categorias = Categoria::pluck('nombre_categoria', 'id');
        return view('productos.edit',compact('producto','categorias'));
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
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'precio_costo' => 'required',
            'cantidad' => 'required',
        ]);

        Producto::find($id)->update($request->all());
        return redirect()->route('productos.index')
                        ->with('success','Producto Modificado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Producto::find($id)->delete();
        return redirect()->route('productos.index')
                        ->with('success','Producto Borrado!');
    }

    public function inventario(){
        $monto_total = 0;
        $monto_total_costo = 0;
        $cantidad_total = 0;
        $productos_disponibles = Producto::where('cantidad','>','0')
                                ->orderBy('idCategoria','desc')
                                ->get();
        // filtrar los servicios de los productos
        $productos_disponibles = $productos_disponibles->filter(function($producto){
            if($producto->idCategoria !== 8 ){
                return $producto;
            }
        }); 
        foreach ($productos_disponibles as $producto) {
            $monto_total = $monto_total + $producto->precio * $producto->cantidad;
            $monto_total_costo = $monto_total_costo + $producto->precio_costo * $producto->cantidad;
            $cantidad_total = $cantidad_total + $producto->cantidad;
        }
        return view('productos.inventario',compact('productos_disponibles','monto_total','monto_total_costo','cantidad_total'));
    }

    public function inventario_pdf(){
        $monto_total = 0;
        $monto_total_costo = 0;
        $cantidad_total = 0;

        $productos_disponibles = Producto::where('cantidad','>','0')
                                ->orderBy('idCategoria','desc')
                                ->get();
        // filtrar los servicios de los productos
        $productos_disponibles = $productos_disponibles->filter(function($producto){
            if($producto->idCategoria !== 8 ){
                return $producto;
            }
        }); 
        foreach ($productos_disponibles as $producto) {
            $monto_total = $monto_total + $producto->precio * $producto->cantidad;
            $monto_total_costo = $monto_total_costo + $producto->precio_costo * $producto->cantidad;
            $cantidad_total = $cantidad_total + $producto->cantidad;
        }
        $categoria = '';
        Fpdf::SetTopMargin(10);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::AddPage();

        Fpdf::SetFont('Times', 'B', 15);

        Fpdf::Cell(35, 15, 'Inventario', 0,0,'L',false);
        Fpdf::Ln(18);
        Fpdf::SetFont('Times', 'B', 12);
        Fpdf::Cell(30, 10, 'Codigo', 'T L',0,'L',false);
        Fpdf::Cell(85, 10, 'Descripcion', 'T',0,'L',false);
        Fpdf::Cell(25, 10, 'Medidas', 'T',0,'L',false);
        Fpdf::Cell(14, 10, 'Ctd', 'T',0,'L',false);
        Fpdf::Cell(23, 10, 'P.Venta', 'T',0,'L',false);
        Fpdf::Cell(23, 10, 'P.Costo', 'T R',0,'L',false);
        Fpdf::Ln(10);
        // Fpdf::SetFillColor(255,255,255);
        // Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Times', '', 12);
        $cont = 0;
        foreach ($productos_disponibles as $producto) {
            if($cont == 15){
                Fpdf::Cell(195, 10, 'Continuar en la siguiente Pagina' ,'T',0,'L',false);
                Fpdf::AddPage();
                Fpdf::SetFont('Times', 'B', 15);
                Fpdf::Cell(35, 15, 'Inventario (Continuacion)', 0,0,'L',false);
                Fpdf::Ln(18);
                Fpdf::SetFont('Times', 'B', 12);
                Fpdf::Cell(30, 12, 'Codigo', 'T L',0,'L',false);
                Fpdf::Cell(85, 12, 'Descripcion', 'T',0,'L',false);
                Fpdf::Cell(25, 12, 'Medidas', 'T',0,'L',false);
                Fpdf::Cell(14, 12, 'Ctd', 'T',0,'L',false);
                Fpdf::Cell(23, 12, 'P.Venta', 'T',0,'L',false);
                Fpdf::Cell(23, 12, 'P.Costo', 'T R',0,'L',false);
                Fpdf::SetFont('Times', '', 11);
                Fpdf::Ln(10);
                $cont = 0;
            }
            if($producto->categoria->nombre_categoria != $categoria){
                Fpdf::SetFont('Times', 'B', 11);
                Fpdf::SetFillColor(154,204,119);
                Fpdf::SetTextColor(255,255,255);
                Fpdf::Cell(200, 12, $producto->categoria->nombre_categoria, 'L R',1,'L',true);
                Fpdf::SetFont('Times', '', 11);
                Fpdf::SetTextColor(0,0,0);
                $categoria = $producto->categoria->nombre_categoria;
                $cont++;
            }
            Fpdf::SetFillColor(255,255,255);
            $h = 11;
            if(strlen($producto->descripcion)>47){
                $h = 22;
            }
            Fpdf::Cell(30, $h, $producto->codigo, 1,0,'L',false);
            $x = Fpdf::GetX();
            $y = Fpdf::GetY();
            Fpdf::MultiCell(85, 11,$producto->descripcion, 1);
            $H = Fpdf::GetY();
            $diff_h= $H-$y;
            $nuevo_h = $y + $diff_h;
            Fpdf::SetXY($x + 85, $y);
            // Fpdf::Cell(35, 11, $producto->codigo, 'L',0,'L',false);
            // Fpdf::Cell(80, 11,$producto->descripcion, 0,0,'L',false);
            Fpdf::Cell(25, $h,$producto->medidas, 1,0,'L',false);
            Fpdf::Cell(14, $h, $producto->cantidad, 1,0,'L',false);   
            Fpdf::Cell(23, $h, '$'.number_format($producto->precio,2), 1,0,'L',false);
            Fpdf::Cell(23, $h, '$'.number_format($producto->precio_costo,2), 1,0,'L',false);
            Fpdf::Ln(11);
            Fpdf::SetY($nuevo_h);
            $cont++;
        }
        Fpdf::SetFont('Times', 'B', 12);
        Fpdf::SetFillColor(162, 166, 169);
        Fpdf::Cell(30, 12, 'Totales', 'T L',0,'L',true);
        Fpdf::Cell(110, 12, '', 'T R',0,'L',true);
        Fpdf::Cell(14, 12, $cantidad_total, 'T R',0,'L',true);
        Fpdf::Cell(23, 12, '$'.number_format($monto_total,2,'.',','), 'T R',0,'L',true);
        Fpdf::Cell(23, 12, '$'.number_format($monto_total_costo,2,'.',','), 'T R ',0,'L',true);
        Fpdf::SetFont('Times', '', 10);
        Fpdf::Ln(12);
        Fpdf::Cell(200, 10, 'Para la siguiente fecha: '.date('d/m/Y'), 'T',0,'L',false);
        Fpdf::Output('D',date('d/m/Y').' Inventario.pdf');
    }
}