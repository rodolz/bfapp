<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Producto;
use App\Factura;
use App\Categoria;
use PDF;
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
        $productos_disponibles = Producto::where('cantidad','>','0')
                                ->orderBy('idCategoria','desc')
                                ->get();
        $monto_total = 0;
        $monto_total_costo = 0;
        $cantidad_total = 0;
                        
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
        $pdf = PDF::loadView('productos.pdf_inventario', compact('productos_disponibles','monto_total_costo','monto_total','cantidad_total'));
        return $pdf->stream();
    }

}