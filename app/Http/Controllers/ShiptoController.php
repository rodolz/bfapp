<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipto;
use Alert;

class ShiptoController extends Controller
{
    public function index(Request $request){
        $shiptos = Shipto::orderBy('id','DESC')->paginate(10);

        return view('shipto.index',compact('shiptos'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('shipto.create');//
    }

    public function store(Request $request)
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

        Shipto::create($request->all());
        Alert::success('Direccion Shipto "'.$request->nombre_shipto.'" Agregada!')->autoclose(1000);
        return redirect()->route('shipto.index');
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
        Alert::success('Direccion Shipto modificada')->autoclose(1000);
        return redirect()->route('shipto.index');
    }

    public function edit(Request $request, $id)
    {
        $shipto = Shipto::find($id);
        return view('shipto.edit',compact('shipto'));
    }

}
