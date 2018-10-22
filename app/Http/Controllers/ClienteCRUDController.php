<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

class ClienteCRUDController extends Controller
{


    /**
     * Instantiate a new PostController instance.
     *
     * @return void
     */

    // public function __construct()
    // {
    //     $this->middleware('permission:crear-cliente', ['only' => ['create']]);
    //     $this->middleware('permission:editar-cliente',   ['only' => ['edit']]);
    //     $this->middleware('permission:ver-cliente',   ['only' => ['show', 'index']]);
    //     $this->middleware('permission:borrar-cliente',   ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientes = Cliente::orderBy('id','DESC')->paginate(10);
        return view('clientes.index',compact('clientes'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'empresa' => 'required',
            'contacto' => 'required',
            'tel_local' => 'required',
            'tel_celular' => 'required',
            'direccion' => 'required',
            'email' => 'required|email',
            'www' => 'required',
            'ruc' => 'required',
        ]);

        Cliente::create($request->all());
        return redirect()->route('clientes.index')
                        ->with('success','Cliente Agregado!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::find($id);
        return view('clientes.show',compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        return view('clientes.edit',compact('cliente'));
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
        $this->validate($request, [
            'empresa' => 'required',
            'contacto' => 'required',
            'tel_local' => 'required',
            'tel_celular' => 'required',
            'direccion' => 'required',
            'email' => 'required',
            'www' => 'required',
            'ruc' => 'required',
        ]);

        Cliente::find($id)->update($request->all());

        return redirect()->route('clientes.index')
                        ->with('success','Cliente Modificado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cliente::find($id)->delete();
        return redirect()->route('clientes.index')
                        ->with('success','Cliente Borrado!');
    }
}