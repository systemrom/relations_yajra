<?php

namespace App\Http\Controllers;

use App\Models\Companias;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vendedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //Listado de las companias
        $companias = Companias::pluck('nombre', 'id');


        //Listado de todos los datos en datatables
        if ($request->ajax()) {
            $query = Vehiculo::join('companias', 'vehiculos.id_compania', '=', 'companias.id')
                ->leftJoin('vehiculo_vendedor', 'vehiculos.id', '=', 'vehiculo_vendedor.id_vehiculo')
                ->leftJoin('vendedors', 'vehiculo_vendedor.id_vendedor', '=', 'vendedors.id')
                ->select('vehiculos.*', 'companias.nombre as compania_nombre', 'vendedors.nombre as vendedor_nombre');


            //Aplicamos el filtro por compania o placa de forma independiente o combinada
            if ($request->filled('compania') || $request->filled('placa')) {
                $query->where(function ($query) use ($request) {
                    if ($request->filled('compania')) {
                        $query->where('vehiculos.id_compania', $request->input('compania'));
                    }

                    if ($request->filled('placa')) {
                        $query->orWhere('vehiculos.placa', 'like', '%' . $request->input('placa') . '%');
                    }
                });

                //Log del filtro
                Log::info('Filtro aplicado en la funcion index: Compañia = '.$request->input('compania').', Placa = '.$request->input('placa'));
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->toJson();
        }

        return view('vehiculo', compact('companias'));

    }

    /* 
        Funciona por COMPAÑIA O POR PLACA de forma independiente
        if ($request->filled('compania')) {
            $query->where('vehiculos.id_compania', $request->input('compania'));
        }
        if ($request->filled('placa')) {
            $query->where('vehiculos.placa', 'like', '%' . $request->input('placa') . '%');
        }
    */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validator = Validator::make($request->all(), [
            'placa' => 'required|string',
            'peso' => 'required|string',
            'paquete' => 'required|string',
            'volumen' => 'required|string',
            'id_compania' => 'required|exists:companias,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 400); // 400 Bad Request
        }

        try {
            // Recuperar datos del formulario
            $placa = $request->input('placa');
            $peso = $request->input('peso');
            $paquete = $request->input('paquete');
            $volumen = $request->input('volumen');
            $idCompania = $request->input('id_compania');

            // Crear el vehículo
            $vehiculo = Vehiculo::create([
                'placa' => $placa,
                'peso' => $peso,
                'paquete' => $paquete,
                'volumen' => $volumen,
                'id_compania' => $idCompania,
            ]);

            // Crear un vendedor relacionado automáticamente
            $vendedor = new Vendedor([
                'nombre' => $placa,
                'codigo' => $placa,
                'usuario' => $placa,
                'contraseña' => $placa,
                'id_compania' => $idCompania,
            ]);
            $vendedor->save();

            // Asociar vehículo y vendedor
            DB::table('vehiculo_vendedor')->insert([
                'id_vehiculo' => $vehiculo->id,
                'id_vendedor' => $vendedor->id,
            ]);

            //Prueba de logs
            Log::info('Vehiculo agregado: '.$vehiculo.' Vendedor agregado: '.$vendedor);

            return response()->json([
                'message' => 'Vehículo y Vendedor registrados correctamente.',
                'data' => [
                    'vehiculo' => $vehiculo,
                    'vendedor' => $vendedor,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el Vehículo y Vendedor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /* API FUNCIONA A 100% */
    public function addApi(Request $request)
    {
        try {
            // Validar los datos de la solicitud
            $validator = Validator::make($request->all(), [
                'placa' => 'required|string',
                'peso' => 'required|string',
                'paquete' => 'required|string',
                'volumen' => 'required|string',
                'id_compania' => 'required|exists:companias,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors(),
                ], 422); // 422 Unprocessable Entity
            }

            // Crear el vehículo
            $vehiculo = Vehiculo::create($request->all());

            // Crear un vendedor relacionado automáticamente
            $vendedor = new Vendedor([
                'nombre' => $vehiculo->placa,
                'codigo' => $vehiculo->placa,
                'usuario' => $vehiculo->placa,
                'contraseña' => $vehiculo->placa,
                'id_compania' => $request->json()->get('id_compania'),
            ]);
            $vendedor->save(); //persistencia

            //QUery builder -> Funcionalidades propias de la base de datos
            DB::table('vehiculo_vendedor')->insert(
            ['id_vehiculo'=>$vehiculo->id, 'id_vendedor'=>$vendedor->id],
            );

            return response()->json([
                'message' => 'Vehículo y Vendedor registrados correctamente.',
                'data' => [
                    'vehiculo' => $vehiculo,
                    'vendedor' => $vendedor,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el Vehículo y Vendedor.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        //
    }
}
