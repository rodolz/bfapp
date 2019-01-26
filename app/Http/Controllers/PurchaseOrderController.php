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
use App\Producto;
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
                'idPOStatus' => 0,
                'comments' => $request->comments,
                'tax' => $request->tax,
                'po_subtotal' => $subtotal,
                'po_total_amount' => $subtotal+($subtotal*($request->tax/100))
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
        $po = PurchaseOrder::findorFail($id)->delete();
        return redirect()->route('purchase_orders.index')
                        ->with('success', 'PO Borrada!');
    }

    public function select_proveedor(){
        $proveedores = Proveedor::all()->pluck('name','id');
        return view('purchase_orders.select_proveedor', compact('proveedores'));
    }

    public function load($id){
        $po = PurchaseOrder::findorfail($id);
        if($po->idPOStatus == 1){
            return redirect()->back()->with('errors', 'Profile updated!');
        }
        $po_productos = $po->po_pp;
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            foreach($po->po_pp as $po_producto){
                echo "ID del Producto: ".$po_producto->id." | Cantidad: ".$po_producto->pivot->cantidad_producto."<br>";
                $producto_del_inventario = $po_producto->producto;
                $producto_del_inventario->cantidad = $producto_del_inventario->cantidad + $po_producto->pivot->cantidad_producto;
                $producto_del_inventario->save();
                echo "ID del Producto: ".$producto_del_inventario->id." | Cantidad: ".$producto_del_inventario->cantidad."<br>";
            }
            $po->idPOStatus = 1;
            $po->save();
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return redirect()->back()->with('success', 'PO Cargado al inventario');
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
        Fpdf::SetAutoPageBreak(0);
        Fpdf::Image("images/banner.jpg",null,null,190,50);
        Fpdf::Ln(5);
        Fpdf::SetFont('Arial', 'B', 15);
        Fpdf::Cell(190, 10, 'PURCHASE ORDER', 0,0,'C');
        Fpdf::Ln(15);
        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::SetTextColor(255,255,255);
        Fpdf::SetFillColor(130,130,130);
        Fpdf::Cell(95, 6, "SUPPLIER",1,0,'C',1);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::Cell(95, 6, 'Date: '.$po->created_at->format('d-m-Y'),0,0,'R',0);
        Fpdf::Ln(6);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::Cell(95, 7, "Name: ".$proveedor->name,"R L T",0,'L',1);
        Fpdf::Cell(95, 7, 'PO # '.$po->po_number, 0,0,'R',0);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "Address: ".$proveedor->address,"R L",0,'L',1);
        Fpdf::Cell(95, 7, 'Approved by: Brais Sanmartin',0,0,'R',0);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "Country: ".$proveedor->country,"R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "City: ".$proveedor->city,"R L",0,'L',1);;
        Fpdf::Ln();
        Fpdf::Cell(95, 7, "Postal Code: ".$proveedor->postcode,"R L B",0,'L',1);
        Fpdf::Ln(15);

        Fpdf::SetFont('Arial', 'B', 11);
        Fpdf::SetTextColor(255,255,255);
        Fpdf::SetFillColor(130,130,130);
        Fpdf::Cell(95, 6, "BILL TO",1,0,'C',1);
        Fpdf::Cell(95, 6, "SHIP TO",1,0,'C',1);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Arial', 'B', 9);
        Fpdf::Ln(6);
        Fpdf::Cell(95, 5, "Name: BF Services S.A","R L T",0,'L',1);
        Fpdf::Cell(95, 5, "Name: BF Services S.A","R L T",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 5, "Address: Parque Industrial Costa del Este, ","R L",0,'L',1);
        Fpdf::Cell(95, 5, "Address: Parque Industrial Costa del Este, ","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 5, "Edificio IStorage, Local #1234","R L",0,'L',1);
        Fpdf::Cell(95, 5, "Edificio IStorage, Local #1234","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 5, "Country: Panama","R L",0,'L',1);
        Fpdf::Cell(95, 5, "Country: Panama","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 5, "City: Panama","R L",0,'L',1);
        Fpdf::Cell(95, 5, "City: Panama","R L",0,'L',1);
        Fpdf::Ln();
        Fpdf::Cell(95, 5, "Phone: (+507) 6371-0966 / (+507) 6330-1307","R L B",0,'L',1);
        Fpdf::Cell(95, 5, "Phone: (+507) 6371-0966 / (+507) 6330-1307","R L B",0,'L',1);
        Fpdf::Ln(10);

        Fpdf::SetFillColor(42,112,224);
        Fpdf::Cell(35, 6, "PRODUCT CODE","T L B",0,'C',1);
        Fpdf::Cell(70, 6, "DESCRIPTION","T B",0,'C',1);
        Fpdf::Cell(15, 6, "QTY","T B",0,'C',1);
        Fpdf::Cell(35, 6, "UNIT PRICE","T B",0,'C',1);
        Fpdf::Cell(35, 6, "TOTAL","T R B",0,'C',1);
        Fpdf::Ln(6);
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::SetFillColor(255,255,255);
        foreach ($po->po_pp as $producto) {
            $h = 7;
            if(strlen($producto->descripcion)>47){
                $h = 16;
            }
            Fpdf::Cell(35, $h, $producto->codigo, 1,0,'C',1);
            $x = Fpdf::GetX();
            $y = Fpdf::GetY();
            Fpdf::MultiCell(70, 7,$producto->descripcion,1);
            $H = Fpdf::GetY();
            $diff_h= $H-$y;
            $nuevo_h = $y + $diff_h;
            Fpdf::SetXY($x + 70, $y);
            // Obtener la cantidad de dicho producto de la orden
            $cantidad_producto = $producto->pivot->cantidad_producto;
            // Obtener el precio final de ordenes_productos
            $precio_final = $producto->pivot->precio_final;
            Fpdf::Cell(15, $h,$cantidad_producto, 1,0,'C',1);
            Fpdf::Cell(35, $h, '$'.number_format($precio_final,2), 1,0,'C',1);
            Fpdf::Cell(35, $h, '$'.number_format(($precio_final*$cantidad_producto),2), 1,0,'C',1);
            Fpdf::Ln(7);
            Fpdf::SetY($nuevo_h);
        }
        $y = Fpdf::GetY();
        Fpdf::SetFillColor(255,255,255);
        Fpdf::Cell(120);
        Fpdf::Cell(35, 7, 'SUBTOTAL','T',0,'L',1);
        Fpdf::Cell(35, 7, '$'.number_format($po->po_subtotal,2),1,0,'C',1);
        Fpdf::SetY($y+4);
        Fpdf::SetFillColor(130,130,130);
        Fpdf::SetTextColor(255,255,255);
        Fpdf::SetFont('Arial', 'B', 9);
        Fpdf::Cell(100, 7, 'COMMENTS OR SPECIAL INSTRUCTIONS',1,0,'C',1);
        Fpdf::ln(7);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFillColor(255,255,255);
        Fpdf::SetFont('Arial', '', 9);
        // Comments or instruccions part
        Fpdf::MultiCell(100, 7, $po->comments,1,'L',1);
        Fpdf::SetY($y);
        Fpdf::Ln(7);
        Fpdf::Cell(120);
        Fpdf::Cell(35, 7, 'TAX',0,0,'L',1);
        Fpdf::Cell(35, 7, '$'.number_format($po->tax/100*$po->po_subtotal,2),1,0,'C',1);
        // Fpdf::Ln(7);
        // Fpdf::Cell(120, 7, '',0,0,'C',0);
        // Fpdf::Cell(35, 7, 'SHIPPING',0,0,'L',1);
        // Fpdf::Cell(35, 7, '-',1,0,'C',1);
        Fpdf::Ln(7);
        Fpdf::Cell(120, 7, '',0,0,'C',0);
        Fpdf::Cell(35, 7, 'TOTAL',0,0,'L',1);
        Fpdf::SetFillColor(130,130,130);
        Fpdf::SetFont('Arial', 'B', 9.5);
        Fpdf::Cell(35, 7, '$'.number_format($po->po_total_amount,2),1,0,'C',1);
        Fpdf::SetFillColor(255,255,255);
        // Fpdf::Ln(3);
        // Fpdf::SetFont('Times', 'I', 8);
        // Fpdf::Cell(50, 9, 'Nota: los precios no incluyen ITBMS.', 0,0,'L',0);
        // FOOTER DEL PDF
        Fpdf::SetY(-15);
        Fpdf::SetFont('Arial', 'I', 9);
        //Straight Line
        Fpdf::Line(FPDF::GetX(),FPDF::GetY(),FPDF::GetX()+190,FPDF::Gety());
        Fpdf::Ln(2);
        Fpdf::Cell(70, 5, 'BF Services S.A - '. date("Y"), 0);
        //Output
        Fpdf::Output('I','PurchaseOrder_#'.$po->po_number.'.pdf',true);
    }
}
