<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseOrder;
use App\Proveedor;
use Validator;
use Alert;
use App\ProductoProveedor;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
