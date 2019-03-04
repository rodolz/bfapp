<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Comision;
use App\Orden;
use App\User;
use App\Cliente;
use Codedge\Fpdf\Facades\Fpdf;

class ComisionesController extends Controller
{


    //MOSTRAR EL PDF
    public function pdf($idComision){
    	$comision = Comision::find($idComision);
    	$repartidor = User::find($comision->idRepartidor);

    	Fpdf::AddPage();
		Fpdf::SetFont('Arial','B', 12);
		Fpdf::Ln(25);
		Fpdf::SetTextColor(95,22,187);
		Fpdf::SetFillColor(255,255,255);
		Fpdf::Cell(65, 10,'Comision #'.$comision->num_comision.' - '.$repartidor->nombre,'T,B',0,'C',false);
		Fpdf::Cell(65, 10,'',0,0,'C',false);
		Fpdf::SetTextColor(0,0,0);
		Fpdf::Cell(40, 10,'Fecha  : '.$comision->created_at->format('d-m-Y'),0,0,'C',false);
		Fpdf::SetFont('Arial','', 10);
		Fpdf::Ln(20);
		Fpdf::Cell(20, 10, 'Fecha','B',0,'C',false);
		Fpdf::Cell(20, 10, 'Orden #','B',0,'C',false);
		Fpdf::Cell(45, 10, 'Cliente','B',0,'C',false);
		Fpdf::Cell(40, 10, 'Monto Total','B',0,'C',false);
		Fpdf::Cell(45, 10, '% de comision','B',0,'C',false);
		Fpdf::Cell(20, 10, 'Totales','B',0,'C',false);
		Fpdf::Ln(10);
		Fpdf::SetFont('Arial','', 9);
		foreach ($comision->ordenes as $orden) {
			Fpdf::Cell(20, 10,$orden->created_at->format('d-m-Y'),0,0,'C',false);
			Fpdf::Cell(20, 10,$orden->num_orden,0,0,'C',false);
			$cliente = Cliente::find($orden->idCliente);
			Fpdf::Cell(45, 10,$cliente->empresa,0,0,'C',false);
			Fpdf::Cell(40, 10,'$'.$orden->monto_orden,0,0,'C',false);
			Fpdf::Cell(45, 10,$orden->pivot->porcentaje,0,0,'C',false);
			$comision_individual = number_format(($orden->monto_orden*$orden->pivot->porcentaje)/100,2);
			Fpdf::Cell(20, 10,'$'.$comision_individual,0,0,'C',false);
			Fpdf::Ln(10);
		}
		Fpdf::Cell(170, 10, '','T',0,'C',false);
		Fpdf::Cell(20, 10, '$'.number_format($comision->monto_comision,2),'T',0,'C',false);
       	// FOOTER DEL PDF
        Fpdf::SetY(-30);
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(110, 5, '', 0);
        Fpdf::Cell(70, 5, 'Recibido por:___________________________________', 0);

        Fpdf::Output('D','Comision_#'.$comision->num_comision.".pdf",true);
    }



    public function comisiones_repartidor(Request $request){
        $repartidor = User::findorFail($request->idRepartidor);

        $ordenes = $repartidor->ordenes()->get();

        $ordenes_sacar = collect([]);
        foreach ($repartidor->comisiones as $comision) {
            foreach ($ordenes as $orden) {
                $results = DB::table('ordenes_comisiones')
                            ->where('idOrden', $orden->id)
                            ->where('idComision', $comision->id)
                            ->select(DB::raw("COUNT(*) as count"))
                            ->first();
                if($results->count == 1){
                    $ordenes_sacar->push($orden);
                }
            }
        }
        foreach ($ordenes_sacar as $orden_sacar) {
            foreach ($ordenes as $key => $value) {
                if ($orden_sacar->id == $value->id) {
                    $ordenes_filtradas = $ordenes->forget($key);
                }
            }
        }

        // Returning the json data to  the ajax call from the view: empty or with data
        if(empty($ordenes_filtradas)){
            return "{}";
        }else{
            echo $ordenes_filtradas->pluck('num_orden','id');
        }
    }

    public function nueva_comision(Request $request){
        $pago_total = 0;
        $repartidor = User::where('id',$request->idRepartidor)->first();
        $ordenes = $request->ordenes;

        // Inicio de la transaccion
        DB::beginTransaction();
        try{
        	//Calcular el pago total
            foreach (array_shift($ordenes) as $key => $value) {
                    $orden = Orden::where('id',$key)->first();
                    $comision = number_format(($orden->monto_orden*$value)/100,2);
                    $pago_total = number_format($pago_total + $comision,2);
            }

            //INICIO - INSERTAR la nueva comision en la DB
            $comision_max = DB::table('comisions')->max('num_comision');

            $cond = 1;
            if(is_null($comision_max)){
                $num_comision = 1;
            }
            else{
                while($cond <= $comision_max){
                    $comision_faltante = Comision::where('num_comision',$cond)->first();
                    if(is_null($comision_faltante)){
                        $num_comision = $cond;
                        break;
                    }
                    elseif($cond == $comision_max){
                        $num_comision = $comision_max + 1;    
                    }
                    $cond++;
                }
            }

            // DB::table('comisions')->insert(
            //     [
            //     'num_comision' => $num_comision,
            //     'idCliente' => $cliente->id,
            //     'idUsuario' => Auth::user()->id,
            //     'monto_comision' => $precio_total,
            //     'pdf_url' => $pdf_url
            //     ]
            // );
            $comision = new Comision();
            $comision->num_comision =  $num_comision;
            $comision->idRepartidor = $repartidor->id;
            $comision->monto_comision = $pago_total;
            $comision->save();
            // FIN - Insertar la comision en la DB

            // INICIO - INSETAR CADA PRODUCTO EN COMISIONES_ORDENES
            $comision_max_id = DB::table('comisions')->max('id');
            
            $ordenes = $request->ordenes;
            foreach (array_shift($ordenes) as $key => $value) {
                DB::table('ordenes_comisiones')->insert(
                    [
                    'idComision' => $comision_max_id,
                    'idOrden' => $key,
                    'porcentaje' => $value
                    ]
                );
            }
            // FIN - INSETAR CADA PRODUCTO EN comisionES_PRODUCTOS

            // SE hace el commit
            DB::commit();
            return "ok";
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e;
        }
        // Fin de la transaccion
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comisiones = Comision::orderBy('num_comision','DESC')->paginate(10);
        return view('comisiones.index',compact('comisiones','repartidores'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

        // $comisiones = comision::all();
        // return view('comisiones.index')->with('comisiones', $comisiones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Crear arreglo asociativo valor - descripcion
        $ordenes = Orden::orderBy('num_orden','DESC')->pluck('num_orden','id','DESC');

        // $users = User::all();
        $repartidores = User::all()->pluck('nombre','id');
        // foreach ($users as $user) {
        //     if($user->hasRole('repartidor')){
        //         $repartidores[$user->id] = $user->nombre;
        //     }
        // }

            return view('comisiones.create', compact('ordenes','repartidores'));
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
            'codigo' => 'required',
            'idCategoria' => 'required',
            'nombre_producto' => 'required',
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'cantidad' => 'required',
        ]);

        Comision::create($request->all());
        return redirect()->route('comisiones.index')
                        ->with('success','comision Agregada!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comision = Comision::find($id);
        return view('comisiones.show',compact('comision'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $comision = Comision::find($id);
        return view('comisiones.edit',compact('comision'));
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
            'codigo' => 'required',
            'nombre_producto' => 'required',
            'descripcion' => 'required',
            'medidas' => 'required',
            'precio' => 'required',
            'cantidad' => 'required',
        ]);

        Comision::find($id)->update($request->all());
        return redirect()->back()->with('success','comision Modificada!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comision::find($id)->delete();
        return redirect()->back()->with('success','comision Borrada!');
    }
}