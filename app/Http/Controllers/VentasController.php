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
        Fpdf::Image("images/cintillo_control_old.jpg",0,0,215,45);
        // $nueva_y = Fpdf::GetY();
        Fpdf::SetY(42);
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
                $converted_nombre_categoria = utf8_decode($producto->categoria->nombre_categoria);
                Fpdf::SetFont('Times', 'B', 12);
                Fpdf::SetFillColor(154,204,119);
                Fpdf::SetTextColor(255,255,255);
                Fpdf::Cell(195, 12, $converted_nombre_categoria, 'L R',1,'L',true);
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
                Fpdf::Cell(195, 12, $converted_nombre_categoria, 'L R',1,'L',true);
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