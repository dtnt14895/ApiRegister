<?php

namespace App\Http\Controllers;

use App\Models\Selecciones;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SeleccionesController extends Controller
{
    

    public function create(Request $request)
    {

       
        $validator = validator($request->all(), [
            'alumno_id' => 'required|exists:alumnos,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $seleccion = new Selecciones();
            $seleccion->alumno_id = $request->alumno_id;
            $seleccion->curso_id = $request->curso_id;
            $seleccion->save();

            return response()->json(['message' => 'Selección creada correctamente'], 201);
        }
        catch (QueryException $e) {
            $errormsj = $e->getMessage();
        
            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';
        
                $values = explode('-', $duplicateValue);
                $firstValue = $values[0] ?? 'Alumno';
                $secondValue = $values[1] ?? 'seleccionada';
        
                return response()->json(['error' => "El alumno $firstValue ya esta en la clase $secondValue"], 422);
            }
        
            return response()->json(['error' => 'Error en la acción realizada: ' . $errormsj], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }

    public function destroy(Request $request)
    {
        $validator = validator($request->all(), [
            'alumno_id' => 'required|exists:alumnos,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $seleccion = Selecciones::where('alumno_id', $request->alumno_id)
                                 ->where('curso_id', $request->curso_id)
                                ->firstOrFail();
                        
            $seleccion->delete();
    
            return response()->json(['message' => 'Seleccion de clase eliminada correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El alumno '. $request->alumno_id.' no tiene seleccionada la materia '.$request->curso_id], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'El alumno '. $request->alumno_id.' no tiene seleccionada la materia '.$request->curso_id], 404);
            
        }
    }
    
}
