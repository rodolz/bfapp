<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cotizacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Producto;
use App\User;
use PDF;
use Alert;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $cotizaciones = Cotizacion::orderBy('id','DESC')->paginate(10);
        return view('ventas.cotizaciones.index',compact('cotizaciones'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(){

        // con pluck() se crea un array asociativo con los datos de la db
        $clientes = Cliente::pluck('empresa','id');
        $productos = Producto::select(DB::raw("CONCAT(codigo,' | ',descripcion) as codigo_descripcion"),'id')
                                ->pluck('codigo_descripcion','id');

        return view('ventas.cotizaciones.create', compact('clientes','productos'));//
    }

    public function edit(Request $request, $id)
    {
        $clientes = Cliente::pluck('empresa','id');
        $productos = Producto::select(DB::raw("CONCAT(codigo,' | ',descripcion) as codigo_descripcion"),'id')
                                ->pluck('codigo_descripcion','id');
                                
        $cotizacion = Cotizacion::find($id);

        $productos_seleccionados = $cotizacion->cotizacion_producto;

        // foreach ($productos_seleccionados as $producto) {
        //     dd($producto->pivot->cantidad_producto);
        //     // dd($producto->codigo);
        // }
        return view('ventas.cotizaciones.edit',compact('cotizacion','clientes','productos','productos_seleccionados'));
    }

    public function destroy($id){

        Cotizacion::findorFail($id)->delete();
        Alert::success('Cotización Borrada')->autoclose(1000);
        return redirect()->back();
    }
    public function nueva_cotizacion(Request $request){

        $precio_total = 0;
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
                $precio_total += + $precio_x_cantidad;
                array_push($productos,$producto_db);
            }
            //Aplicar el ITBMS al precio total
            $subtotal = $precio_total;

            $precio_total = $precio_total + $precio_total * ($request->itbms / 100);

            $num_cotizacion = Cotizacion::all()->max('num_cotizacion');

            switch ($num_cotizacion) {
                case null:
                    $num_cotizacion = 1;
                    break;
                default:
                    $num_cotizacion += 1;
                    break;
            }

            //Crear nueva cotizacion
            $nueva_cotizacion = Cotizacion::create([
                'idCliente' => $cliente->id,
                'idCotizacionEstado' => 1,
                'num_cotizacion' => $num_cotizacion,
                'idUsuario' => Auth::user()->id,
                'condicion' => $request->condicion,
                't_entrega' => $request->t_entrega,
                'd_oferta' => $request->d_oferta,
                'garantia' => $request->garantia,
                'subtotal' => $subtotal,
                'monto_cotizacion' => $precio_total,
                'notas' => $request->notas,
                'itbms' => $request->itbms
            ]);

            // INICIO - INSETAR CADA PRODUCTO EN Cotizacion_producto
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                DB::table('cotizacion_producto')->insert(
                    [
                    'idCotizacion' => $nueva_cotizacion->id,
                    'idProducto' => $producto['id'],
                    'cantidad_producto' => $producto['cantidad'],
                    'precio_final' => $producto['precio_final']
                    ]
                );
            }
        }   


        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";
    }

    public function update_cotizacion(Request $request)
    {
        $cotizacion = Cotizacion::findorFail($request->idCotizacion);

        // update de cotizacion
        $precio_total = 0;
        $cliente = Cliente::where('id', $request->idCliente)->first();
        $data = json_decode($request->data, true);
        $productos = array();
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            $cotizacion->cotizacion_producto()->detach();
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                $producto_db = Producto::where('id',$producto['id'])->first();
                $producto_db->cantidad = $producto['cantidad'];
                $precio_x_cantidad = $producto['precio_final'] * $producto_db->cantidad;
                $precio_total += + $precio_x_cantidad;
                array_push($productos,$producto_db);
            }
            
            //Aplicar el ITBMS al precio total
            $subtotal = $precio_total;
            $precio_total = $precio_total + $precio_total * ($request->itbms / 100);
            //Modificar la cotizacion
            $cotizacion->idCliente = $cliente->id;
            $cotizacion->idUsuario = Auth::user()->id;
            $cotizacion->condicion = $request->condicion;
            $cotizacion->t_entrega = $request->t_entrega;
            $cotizacion->d_oferta = $request->d_oferta;
            $cotizacion->garantia = $request->garantia;
            $cotizacion->subtotal = $subtotal;
            $cotizacion->monto_cotizacion = $precio_total;
            $cotizacion->notas = $request->notas;
            $cotizacion->itbms = $request->itbms;
            $cotizacion->save();

            // INICIO - INSETAR CADA PRODUCTO EN Cotizacion_producto
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                DB::table('cotizacion_producto')->insert(
                    [
                    'idCotizacion' => $cotizacion->id,
                    'idProducto' => $producto['id'],
                    'cantidad_producto' => $producto['cantidad'],
                    'precio_final' => $producto['precio_final']
                    ]
                );
            }
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

    public function cotizacion_pdf($id){

        $cotizacion = Cotizacion::find($id);
        $vendedor = User::find($cotizacion->idUsuario);  
        $pdf = PDF::loadView('ventas.cotizaciones.pdf_cotizacion', compact('cotizacion','vendedor'));
        return $pdf->stream();
    }
}