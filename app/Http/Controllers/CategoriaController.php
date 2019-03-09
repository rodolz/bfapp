<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Categoria;
use Alert;
use DOMDocument;

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
            'nombre_categoria' => 'required'
        ]);

        if($validator->fails()){

            $errors = $validator->errors()->all();
            $errors = implode("<br />",$errors);
            SWAL::error("Hubo Errores",'',['html' => $errors]);
            return redirect()->back()->withErrors();
        }

        Categoria::create($request->all());

        Alert::success("Categoría Creada!")->autoclose(1500);
        
        return redirect()->back();
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
