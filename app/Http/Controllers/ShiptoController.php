<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Shipto;

class ShiptoController extends Controller
{
    public function index(Request $request){
        $shiptos = Shipto::orderBy('id','DESC')->paginate(10);

        return view('shipto.index',compact('shiptos'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
        return view('shipto.index',compact('shiptos'));
    }

    public function create()
    {
        return view('shipto.create');//
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_shipto' => 'required',
            'name' => 'required',
            'address_line1'=> 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'phone' => 'required'
        ]);

        if($validator->fails()){
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }


        Shipto::create($request->all());
        return redirect()->route('shipto.index')
                        ->with('success','Direccion Shipto "'.$request->nombre_shipto.'" Agregada!');
    }

    public function destroy($id)
    {
        Shipto::find($id)->delete();
        return redirect()->route('shipto.index')
                        ->with('success','Direccion Shipto Borrada!');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre_shipto' => 'required',
            'name' => 'required',
            'address_line1'=> 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'phone' => 'required'
        ]);

        Shipto::find($id)->update($request->all());

        return redirect()->route('shipto.index')
                        ->with('success','Direccion Shipto Modificada!');
    }

    public function edit(Request $request, $id)
    {
        $shipto = Shipto::find($id);
        return view('shipto.edit',compact('shipto'));
    }

}
