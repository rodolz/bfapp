<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cotizacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Producto;
use App\User;
use Codedge\Fpdf\Facades\Fpdf;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $cotizaciones = Cotizacion::orderBy('id','DESC')->paginate(10);
        return view('ventas.cotizaciones.index',compact('cotizaciones'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

        // $ordenes = Orden::all();
        // return view('ordenes.index')->with('ordenes', $ordenes);
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
                                ->where('cantidad','>','0')
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

        $cotizacion = Cotizacion::findorFail($id)->delete();
        return redirect()->route('ventas.cotizaciones.index')
                        ->with('success', 'Cotizacion Borrada!');
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
                'num_cotizacion' => $num_cotizacion,
                'idUsuario' => Auth::user()->id,
                'condicion' => $request->condicion,
                't_entrega' => $request->t_entrega,
                'd_oferta' => $request->d_oferta,
                'garantia' => $request->garantia,
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
            $precio_total = $precio_total + $precio_total * ($request->itbms / 100);

            $cotizacion->idCliente = $cliente->id;
            $cotizacion->idUsuario = Auth::user()->id;
            $cotizacion->monto_cotizacion = $precio_total;
            // $orden->idOrdenEstado = 1;
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
    //MOSTRAR EL PDF DE LA COTIZACION
    public function nueva_cotizacion_pdf($idCotizacion){

        $subtotal = 0;
        $cotizacion = Cotizacion::find($idCotizacion);
        $cliente = Cliente::find($cotizacion->idCliente);
        $vendedor = User::find($cotizacion->idUsuario);
        Fpdf::SetTopMargin(1);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::AddPage();
        //BANNER
        Fpdf::Image("images/cintillo_control.jpg",null,null,210,50);

        //formatear el nombre del cliente, direccion en caracteres de castellano
        $converted_contacto = utf8_decode($cliente->contacto);
        $converted_cliente = utf8_decode($cliente->empresa);
        $converted_direccion = utf8_decode($cliente->direccion);

        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::Cell(40, 10, 'Fecha: '.$cotizacion->created_at->format('d-m-Y'), 0);
        Fpdf::Cell(100, 10, '', 0);
        Fpdf::Cell(50, 10, utf8_decode('COTIZACIÓN #').$cotizacion->num_cotizacion, 0);
        Fpdf::Ln(12);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::SetTextColor(0,0,0);


        Fpdf::Cell(17, 9,'Empresa:',0,0,'L');
        Fpdf::Cell(50, 9, $converted_cliente,0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(19, 9, utf8_decode('Ubicación:'),0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80, 9, $converted_direccion,0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(37, 9,'Persona de contacto:',0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(25, 9, $converted_contacto,0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        $converted_telefono = utf8_decode('Teléfono:');
        Fpdf::Cell(17, 9,$converted_telefono,0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(30, 9, $cliente->tel_local,0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(12, 9,'Email:',0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(45, 9, $cliente->email,0,0,'L');
        Fpdf::Ln(7);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190, 8, 'Estimado Sr:',0,0,'C');
        Fpdf::Ln(5);
        Fpdf::Cell(190, 8, 'Por medio de la presente tenemos el agrado de cotizarle el siguiente material:',0,0,'C');
        Fpdf::Ln(8);
        Fpdf::SetFont('Arial', 'U', 10);
        Fpdf::Cell(30, 8, 'Codigo', 0,0,'C',1);
        Fpdf::Cell(80, 8, utf8_decode('Descripción'), 0,0,'C',1);
        Fpdf::Cell(25, 8, 'Medidas', 0,0,'C',1);
        Fpdf::Cell(20, 8, 'Cantidad', 0,0,'C',1);
        Fpdf::Cell(20, 8, 'Precio-Unid', 0,0,'C',1);
        Fpdf::Cell(20, 8, 'Totales', 0,0,'C',1);
        Fpdf::Ln(8);
        Fpdf::SetFont('Arial', 'B', 9);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        foreach ($cotizacion->cotizacion_producto as $producto) {
            $h = 8;
            if(strlen($producto->descripcion)>48){
                $h = 16;
            }
            Fpdf::Cell(30, $h, $producto->codigo, 1,0,'C',1);
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
            //Calcular el sub-total
            $subtotal = $subtotal + $cantidad_producto * $precio_final;
            Fpdf::Cell(20, $h,$cantidad_producto, 1,0,'C',1);
            Fpdf::Cell(20, $h, '$'.number_format($precio_final,2), 1,0,'C',1);
            Fpdf::Cell(20, $h, '$'.number_format(($cantidad_producto*$precio_final),2), 1,0,'C',1);
            Fpdf::Ln(8);
            Fpdf::SetY($nuevo_h);
        }
        Fpdf::Cell(175, 5, 'SUB-TOTAL',0,0,'R',0);
        Fpdf::Cell(20, 5, '$'.number_format($subtotal,2),1,0,'C',1);
        Fpdf::Ln(5);
        Fpdf::Cell(175, 5, 'ITBMS 7%',0,0,'R',0);
        Fpdf::Cell(20, 5, '$'.number_format(($subtotal * $cotizacion->itbms/100),2),1,0,'C',1);
        Fpdf::Ln(5);
        Fpdf::SetFillColor(189,189,189);
        Fpdf::Cell(175, 5, 'TOTAL',0,0,'R',0);
        Fpdf::Cell(20, 5, '$'.number_format($cotizacion->monto_cotizacion,2),1,0,'C',1);
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(34, 9,utf8_decode('Condición de Pago:'),0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(160, 9, utf8_decode($cotizacion->condicion),0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(34, 9,'Tiempo de entrega:',0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(15, 9, utf8_decode($cotizacion->t_entrega),0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(37, 9,utf8_decode('Duración de la oferta:'),0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(25, 9,utf8_decode($cotizacion->d_oferta),0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(18, 9,utf8_decode('Garantía:'),0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(160, 9, utf8_decode($cotizacion->garantia) ,0,0,'L');
        Fpdf::Ln(7);
        Fpdf::MultiCell(195, 5, 'Nota: '.utf8_decode($cotizacion->notas), 0,'L');
        // FOOTER DEL PDF
        Fpdf::SetY(-45);
        Fpdf::SetX(10);
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(50, 12, 'Cliente Acepta', 0,0,'C',0);
        Fpdf::SetX(145);
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(50, 12, utf8_decode($vendedor->nombre), 0,0,'C',0);
        Fpdf::Ln(5);
        Fpdf::SetX(145);
        Fpdf::SetFont('Arial', '', 8);
        foreach ($vendedor->roles as $role) {
            $nombre_rol = $role->display_name;
        }
        Fpdf::Cell(50, 12, $nombre_rol, 0,0,'C',0);
        Fpdf::Ln(30);
        Fpdf::Cell(60, 8, 'Parque industrial de Costa del Este, Edif. Istorage', 'T',0,'L',0);
        FPDF::Cell(90,8, '','T',0,'L',0);
        Fpdf::Cell(45, 8, 'Email: sgpreprensa@gmail.com', 'T',0,'L',0);
        Fpdf::Ln(4);
        Fpdf::Cell(75, 8, utf8_decode('Ciudad de Panamá / Panamá. Teléfono: (+507) 6371-0966'), 0,0,'L',0);

        //Se debe convertir lo que retorna el output en un string base64 para poder mostrarlo con ajax
        // $pdfString = Fpdf::Output('', 'S', true);
        // $pdfBase64 = base64_encode($pdfString);
        // echo 'data:application/pdf;base64,' . $pdfBase64;
        Fpdf::Output('I','Cotizacion_'.$cotizacion->num_cotizacion.'-'.$cotizacion->created_at->format('dmY').'.pdf',true);
    }
}