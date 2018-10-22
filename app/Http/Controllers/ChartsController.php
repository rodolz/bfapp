<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Producto;

use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function index(){
    	return view('graficas.index');
    }

    public function getProductos(){
    	// $productos = DB::table('productos')
     //        ->select(DB::raw('MONTHNAME(updated_at) as month'), DB::raw("DATE_FORMAT(updated_at,'%Y-%m') as monthNum"), DB::raw('count(*) as productos'))
     //        ->groupBy('monthNum')
     //        ->get();
        $productos = Producto::all();
    	return $productos;
    }
}
