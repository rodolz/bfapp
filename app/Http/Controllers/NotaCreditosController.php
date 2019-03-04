<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Pago;
use App\Factura;
use App\NotaCredito;
use PDF;

class NotaCreditosController extends Controller
{

    public function actualizar_num_fiscal(Request $request){
        // Inicio de la transaccion
        DB::beginTransaction();
        try{
            //FORZAR EL UPDATE
            NotaCredito::where('id', $request->idNotaCredito)->update($request->except(['_method', '_token','idNotaCredito']));
        }   
        catch (\Exception $e) {
             DB::rollback();
             return $e->getMessage();
        }
        // SE hace el commit
        DB::commit();
        return "ok";

    }

    public function nota_credito_pdf($id){
        $nota_credito = NotaCredito::find($id);
        $cliente = $nota_credito->pago->cliente; 
        $factura = $nota_credito->pago->facturas()->first();  
        $pdf = PDF::loadView('nota_creditos.pdf_nota_de_credito', compact('cliente','nota_credito','factura'));
        return $pdf->stream();
    }

    public function index(Request $request)
    {
        $nota_creditos = NotaCredito::orderBy('num_nota_credito','DESC')->paginate(10);
        return view('nota_creditos.index',compact('nota_creditos'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        // Solo las facturas que no han sido pagadas ni abonadas
        $facturas = Factura::where('idFacturaEstado',1)
                    ->orderBy('num_factura','DESC')->get();
        // Crear arreglo asociativo valor - descripcion
        foreach ($facturas as $factura) {
             $facturas_fmt[$factura->id] = 'Control #'.$factura->num_factura;
         }
        return view('nota_creditos.create', compact('facturas_fmt'));//
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'idFactura' => 'required'
        ]);

        $factura = Factura::find($request->idFactura);
        $cliente = $factura->cliente;

        DB::beginTransaction();
        try{
            //INICIO - INSERTAR la nota de credito en la DB
            $nc_max = DB::table('nota_creditos')->max('num_nota_credito');
            if(is_null($nc_max)){
                $nc_num = 1;
            }
            else{
                $nc_num = $nc_max + 1;
            }
            $nota_credito = NotaCredito::create([
                'num_nota_credito' => $nc_num
            ]);
            // FIN - Insertar la nota de credito en la DB

            //Crear el pago para la factura
            $pago = Pago::create([
                'idTipoPago' => 5,
                'idCliente' => $cliente->id,
                'banco' => 'N/A',
                'numero_referencia' => 'N.C #'.$nota_credito->num_nota_credito,
                'monto_pago' => $factura->monto_factura,
                'descripcion' => 'Pago automatizado por Nota de Credito #'.$nota_credito->num_nota_credito
            ]);

            //actualizar la nota credito con el pago creado
            $nota_credito->idPago = $pago->id;
            $nota_credito->save();

            //Actualizar la factura acreditada y crear el registro en el pivot table
            
            $factura->update(['idFacturaEstado' => 4]);
            $factura->pagos()->attach($pago->id, ['monto_pago' => $factura->monto_factura]);

        } catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }

        DB::commit();
        return redirect()->route('nota_creditos.index')
                        ->with('success','Nota de Credito Creada!');
    }

    public function show($id)
    {
        $factura = Factura::find($id);
        return view('facturas.show',compact('factura'));
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        try{
            //Buscar la nota_credito
            $nota_credito = NotaCredito::find($id);
            $pago = $nota_credito->pago;
            //Borrar la factura

            $factura = $pago->facturas()->first();
            $factura->update(['idFacturaEstado' => 1]);

            $nota_credito->delete();
            $pago->delete();
        } catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }

        DB::commit();
        return redirect()->route('nota_creditos.index')
                        ->with('success','Nota de Credito Borrada!');
    }
}