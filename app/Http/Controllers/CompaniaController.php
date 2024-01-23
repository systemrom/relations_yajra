<?php

namespace App\Http\Controllers;

use App\Models\Companias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CompaniaController extends Controller
{
    //
    public function addCompania(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Error de validacion',
                'error'=>$validator->errors(),
            ], 400);
        }

        try{
            //$compania = Companias::create($request->all());
            DB::select("CALL insertarCompanias(?)", [$request->nombre]);

            Log::info('Compañia agregada correctamente mediante store procedure insertarCompanias: '.$request->nombre);

            return response()->json([
                'message'=>'Compañia agregada correctamente',
                'data'=>[
                    'compañia'=>$request->nombre,
                ]
            ], 200);
        }catch(\Exception $ex){
            Log::error('Error al agregar la compañia: '.$ex->getMessage());

            return response()->json([
                'message'=>'Error al agregar la compañia',
                'error'=>$ex->getMessage(),
            ], 500);
        }

    }
}
