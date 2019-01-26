<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Orden;
use App\User;
use App\Role;
use App\Cliente;
use App\Producto;
use App\Cotizacion;
use Codedge\Fpdf\Facades\Fpdf;

class OrdenesController extends Controller
{
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'codigo' => 'required',
            'idCategoria' => 'required',
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'cantidad' => 'required',
        ]);

        Orden::create($request->all());
        return redirect()->route('ordenes.index')
                        ->with('success','Orden Agregada!');
    }

    public function show($id)
    {
        $orden = Orden::find($id);
        return view('ordenes.show',compact('orden'));
    }

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

    public function update_orden(Request $request)
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

        Orden::find($id)->update($request->all());
        return redirect()->route('ordenes.index')
                        ->with('success','Orden Modificada!');
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
        return redirect()->route('ordenes.index')
                        ->with('success', 'Orden Borrada!');
    }

    //MOSTRAR EL PDF
    public function pdf($idOrden){
        $orden = Orden::find($idOrden);
        $cliente = Cliente::find($orden->idCliente);
        $vendedor = User::find($orden->idUsuario);

        //formatear el nombre del cliente, direccion en caracteres de castellano
        $converted_contacto = utf8_decode($cliente->contacto);
        $converted_cliente = utf8_decode($cliente->empresa);
        $converted_direccion = utf8_decode($cliente->direccion);

        $precioT = 0;
        Fpdf::AddPage();
        Fpdf::Image("images/banner.jpg",null,null,190,50);
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::Cell(40, 10, 'Fecha: '.$orden->created_at->format('d-m-Y'), 0);
        Fpdf::Cell(100, 10, '', 0);
        Fpdf::Cell(50, 10, 'Nota de entrega #'.$orden->num_orden, 0);
        Fpdf::Ln(12);
        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFillColor(238,238,238);
        // ICONOS
        $icon = "images/iconos/empresa.png";
        $icon2 = "images/iconos/direccion.png";
        $icon3 = "images/iconos/correo.png";
        $icon4 = "images/iconos/tlf.png";
        $icon5 = "images/iconos/contacto2.png";
        $icon6 = "images/iconos/camion.png";
        $icon7 = "images/iconos/maletin.png";

        Fpdf::Cell(10, 10,Fpdf::Image($icon,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        Fpdf::Cell(50, 10, $converted_cliente,0,0,'L',1);
        Fpdf::Cell(10, 10,Fpdf::Image($icon2,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        Fpdf::Cell(120, 10, $converted_direccion,0,0,'L',1);
        Fpdf::Ln(12);
        Fpdf::Cell(10, 10,Fpdf::Image($icon3,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        Fpdf::Cell(50, 10,$cliente->email,0,0,'L',1);
        Fpdf::Cell(10, 10,Fpdf::Image($icon4,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        Fpdf::Cell(50, 10,$cliente->tel_local,0,0,'L',1);
        Fpdf::Cell(10, 10,Fpdf::Image($icon5,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        Fpdf::Cell(60, 10,$converted_contacto,0,0,'L',1);
        Fpdf::Ln(12);
        Fpdf::Cell(10, 10,Fpdf::Image($icon6,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(80, 10, ,0,0,'L',1);
        foreach ($orden->ordenes_repartidores as $repartidor) {
            Fpdf::Cell(35, 10,$repartidor->nombre,0,0,'L',1);
        }
        Fpdf::SetX(130);
        Fpdf::Cell(10, 10,Fpdf::Image($icon7,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        Fpdf::Cell(60, 10,$vendedor->nombre,0,0,'L',1);
        Fpdf::Ln(15);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::Cell(190, 8, 'SE RECIBE:',0,0,'C');
        Fpdf::Ln(13);
        Fpdf::SetTextColor(255,255,255);
        Fpdf::SetFillColor(66,133,244);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(20, 8, 'Codigo', 1,0,'C',1);
        Fpdf::Cell(80, 8, 'Descripcion', 1,0,'C',1);
        Fpdf::Cell(25, 8, 'Medidas', 1,0,'C',1);
        Fpdf::Cell(20, 8, 'Cantidad', 1,0,'C',1);
        Fpdf::Cell(25, 8, 'Precio-Unid', 1,0,'C',1);
        Fpdf::Cell(20, 8, 'Totales', 1,0,'C',1);
        Fpdf::Ln(8);
        Fpdf::SetFont('Arial', 'B', 9);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        foreach ($orden->ordenes_productos as $producto) {
            $h = 8;
            if(strlen($producto->descripcion)>47){
                $h = 16;
            }
        
            Fpdf::Cell(20, $h, $producto->codigo, 1,0,'C',1);
            $x = Fpdf::GetX();
            $y = Fpdf::GetY();
            Fpdf::MultiCell(80, 8,$producto->descripcion,1);
            $H = Fpdf::GetY();
            $diff_h= $H-$y;
            $nuevo_h = $y + $diff_h;
            Fpdf::SetXY($x + 80, $y);
            Fpdf::Cell(25, $h,$producto->medidas, 1,0,'C',1);
            // Obtener la cantidad de dicho producto de la orden
            $cantidad_producto = $producto->pivot->cantidad_producto;
            // Obtener el precio final de ordenes_productos
            $precio_final = $producto->pivot->precio_final;
            $precioT = $precioT + $precio_final * $cantidad_producto;    
            Fpdf::Cell(20, $h,$cantidad_producto, 1,0,'C',1);
            Fpdf::Cell(25, $h, '$'.number_format($precio_final,2), 1,0,'C',1);
            Fpdf::Cell(20, $h, '$'.number_format(($precio_final*$cantidad_producto),2), 1,0,'C',1);
            Fpdf::Ln(8);
            Fpdf::SetY($nuevo_h);
        }
        Fpdf::Cell(170, 5, '',0,0,'C',0);
        Fpdf::SetFillColor(189,189,189);
        Fpdf::Cell(20, 5, '$'.number_format($precioT,2),1,0,'C',1);
        Fpdf::Ln(3);
        Fpdf::SetFont('Times', 'I', 8);
        Fpdf::Cell(50, 9, 'Nota: los precios no incluyen ITBMS.', 0,0,'L',0);
        // FOOTER DEL PDF
        Fpdf::SetY(-30);
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(110, 5, '', 0);
        Fpdf::Cell(70, 5, 'Recibido por:___________________________________', 0);

        Fpdf::Output('I','Nota_de_entrega_'.$orden->num_orden.'.pdf',true);
    }



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

    public function index(Request $request)
    {
        $ordenes = Orden::orderBy('num_orden','DESC')->paginate(10);
        return view('ordenes.index',compact('ordenes','repartidores'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

        // $ordenes = Orden::all();
        // return view('ordenes.index')->with('ordenes', $ordenes);
    }

}