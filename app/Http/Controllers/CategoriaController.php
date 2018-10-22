<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller
{
    public function index(Request $request){
        $categorias = Categoria::orderBy('id','DESC')->paginate(10);

        return view('categorias.index',compact('categorias'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
        return view('categorias.index',compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');//
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria' => 'required',
        ]);

        if($validator->fails()){
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }


        Categoria::create($request->all());
        return redirect()->route('categorias.index')
                        ->with('success','Categoria '.$request->nombre_categoria.' Agregada!');
    }

    public function destroy($id)
    {
        Categoria::find($id)->delete();
        return redirect()->route('categorias.index')
                        ->with('success','Categoria Borrada!');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre_categoria' => 'required',
        ]);

        Categoria::find($id)->update($request->all());

        return redirect()->route('categorias.index')
                        ->with('success','Categoria Modificada!');
    }

    public function edit(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        return view('categorias.edit',compact('categoria'));
    }

}
