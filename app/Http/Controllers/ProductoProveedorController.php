<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Proveedor;
use App\ProductoProveedor;

class ProductoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proveedores = Proveedor::pluck('name', 'id');
        return view('proveedores.productos_proveedores.create',compact('proveedores'));//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->data, true);
        $productos = array();
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            foreach ($data as $producto){
                //Eliminar las comas en el precio
                $producto['precio'] = str_replace(',', '', $producto['precio']);
                $producto = ProductoProveedor::create([
                    'idProveedor' => $request->idProveedor,
                    'codigo' => $producto['codigo'],
                    'descripcion' => $producto['descripcion'],
                    'medidas' => "test",
                    'precio' => $producto['precio']
                ]);
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
        $producto = ProductoProveedor::find($id);
        $proveedor = Proveedor::where('id',$producto->idProveedor)
                                ->first();
        $producto->delete();
        return redirect()->route('proveedores.show',['id' => $proveedor->id])
                        ->with('success','Producto Borrado!');
    }
}
