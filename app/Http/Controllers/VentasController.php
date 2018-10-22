<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Cliente;
use App\Producto;
use App\Categoria;
use Codedge\Fpdf\Facades\Fpdf;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Barryvdh\DomPDF\Facade as PDF;

class VentasController extends Controller
{


    //MOSTRAR EL PDF DEL PRESUPUESTO
    public function nueva_cotizacion(Request $request){

    Date::setLocale('es');
    $fecha_esp = Date::now()->format('d-F-Y'); // domingo 28 abril 2013 21:58:16

        $precio_total = 0;
        $cliente = Cliente::where('id', $request->idCliente)->first();
        $data = json_decode($request->data, true);
        $productos = array();

            foreach (array_shift($data) as $key => $value) {
                    $producto = Producto::where('id',$key)->first();
                    $producto->cantidad = $value;
                    $precio_x_cantidad = $producto->precio * $producto->cantidad;
                    $precio_total = $precio_total + $precio_x_cantidad;
                    array_push($productos,$producto);
            }
        $vendedor = User::find(1);
        Fpdf::SetTopMargin(1);
        Fpdf::SetAutoPageBreak(true, 0.5);
        Fpdf::AddPage();
        //BANNER
        Fpdf::Image("images/cintillo_control.jpg",null,null,205,50);

        //formatear el nombre del cliente, direccion en caracteres de castellano
        $converted_contacto = utf8_decode($cliente->contacto);
        $converted_cliente = utf8_decode($cliente->empresa);
        $converted_direccion = utf8_decode($cliente->direccion);

        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::Cell(40, 10, 'Fecha: '.$fecha_esp, 0);
        Fpdf::Cell(100, 10, '', 0);
        Fpdf::Cell(50, 10, utf8_decode('COTIZACIÓN'), 0);
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
        Fpdf::Ln(5);
        Fpdf::Ln(15);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190, 8, 'Estimado Sr:',0,0,'C');
        Fpdf::Ln(5);
        Fpdf::Cell(190, 8, 'Por medio de la presente tenemos el agrado de cotizarle el siguiente material:',0,0,'C');
        Fpdf::Ln(8);
        Fpdf::SetFont('Arial', 'U', 10);
        Fpdf::Cell(20, 8, 'Codigo', 0,0,'C',1);
        Fpdf::Cell(80, 8, 'Descripcion', 0,0,'C',1);
        Fpdf::Cell(25, 8, 'Medidas', 0,0,'C',1);
        Fpdf::Cell(20, 8, 'Cantidad', 0,0,'C',1);
        Fpdf::Cell(25, 8, 'Precio-Unid', 0,0,'C',1);
        Fpdf::Cell(20, 8, 'Totales', 0,0,'C',1);
        Fpdf::Ln(8);
        Fpdf::SetFont('Arial', 'B', 9);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        foreach ($productos as $producto) {
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
            Fpdf::Cell(20, $h,$producto->cantidad, 1,0,'C',1);
            Fpdf::Cell(25, $h, '$'.number_format($producto->precio,2), 1,0,'C',1);
            Fpdf::Cell(20, $h, '$'.number_format(($producto->precio*$producto->cantidad),2), 1,0,'C',1);
            Fpdf::Ln(8);
            Fpdf::SetY($nuevo_h);
        }
        Fpdf::Cell(170, 5, '',0,0,'C',0);
        Fpdf::SetFillColor(189,189,189);
        Fpdf::Cell(20, 5, '$'.number_format($precio_total,2),1,0,'C',1);
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(34, 9,utf8_decode('Condición de Pago:'),0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(160, 9,'Orden de compra para el despacho del material y plazo de 30 dias para el pago de la factura.' ,0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(34, 9,'Tiempo de entrega:',0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(15, 9, 'Inmediato.',0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(37, 9,utf8_decode('Duración de la oferta:'),0,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(25, 9,utf8_decode('3 días'),0,0,'L');
        Fpdf::Ln(5);
        Fpdf::SetFont('Times', 'I', 8);
        Fpdf::Cell(50, 9, 'Nota: los precios no incluyen ITBMS.', 0,0,'L',0);
        // FOOTER DEL PDF
        Fpdf::SetY(-85);
        Fpdf::SetX(10);
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(50, 12, 'Cliente Acepta', 0,0,'C',0);
        Fpdf::SetX(145);
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(50, 12, Auth::user()->nombre, 0,0,'C',0);
        Fpdf::Ln(5);
        Fpdf::SetX(145);
        Fpdf::SetFont('Arial', '', 8);
        foreach (Auth::user()->roles as $role) {
            $nombre_rol = $role->display_name;
        }
        Fpdf::Cell(50, 12, $nombre_rol, 0,0,'C',0);
        Fpdf::Ln(55);
        Fpdf::Cell(60, 8, 'Via Espana, PH Las Hortencias, Piso 10, 10A', 'T',0,'L',0);
        FPDF::Cell(90,8, '','T',0,'L',0);
        Fpdf::Cell(45, 8, 'Email: sgpreprensa@gmail.com', 'T',0,'L',0);
        Fpdf::Ln(4);
        Fpdf::Cell(75, 8, 'Ciudad de Panama / Panama. '.$converted_telefono.': (+507) 6371-0966', 0,0,'L',0);

        //Se debe convertir lo que retorna el output en un string base64 para poder mostrarlo con ajax
        $pdfString = Fpdf::Output('', 'S', true);
        $pdfBase64 = base64_encode($pdfString);
        echo 'data:application/pdf;base64,' . $pdfBase64;
    }

    public function lista_precios(Request $request)
    {
        $productos = Producto::where('cantidad','>','0')
                                ->orderBy('idCategoria','desc')
                                ->get();
        // Preparar las categorias de solo los productos disponibles
        foreach ($productos as $producto) {
            $categorias[$producto->categoria->id] = $producto->categoria->nombre_categoria;
        }
        return view('ventas.lista_precios',compact('productos','categorias'));
    }

    public function lista_precios_pdf(Request $request) {

        if(!isset($request->categorias)){
            return redirect()->back()->withErrors('Debe seleccionar al menos una categoria, intente de nuevo');
        }

        $productos_disponibles = Producto::where('cantidad','>','0')
                                ->whereIn('idCategoria',$request->categorias)
                                ->orderBy('idCategoria','desc')
                                ->get();
        $categoria = '';
        Fpdf::SetTopMargin(5);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::AddPage();
        Fpdf::Image("images/cintillo_control.jpg",null,null,195,50);
        Fpdf::SetFont('Times', 'B', 15);

        Fpdf::Cell(35, 15, 'Lista de Precios', 0,0,'L',false);
        Fpdf::Ln(18);
        Fpdf::SetFont('Times', 'B', 12);
        Fpdf::Cell(35, 12, 'Codigo', 'T L',0,'L',false);
        Fpdf::Cell(100, 12, 'Descripcion', 'T',0,'L',false);
        Fpdf::Cell(30, 12, 'Medidas', 'T',0,'L',false);
        Fpdf::Cell(30, 12, 'Precio Unitario', 'T R',0,'L',false);
        Fpdf::Ln(12);
        // Fpdf::SetFillColor(255,255,255);
        // Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Times', '', 12);
        $cont = 0;
        foreach ($productos_disponibles as $producto) {
            if($cont == 15){
                Fpdf::Cell(195, 12, 'Continuar en la siguiente pagina' ,'T',0,'L',false);
                Fpdf::AddPage();
                Fpdf::SetFont('Times', 'B', 15);
                Fpdf::Cell(35, 15, 'Lista de Precios (Continuacion)', 0,0,'L',false);
                Fpdf::Ln(18);  
                Fpdf::SetFont('Times', 'B', 12);
                Fpdf::Cell(35, 12, 'Codigo', 'T L',0,'L',false);
                Fpdf::Cell(100, 12, 'Descripcion', 'T',0,'L',false);
                Fpdf::Cell(30, 12, 'Medidas', 'T',0,'L',false);
                Fpdf::Cell(30, 12, 'Precio Unitario', 'T R',0,'L',false);
                Fpdf::SetFont('Times', '', 12);
                Fpdf::Ln(12);
                $cont = 0;
            }
            if($producto->categoria->nombre_categoria != $categoria){
                Fpdf::SetFont('Times', 'B', 12);
                Fpdf::SetFillColor(154,204,119);
                Fpdf::SetTextColor(255,255,255);
                Fpdf::Cell(195, 12, $producto->categoria->nombre_categoria, 'L R',1,'L',true);
                Fpdf::SetFont('Times', '', 12);
                Fpdf::SetTextColor(0,0,0);
                $categoria = $producto->categoria->nombre_categoria;
                $cont++;
            }
            // Para repetir el nombre de la categoria en la nueva pagina
            if($cont === 0){
                Fpdf::SetFont('Times', 'B', 12);
                Fpdf::SetFillColor(154,204,119);
                Fpdf::SetTextColor(255,255,255);
                Fpdf::Cell(195, 12, $producto->categoria->nombre_categoria, 'L R',1,'L',true);
                Fpdf::SetFont('Times', '', 12);
                Fpdf::SetTextColor(0,0,0);
                $categoria = $producto->categoria->nombre_categoria;
                $cont++;        
            }
            Fpdf::Cell(35, 12, $producto->codigo, 'L',0,'L',false);
            Fpdf::Cell(100, 12,$producto->descripcion, 0,0,'L',false);
            Fpdf::Cell(30, 12,$producto->medidas, 0,0,'L',false);   
            Fpdf::Cell(30, 12, '$'.number_format($producto->precio,2), 'R',0,'L',false);
            Fpdf::Ln(12);
            $cont++;
        }
        Fpdf::Cell(195, 12, 'Para la siguiente fecha: '.date('d/m/Y'), 'T',0,'L',false);
        Fpdf::Output('I',date('d/m/Y').' Lista de Precios.pdf');
    }
}