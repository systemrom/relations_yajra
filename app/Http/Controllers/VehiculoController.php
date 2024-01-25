<?php

namespace App\Http\Controllers;

use App\Models\Companias;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Vendedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VehiculoController extends Controller
{
    public  function obtenerDataAPI()
    {
        try {
            // Realizar la solicitud a la API
            $response = Http::withHeaders([
                'Authorization' => 'Token 6b89884a6954868eb58da4c4e16345bb9809abea',
            ])->get('https://api.simpliroute.com/v1/routes/vehicles/');

            // Decodificar la respuesta JSON
            $data = $response->json();
            Log::info('Acceso exitoso a la API, datos obtenidos: '.json_encode($data));

            // Verificar si la respuesta es válida
            if (isset($data)) {
                return view('api', compact('data'));
            } else {
                Log::error('Error en el acceso a la API.');
                return response()->json(['error' => 'Respuesta de la API incompleta o sin datos.']);
            }
        } catch (\Exception $ex) {
            Log::error('Error en el acceso a la API: '.$ex->getMessage());
            return response()->json(['error' => 'Error en el acceso a la API: ' . $ex->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        //Listado de las companias
        $companias = Companias::pluck('nombre', 'id');


        //Listado de todos los datos en datatables
        if ($request->ajax()) {
            $query = Vehiculo::join('companias', 'vehiculos.id_compania', '=', 'companias.id')
                ->leftJoin('vehiculo_vendedor', 'vehiculos.id', '=', 'vehiculo_vendedor.id_vehiculo')
                ->leftJoin('vendedors', 'vehiculo_vendedor.id_vendedor', '=', 'vendedors.id')
                ->leftJoin('vehiculo_integracion', 'vehiculos.id','=','vehiculo_integracion.id_vehiculo')
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
                Log::info('Filtro aplicado en el metodo index: Compañia = '.$request->input('compania').', Placa = '.$request->input('placa'));
            }

            //Condicion para mostrar solo datos que tengan integracion:
            $query->whereNotNull('vehiculo_integracion.id_integracion');

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->toJson();
        }
        return view('vehiculo', compact('companias'));
    }

    /*
     * public function index(Request $request)
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
                Log::info('Filtro aplicado en el metodo index: Compañia = '.$request->input('compania').', Placa = '.$request->input('placa'));
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->toJson();
        }
        return view('vehiculo', compact('companias'));
    }
    */

    /*
        Funciona por COMPAÑIA O POR PLACA de forma independiente
        if ($request->filled('compania')) {
            $query->where('vehiculos.id_compania', $request->input('compania'));
        }
        if ($request->filled('placa')) {
            $query->where('vehiculos.placa', 'like', '%' . $request->input('placa') . '%');
        }
    */

    public function create()
    {
        //
    }

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


            /*
             * Se podría hacer el vehiculo a new Vehiculo.... similar a lo de vendedor y los $vehiculo->save();
             * y $vendedor->save(); colocarlos dentro del condicional del if($vehicless) para que
             * si al enviar la solicitud a la API ... si se encuentra el ID se agregué en base de datos interna
             * y sino se encuentra el id pz.. no se agregaría en la base de datos.
            */
            // Crear el vehículo
            $vehiculo = new Vehiculo([
                'placa' => $placa,
                'peso' => $peso,
                'paquete' => $paquete,
                'volumen' => $volumen,
                'id_compania' => $idCompania,
            ]);

//            $vehiculo = Vehiculo::create([
//                'placa' => $placa,
//                'peso' => $peso,
//                'paquete' => $paquete,
//                'volumen' => $volumen,
//                'id_compania' => $idCompania,
//            ]);

            // Crear un vendedor relacionado automáticamente
            $vendedor = new Vendedor([
                'nombre' => $placa,
                'codigo' => $placa,
                'usuario' => $placa,
                'contraseña' => $placa,
                'id_compania' => $idCompania,
            ]);
            //$vendedor->save();

            /*ARRAY PARA LA API*/
            $datosSendApi = [
                'name' => $placa,
                'shift_start' => '05:00:00',
                'shift_end' => '23:00:00',
                'capacity' => 10000,
                'capacity_2' => 200,
                'capacity_3' => 100,
                'default_driver' => null,
                'location_start_address' => "Avenida San Borja Sur 490, Cercado de Lima, Perú",
                'location_start_latitude' => "-12.101389",
                'location_start_longitude' => "-77.004534",
                'location_end_address' => "Avenida San Borja Sur 490, Cercado de Lima, Perú",
                'location_end_latitude' => "-12.101389",
                'location_end_longitude' => "-77.004534",
                'skills' => [54569],
                'reference_id' => "45D-23A",
                'min_load' => 10,
                'min_load_2' => 0,
                'min_load_3' => 0,
                'max_visit' => 50,
            ];

            //Realizando la solicitud a la API del tercero:
            $responseApi = Http::withHeaders([
                'Authorization' => 'Token 6b89884a6954868eb58da4c4e16345bb9809abea',
            ])->post('https://api.simpliroute.com/v1/routes/vehicles/',$datosSendApi);

            //Decodificado del json
            $dataApi = $responseApi->json();
            Log::info('Acceso a la API: '.json_encode($dataApi));

            // Verificar si la respuesta es un array asociativo y sino se obtiene el id de la API
            if (!is_array($dataApi) || !isset($dataApi['id'])) {
                Log::error('La respuesta de la API del tercero no es un array asociativo válido.');
                return response()->json(['error' => 'Respuesta de la API del tercero no válida.']);
            }else{
                $vehicles = [
                    'id'=>$dataApi['id'], //id
                ];
                Log::info('ID OBTENIDO: '.$vehicles['id']);
            }

            if($vehicles){
                $idIntegra = $vehicles['id'];

                //Al acceder a la API recién agregamos en la base de datos interna -> efectos de prueba
                $vehiculo->save();
                $vendedor->save();
                // Asociar vehículo y vendedor
                DB::table('vehiculo_vendedor')->insert([
                    'id_vehiculo' => $vehiculo->id,
                    'id_vendedor' => $vendedor->id,
                ]);
                Log::info('DATOS INTERNOS => Vehiculo agregado: '.$vehiculo.' Vendedor agregado: '.$vendedor);

                DB::table('vehiculo_integracion')->insert([
                    'id_vehiculo'=>$vehiculo->id,
                    'id_integracion'=>$idIntegra,
                ]);
                Log::info('DATOS INTEGRACION => Vehiculo agregado: '.$vehiculo.' Integracion agregada: '.$idIntegra);

                return response()->json([
                    'message' => 'Vehículo, Vendedor e Integracion registradas correctamente.',
                    'data' => [
                        'vehiculo' => $vehiculo,
                        'vendedor' => $vendedor,
                        'integracion' =>$idIntegra,
                    ],
                ], 201);
            }else{ //si no se puede obtener el ID se agrega por default el id
                Log::error('Error al obtener el ID de la API.');
                return response()->json(['error' => 'Respuesta de la API del tercero incompleta o sin datos.']);
            }
        } catch (\Exception $e) {
            Log::error('Error en la solicitud a la API del tercero: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la solicitud a la API del tercero: ' . $e->getMessage()]);
        }
    }

    /*
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

            //ARRAY PARA LA API
            $datosSendApi = [
            'name' => $placa,
            'shift_start' => '05:00:00',
            'shift_end' => '23:00:00',
            'capacity' => 10000,
            'capacity_2' => 200,
            'capacity_3' => 100,
            'default_driver' => null,
            'location_start_address' => "Avenida San Borja Sur 490, Cercado de Lima, Perú",
            'location_start_latitude' => "-12.101389",
            'location_start_longitude' => "-77.004534",
            'location_end_address' => "Avenida San Borja Sur 490, Cercado de Lima, Perú",
            'location_end_latitude' => "-12.101389",
            'location_end_longitude' => "-77.004534",
            'skills' => [54569],
            'reference_id' => "45D-23A",
            'min_load' => 10,
            'min_load_2' => 0,
            'min_load_3' => 0,
            'max_visit' => 50,
            ];

                //Realizando la solicitud a la API del tercero:
            $responseApi = Http::withHeaders([
            'Authorization' => 'Token 6b89884a6954868eb58da4c4e16345bb9809abea',
            ])->post('https://api.simpliroute.com/v1/routes/vehicles/',$datosSendApi);

                //Decodificado del json
            $dataApi = $responseApi->json();
            Log::info('Acceso a la API: '.json_encode($dataApi));

                // Verificar si la respuesta es un array asociativo y sino se obtiene el id de la API
            if (!is_array($dataApi) || !isset($dataApi['id'])) {
            Log::error('La respuesta de la API del tercero no es un array asociativo válido.');
            return response()->json(['error' => 'Respuesta de la API del tercero no válida.']);
            }else{
                $vehicles = [
                    'id'=>$dataApi['id'],
                ];
                Log::info('ID OBTENIDO: '.$vehicles['id']);
            }

            if($vehicles){
                $idIntegra = $vehicles['id'];

                // Asociar vehículo y vendedor
                DB::table('vehiculo_vendedor')->insert([
                    'id_vehiculo' => $vehiculo->id,
                    'id_vendedor' => $vendedor->id,
                ]);
                Log::info('DATOS INTERNOS => Vehiculo agregado: '.$vehiculo.' Vendedor agregado: '.$vendedor);

                DB::table('vehiculo_integracion')->insert([
                    'id_vehiculo'=>$vehiculo->id,
                    'id_integracion'=>$idIntegra,
                ]);
                Log::info('DATOS INTEGRACION => Vehiculo agregado: '.$vehiculo.' Integracion agregada: '.$idIntegra);

                return response()->json([
                    'message' => 'Vehículo, Vendedor e Integracion registradas correctamente.',
                    'data' => [
                        'vehiculo' => $vehiculo,
                        'vendedor' => $vendedor,
                        'integracion' =>$idIntegra,
                    ],
                ], 201);
            }else{ //si no se puede obtener el ID se agrega por default el id
                $idIntegra = xxxx;
                DB::table('vehiculo_integracion')->insert([
                    'id_vehiculo'=>$vehiculo->id,
                    'id_integracion'=>$idIntegra,
                ]);
                Log::info('Error al obtener el ID de la API, se agregó la siguiente data: Vehiculo agregado: '.$vehiculo.' Integracion agregada: '.$idIntegra);
                return response()->json(['error' => 'Respuesta de la API del tercero incompleta o sin datos.']);
            }
            } catch (\Exception $e) {
                Log::error('Error en la solicitud a la API del tercero: ' . $e->getMessage());
                return response()->json(['error' => 'Error en la solicitud a la API del tercero: ' . $e->getMessage()]);
            }
        }
    */

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
            DB::table('vehiculo_vendedor')->insert([
                'id_vehiculo'=>$vehiculo->id,
                'id_vendedor'=>$vendedor->id
            ],);

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

    public function listaVehiculos()
    {
        $vehic = Vehiculo::all();
        return response()->json($vehic);
    }

    public function show($id)
    {
        //
        if(request()->ajax()){
            $data = Vehiculo::find($id);
            $data1 = Vendedor::find($id);
            $data2 = Companias::all();
            Log::info('DATA OBTENIDA DE VEHICULO: '.$data.' DATA OBTENIDA DEL VENDEDOR: '.$data1);
            return response()->json(['vehiculo'=>$data, 'vendedor'=>$data1, 'companias'=>$data2]);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //Eliminar en ambas tablas
        $data = Vehiculo::find($id);
        $data->delete();
        //De forma automática elimina el vendedor
        $data1 = Vendedor::find($id);
        $data1->delete($id);

        Log::info('Vehiculo eliminado: '.$data.' Vendedor eliminado: '.$data1);
    }

}
