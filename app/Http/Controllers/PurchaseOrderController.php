<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\PurchaseOrder;
use App\Proveedor;
use App\ProductoProveedor;
use App\Shipto;
use PDF;
use Alert;

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
                'idPOStatus' => 1,
                'idShipto' => $request->shipto,
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
        PurchaseOrder::findorFail($id)->delete();
        Alert::success('Orden de Compra Borrada')->autoclose(1000);
        return redirect()->route('purchase_orders.index');
    }

    public function select_proveedor(){
        $proveedores = Proveedor::all()->pluck('name','id');
        return view('purchase_orders.select_proveedor', compact('proveedores'));
    }

    public function load($id){
        $po = PurchaseOrder::findorfail($id);
        if($po->idPOStatus == 2){
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
            $po->idPOStatus = 2;
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

        $this->validate($request, [
            'idProveedor' => 'required',
        ],
        [
            'idProveedor.required' => 'Debe escojer a un Proveedor',
        ]);
        
        $proveedor = Proveedor::findOrfail($request->idProveedor);
        // Se buscan las direccione Shipto
        $shipto = Shipto::pluck('nombre_shipto','id');
        // Se buscan los productos del proveedor seleccionado
        $productos = ProductoProveedor::select(DB::raw("CONCAT(codigo,' | ',descripcion) as codigo_descripcion"),'id')
                            ->where('idProveedor', '=', $proveedor->id)
                            ->pluck('codigo_descripcion','id');

        return view('purchase_orders.create', compact('proveedor','productos','shipto'));
    }

    public function po_pdf($id){
        $po = PurchaseOrder::find($id);
        $proveedor = Proveedor::find($po->idProveedor);
        $pdf = PDF::loadView('purchase_orders.pdf_po', compact('po','proveedor'));
        return $pdf->stream();
    }

}
