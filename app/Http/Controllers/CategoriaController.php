<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use Alert;

class CategoriaController extends Controller
{
    public function index(Request $request){
        $categorias = Categoria::orderBy('id','DESC')->paginate(10);

        return view('categorias.index',compact('categorias'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('categorias.create');//
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre_categoria' => 'required'
        ]);

        Categoria::create($request->all());

        Alert::success("Categoría Creada")->autoclose(1000);
        
        return redirect()->route('categorias.index');
    }

    public function destroy($id)
    {
        Categoria::find($id)->delete();

        Alert::success("Categoría Borrada")->autoclose(1000);
        
        return redirect()->back();
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
