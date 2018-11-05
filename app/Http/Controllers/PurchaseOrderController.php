<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\PurchaseOrder;
use App\Proveedor;
use Validator;
use Alert;
use App\ProductoProveedor;
use Codedge\Fpdf\Facades\Fpdf;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchase_orders = PurchaseOrder::orderBy('id','DESC')->paginate(10);
        return view('purchase_orders.index',compact('purchase_orders'))
                ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subtotal = 0;
        $proveedor = Proveedor::where('id', $request->idProveedor)->first();
        $data = json_decode($request->data, true);
        $productos = array();
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                $producto_db = ProductoProveedor::where('id',$producto['id'])->first();
                $producto_db->cantidad = $producto['cantidad'];
                $precio_x_cantidad = $producto['precio_final'] * $producto_db->cantidad;
                $subtotal = $subtotal + $precio_x_cantidad;
                array_push($productos,$producto_db);
            }

            //INICIO - INSERTAR el PO en la DB
            $po_max = DB::table('purchase_orders')->max('po_number');
            // $cond = 1;
            if(is_null($po_max)){
                $po_num = 1;
            }
            else{
                $po_num = $po_max + 1;
            }
            $nuevo_po = PurchaseOrder::create([
                'po_number' => $po_num,
                'idProveedor' => $proveedor->id,
                'shipping_method' => "testshipping",
                'tax' => 7.3,
                'po_subtotal' => $subtotal,
                'po_total_amount' => $subtotal+($subtotal*7.3)
            ]);
            // FIN - Insertar el PO en la DB

            // INICIO - INSETAR CADA PRODUCTO EN purchaseorders_productos
            foreach ($data as $producto) {
                $producto['precio_final'] = str_replace(',', '', $producto['precio_final']);
                DB::table('purchaseorders_productosproveedores')->insert(
                    [
                    'idPO' => $nuevo_po->id,
                    'idProductoProveedor' => $producto['id'],
                    'cantidad_producto' => $producto['cantidad'],
                    'precio_final' => $producto['precio_final']
                    ]
                );
            }
            // FIN - INSETAR CADA PRODUCTO EN purchaseorders_productos
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function select_proveedor(){
        $proveedores = Proveedor::all()->pluck('name','id');
        return view('purchase_orders.select_proveedor', compact('proveedores'));
    }


    public function proveedor_productos(Request $request){
        $customMessages = [
            'idProveedor.required' => 'Debe escojer a un Proveedor!',
        ];

        $validator = Validator::make($request->all(), [
            'idProveedor' => 'required',
        ],$customMessages);

        if ($validator->fails()) {
            $errores = $validator->errors();
            Alert::error($errores->first('idProveedor'));
            return redirect()->back();
        }  
        
        $proveedor = Proveedor::findOrfail($request->idProveedor);

        // Se buscan las facturas por cobrar
        $productos = ProductoProveedor::where('idProveedor', '=', $proveedor->id)
                            ->pluck('codigo','id');
        return view('purchase_orders.create', compact('proveedor','productos'));
    }

    //MOSTRAR EL PDF
    public function po_pdf($idPO){
        $po = PurchaseOrder::find($idPO);
        $proveedor = Proveedor::find($po->idProveedor);

        //formatear el nombre del cliente, direccion en caracteres de castellano
        // $converted_contacto = utf8_decode($proveedor->contacto);
        // $converted_cliente = utf8_decode($proveedor->empresa);
        // $converted_direccion = utf8_decode($proveedor->direccion);

        Fpdf::AddPage();
        Fpdf::Image("images/banner.jpg",null,null,190,50);
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::Cell(40, 10, 'Date: '.$po->created_at->format('d-m-Y'), 0);
        Fpdf::Cell(100, 10, '', 0);
        Fpdf::Cell(50, 10, 'PO #'.$po->po_number, 0);
        Fpdf::Ln(12);
        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFillColor(170,170,170);
        // ICONOS
        // $icon = "images/iconos/empresa.png";
        // $icon2 = "images/iconos/direccion.png";
        // $icon3 = "images/iconos/correo.png";
        // $icon4 = "images/iconos/tlf.png";
        // $icon5 = "images/iconos/contacto2.png";
        // $icon6 = "images/iconos/camion.png";
        // $icon7 = "images/iconos/maletin.png";


        Fpdf::Cell(95, 7, "Supplier","T L B",0,'C',1);
        Fpdf::Cell(95, 7, "Ship to","R T B",0,'C',1);
        Fpdf::Ln(7);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetFont('Arial', 'B', 9.5);
        Fpdf::Cell(95, 7, "Name: ".$proveedor->name,"R L T",0,'L',1);
        Fpdf::Cell(95, 7, "Name: BF Services S.A","R L T",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "Address: ".$proveedor->address,"R L",0,'L',1);
        Fpdf::Cell(95, 7, "Address: Parque Industrial Costa del Este, ","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "","R L",0,'L',1);
        Fpdf::Cell(95, 7, "Edificio IStorage, Local #1234","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "Country: ".$proveedor->country,"R L",0,'L',1);
        Fpdf::Cell(95, 7, "Country: Panama","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "City: ".$proveedor->city,"R L",0,'L',1);
        Fpdf::Cell(95, 7, "City: Panama","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "PostCode: ".$proveedor->postcode,"R L B",0,'L',1);
        Fpdf::Cell(95, 7, "Phone: (+507) 6371-0966 / (+507) 6964-7914","R L B",0,'L',1);

        Fpdf::Ln(15);

        Fpdf::SetFillColor(170,170,170);
        Fpdf::Cell(50, 7, "REQUESTED BY",1,0,'C',1);
        Fpdf::Cell(50, 7, "APPROVED BY",1,0,'C',1);
        Fpdf::Cell(50, 7, "SHIPPED METHOD",1,0,'C',1);
        Fpdf::Ln(7);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetFont('Arial', 'B', 9.5);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(50, 10, $converted_cliente,0,0,'L',1);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon2,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(120, 10, $converted_direccion,0,0,'L',1);
        // Fpdf::Ln(12);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon3,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(50, 10,$proveedor->email,0,0,'L',1);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon4,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(50, 10,$proveedor->tel_local,0,0,'L',1);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon5,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(60, 10,$converted_contacto,0,0,'L',1);
        // Fpdf::Ln(12);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon6,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // // Fpdf::Cell(80, 10, ,0,0,'L',1);
        // foreach ($po->ordenes_repartidores as $repartidor) {
        //     Fpdf::Cell(35, 10,$repartidor->nombre,0,0,'L',1);
        // }
        // Fpdf::SetX(130);
        // Fpdf::Cell(10, 10,Fpdf::Image($icon7,Fpdf::GetX(), Fpdf::GetY(), 10),0,0,'L',false);
        // Fpdf::Cell(60, 10,$vendedor->nombre,0,0,'L',1);
        // Fpdf::Ln(15);
        // Fpdf::SetTextColor(0,0,0);
        // Fpdf::SetFont('Arial', 'B', 11);
        // Fpdf::Cell(190, 8, 'SE RECIBE:',0,0,'C');
        // Fpdf::Ln(13);
        // Fpdf::SetTextColor(255,255,255);
        // Fpdf::SetFillColor(66,133,244);
        // Fpdf::SetFont('Arial', 'B', 10);
        // Fpdf::Cell(20, 8, 'Codigo', 1,0,'C',1);
        // Fpdf::Cell(80, 8, 'Descripcion', 1,0,'C',1);
        // Fpdf::Cell(25, 8, 'Medidas', 1,0,'C',1);
        // Fpdf::Cell(20, 8, 'Cantidad', 1,0,'C',1);
        // Fpdf::Cell(25, 8, 'Precio-Unid', 1,0,'C',1);
        // Fpdf::Cell(20, 8, 'Totales', 1,0,'C',1);
        // Fpdf::Ln(8);
        // Fpdf::SetFont('Arial', 'B', 9);
        // Fpdf::SetFillColor(255,255,255);
        // Fpdf::SetTextColor(0,0,0);
        // foreach ($po->ordenes_productos as $producto) {
        //     $h = 8;
        //     if(strlen($producto->descripcion)>47){
        //         $h = 16;
        //     }
        //     Fpdf::Cell(20, $h, $producto->codigo, 1,0,'C',1);
        //     $x = Fpdf::GetX();
        //     $y = Fpdf::GetY();
        //     Fpdf::MultiCell(80, 8,$producto->descripcion,1);
        //     $H = Fpdf::GetY();
        //     $diff_h= $H-$y;
        //     $nuevo_h = $y + $diff_h;
        //     Fpdf::SetXY($x + 80, $y);
        //     Fpdf::Cell(25, $h,$producto->medidas, 1,0,'C',1);
        //     // Obtener la cantidad de dicho producto de la orden
        //     $cantidad_producto = $producto->pivot->cantidad_producto;
        //     // Obtener el precio final de ordenes_productos
        //     $precio_final = $producto->pivot->precio_final;
        //     $precioT = $precioT + $precio_final * $cantidad_producto;    
        //     Fpdf::Cell(20, $h,$cantidad_producto, 1,0,'C',1);
        //     Fpdf::Cell(25, $h, '$'.number_format($precio_final,2), 1,0,'C',1);
        //     Fpdf::Cell(20, $h, '$'.number_format(($precio_final*$cantidad_producto),2), 1,0,'C',1);
        //     Fpdf::Ln(8);
        //     Fpdf::SetY($nuevo_h);
        // }
        // Fpdf::Cell(170, 5, '',0,0,'C',0);
        // Fpdf::SetFillColor(189,189,189);
        // Fpdf::Cell(20, 5, '$'.number_format($precioT,2),1,0,'C',1);
        // Fpdf::Ln(3);
        // Fpdf::SetFont('Times', 'I', 8);
        // Fpdf::Cell(50, 9, 'Nota: los precios no incluyen ITBMS.', 0,0,'L',0);
        // // FOOTER DEL PDF
        // Fpdf::SetY(-30);
        // Fpdf::SetFont('Arial', '', 9);
        // Fpdf::Cell(110, 5, '', 0);
        // Fpdf::Cell(70, 5, 'Recibido por:___________________________________', 0);

        Fpdf::Output('I','Nota_de_entrega_'.$po->num_orden.'.pdf',true);
    }
}
