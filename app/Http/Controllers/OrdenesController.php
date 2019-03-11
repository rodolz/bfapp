<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Orden;
use App\User;
use App\Cliente;
use App\Producto;
use App\Cotizacion;
use Alert;
use PDF;

class OrdenesController extends Controller
{

    public function index(Request $request)
    {
        $ordenes = Orden::orderBy('num_orden','DESC')->paginate(10);
        return view('ordenes.index',compact('ordenes','repartidores'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

        // $ordenes = Orden::all();
        // return view('ordenes.index')->with('ordenes', $ordenes);
    }

    public function orden_pdf($id){
        $orden = Orden::find($id);
        $cliente = Cliente::find($orden->idCliente);
        $vendedor = User::find($orden->idUsuario); 
        $pdf = PDF::loadView('ordenes.pdf_orden', compact('cliente','orden','vendedor'));
        return $pdf->stream();
    }

    public function create()
    {
        $repartidores = User::all()->pluck('nombre','id');
        // $users = User::all();
        // foreach ($users as $user) {
        //     dd($user->roles);
        //     if($user->rol() == 3){
        //         $repartidores[$user->id] = $user->nombre;
        //     }
        // }
        // con pluck() se crea un array asociativo con los datos de la db
        $clientes = Cliente::pluck('empresa','id');
        $productos = Producto::select(DB::raw("CONCAT(codigo,' | ',descripcion) as codigo_descripcion"),'id')
                                ->where('cantidad','>','0')
                                ->pluck('codigo_descripcion','id');

        return view('ordenes.create', compact('clientes','productos','repartidores'));//
    }

    // public function show($id)
    // {
    //     $orden = Orden::find($id);
    //     return view('ordenes.show',compact('orden'));
    // }

    public function edit(Request $request, $id)
    {
        $clientes = Cliente::pluck('empresa','id');
        $productos = Producto::select(DB::raw("CONCAT(codigo,' | ',descripcion) as codigo_descripcion"),'id')
                                ->where('cantidad','>','0')
                                ->pluck('codigo_descripcion','id');
                                
        $repartidores = User::pluck('nombre','id');
        $orden = Orden::find($id);

        $productos_seleccionados = $orden->ordenes_productos;
        $repartidores_seleccionados = $orden->ordenes_repartidores;

        // foreach ($productos_seleccionados as $producto) {
        //     dd($producto->pivot->cantidad_producto);
        //     // dd($producto->codigo);
        // }
        return view('ordenes.edit',compact('orden','clientes','productos','productos_seleccionados','repartidores','repartidores_seleccionados'));
    }

    public function nueva_orden_cotizacion($id)
    {
        $cotizacion = Cotizacion::findOrfail($id);
        $cliente_seleccionado = Cliente::where('id',$cotizacion->idCliente)->pluck('empresa','id');
        $productos = Producto::select(DB::raw("CONCAT(codigo,' | ',descripcion) as codigo_descripcion"),'id')
                                ->where('cantidad','>','0')
                                ->pluck('codigo_descripcion','id');
                                
        $repartidores = User::pluck('nombre','id');

        $productos_seleccionados = $cotizacion->cotizacion_producto;
        
        return view('ordenes.create_from_cotizacion',compact('cotizacion','productos_seleccionados','repartidores','cliente_seleccionado'));
    }

    public function update(Request $request)
    {
        $orden = Orden::findorFail($request->idOrden);

        //check if the products selected are the same to the previous products
        $productos_orden = $orden->ordenes_productos;
        //update those productos if they are diff or diff qty
        foreach ($productos_orden as $producto) {
            //Buscar la cantidad para reintegrar al inventario
            $cantidad_producto = $producto->pivot->cantidad_producto;
            //Buscar el producto para updatear
            $producto_inventario = Producto::findorFail($producto->id);
            //Actualizar la cantidad
            $producto_inventario->cantidad += $cantidad_producto;
            //Guardar en la DB
            $producto_inventario->save();
        }
        $orden->ordenes_productos()->detach();
        // check if the repartidores are the same, otherwise update them
        $orden->ordenes_repartidores()->detach();

        // update the orden
        $precio_total = 0;
        $repartidores = User::whereIn('id',$request->repartidores)->get();
        $cliente = Cliente::where('id', $request->idCliente)->first();
        $data = json_decode($request->data, true);
        $productos = array();
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                $producto_db = Producto::where('id',$producto['id'])->first();
                $producto_db->cantidad = $producto['cantidad'];
                $precio_x_cantidad = $producto['precio_final'] * $producto_db->cantidad;
                $precio_total = $precio_total + $precio_x_cantidad;
                array_push($productos,$producto_db);
            }
            foreach ($productos as $producto) {
                // si no es un servicio updatear la cantidad del inventario
                if($producto->idCategoria !== 8 ){    
                    //Buscar la cantidad disponible de cada producto
                    $cantidad_disponible = Producto::where('id',$producto->id)->value('cantidad');
                    //Determinar la cantidad final del producto
                    $cantidad_final = $cantidad_disponible - $producto->cantidad;
                    //Modificar la cantidad del producto en la db
                    Producto::where('id',$producto->id)->update(array('cantidad' => $cantidad_final));
                }
            }

            $orden->idCliente = $cliente->id;
            $orden->idUsuario = Auth::user()->id;
            $orden->monto_orden = $precio_total;
            // $orden->idOrdenEstado = 1;
            $orden->save();


            // INICIO - INSETAR CADA PRODUCTO EN ORDENES_PRODUCTOS

            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                DB::table('ordenes_productos')->insert(
                    [
                    'idOrden' => $orden->id,
                    'idProducto' => $producto['id'],
                    'cantidad_producto' => $producto['cantidad'],
                    'precio_final' => $producto['precio_final']
                    ]
                );
            }
            // FIN - INSETAR CADA PRODUCTO EN ORDENES_PRODUCTOS

            // INICIO - INSETAR CADA REPARTIDOR EN ORDENES_REPARTIDORES
            foreach ($repartidores as $repartidor) {
                DB::table('ordenes_repartidores')->insert(
                    [
                    'idOrden' => $orden->id, 
                    'idRepartidor' => $repartidor->id
                    ]
                );
            }
            // FIN - INSETAR CADA REPARTIDOR EN ORDENES_REPARTIDORES
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // Fin de la transaccion
        // SE hace el commit
        DB::commit();
        return "ok";

        // Orden::find($id)->update($request->all());
        // return redirect()->route('ordenes.index')
        //                 ->with('success','Orden Modificada!');
    }

    public function destroy($id)
    {
        $productos = Orden::find($id)->ordenes_productos;
        foreach ($productos as $producto) {
            //Buscar la cantidad para reintegrar al inventario
            $cantidad_producto = $producto->pivot->cantidad_producto;
            //Buscar el producto para updatear
            $producto_inventario = Producto::findorFail($producto->id);
            //Actualizar la cantidad
            $producto_inventario->cantidad += $cantidad_producto;
            //Guardar en la DB
            $producto_inventario->save();
        }
        $orden = Orden::findorFail($id)->delete();

        Alert::success("Nota de Entrega Eliminada!")->autoclose(1500);
        
        return redirect()->back();
    }

    // Crear order
    public function nueva_orden(Request $request){
        $precio_total = 0;
        $repartidores = User::whereIn('id',$request->repartidores)->get();
        $cliente = Cliente::where('id', $request->idCliente)->first();
        $data = json_decode($request->data, true);
        $productos = array();
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                $producto_db = Producto::where('id',$producto['id'])->first();
                $producto_db->cantidad = $producto['cantidad'];
                $precio_x_cantidad = $producto['precio_final'] * $producto_db->cantidad;
                $precio_total = $precio_total + $precio_x_cantidad;
                array_push($productos,$producto_db);
            }
            foreach ($productos as $producto) {
                if($producto->idCategoria !== 8 ){    
                    //Buscar la cantidad disponible de cada producto
                    $cantidad_disponible = Producto::where('id',$producto->id)->value('cantidad');
                    //Determinar la cantidad final del producto
                    $cantidad_final = $cantidad_disponible - $producto->cantidad;
                    //Modificar la cantidad del producto en la db
                    Producto::where('id',$producto->id)->update(array('cantidad' => $cantidad_final));
                }
            }

            //INICIO - INSERTAR la nueva orden en la DB
            $orden_max = DB::table('ordens')->max('num_orden');
            // $cond = 1;
            if(is_null($orden_max)){
                $num_orden = 1;
            }
            else{
                // while($cond <= $orden_max){
                //     $orden_faltante = Orden::where('num_orden',$cond)->first();
                //     if(is_null($orden_faltante)){
                //         $num_orden = $cond;
                //         break;
                //     }
                //     elseif($cond == $orden_max){
                //         $num_orden = $orden_max + 1;   
                //     }
                //     $cond++;
                // }
                $num_orden = $orden_max + 1;
            }
            $nueva_orden = Orden::create([
                'num_orden' => $num_orden,
                'idCliente' => $cliente->id,
                'idUsuario' => Auth::user()->id,
                'monto_orden' => $precio_total,
                'idOrdenEstado' => 1
            ]);
            // FIN - Insertar la orden en la DB

            // INICIO - INSETAR CADA PRODUCTO EN ORDENES_PRODUCTOS
            $orden_max_id = DB::table('ordens')->max('id');
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                DB::table('ordenes_productos')->insert(
                    [
                    'idOrden' => $orden_max_id,
                    'idProducto' => $producto['id'],
                    'cantidad_producto' => $producto['cantidad'],
                    'precio_final' => $producto['precio_final']
                    ]
                );
            }
            // FIN - INSETAR CADA PRODUCTO EN ORDENES_PRODUCTOS

            // INICIO - INSETAR CADA REPARTIDOR EN ORDENES_REPARTIDORES
            foreach ($repartidores as $repartidor) {
                DB::table('ordenes_repartidores')->insert(
                    [
                    'idOrden' => $orden_max_id, 
                    'idRepartidor' => $repartidor->id
                    ]
                );
            }
            // FIN - INSERTAR CADA REPARTIDOR EN ORDENES_REPARTIDORES
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";
    }

    // Crear order desde cotizacion
    public function nueva_ordenC(Request $request){
        $precio_total = 0;
        $repartidores = User::whereIn('id',$request->repartidores)->get();
        $cliente = Cliente::where('id', $request->idCliente)->first();
        $cotizacion = Cotizacion::where('id',$request->idCotizacion)->first();
        $data = json_decode($request->data, true);
        $productos = array();
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                $producto_db = Producto::where('id',$producto['id'])->first();
                $producto_db->cantidad = $producto['cantidad'];
                $precio_x_cantidad = $producto['precio_final'] * $producto_db->cantidad;
                $precio_total = $precio_total + $precio_x_cantidad;
                array_push($productos,$producto_db);
            }
            foreach ($productos as $producto) {
                if($producto->idCategoria !== 8 ){    
                    //Buscar la cantidad disponible de cada producto
                    $cantidad_disponible = Producto::where('id',$producto->id)->value('cantidad');
                    //Determinar la cantidad final del producto
                    $cantidad_final = $cantidad_disponible - $producto->cantidad;
                    //Modificar la cantidad del producto en la db
                    Producto::where('id',$producto->id)->update(array('cantidad' => $cantidad_final));
                }
            }

            //INICIO - INSERTAR la nueva orden en la DB
            $orden_max = DB::table('ordens')->max('num_orden');
            // $cond = 1;
            if(is_null($orden_max)){
                $num_orden = 1;
            }
            else{
                // while($cond <= $orden_max){
                //     $orden_faltante = Orden::where('num_orden',$cond)->first();
                //     if(is_null($orden_faltante)){
                //         $num_orden = $cond;
                //         break;
                //     }
                //     elseif($cond == $orden_max){
                //         $num_orden = $orden_max + 1;   
                //     }
                //     $cond++;
                // }
                $num_orden = $orden_max + 1;
            }
            $nueva_orden = Orden::create([
                'num_orden' => $num_orden,
                'idCliente' => $cliente->id,
                'idUsuario' => Auth::user()->id,
                'monto_orden' => $precio_total,
                'idOrdenEstado' => 1
            ]);
            // FIN - Insertar la orden en la DB


            $cotizacion->idCotizacionEstado = 2;
            $cotizacion->save();
            // INICIO - INSETAR CADA PRODUCTO EN ORDENES_PRODUCTOS
            $orden_max_id = DB::table('ordens')->max('id');
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                DB::table('ordenes_productos')->insert(
                    [
                    'idOrden' => $orden_max_id,
                    'idProducto' => $producto['id'],
                    'cantidad_producto' => $producto['cantidad'],
                    'precio_final' => $producto['precio_final']
                    ]
                );
            }
            // FIN - INSETAR CADA PRODUCTO EN ORDENES_PRODUCTOS

            // INICIO - INSETAR CADA REPARTIDOR EN ORDENES_REPARTIDORES
            foreach ($repartidores as $repartidor) {
                DB::table('ordenes_repartidores')->insert(
                    [
                    'idOrden' => $orden_max_id, 
                    'idRepartidor' => $repartidor->id
                    ]
                );
            }
            // FIN - INSERTAR CADA REPARTIDOR EN ORDENES_REPARTIDORES
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";
    }

}