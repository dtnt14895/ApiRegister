<?php

namespace App\Http\Controllers;

use App\Models\Asistencias;
use App\Models\Selecciones;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Validator;

class AsistenciasController extends Controller
{
    public function create(Request $request)
    {

        $request->merge([
            'abreviacion' => strtoupper($request->abreviacion)
        ]);

        $validator = validator($request->all(), [
            'alumno_id' => 'required|exists:alumnos,id',
            'curso_id' => 'required|exists:cursos,id',
            'dia' => 'required|date',
            'abreviacion' => 'required|in:A,T,F'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $seleccion = Selecciones::where('alumno_id', $request->alumno_id)
                ->where('curso_id', $request->curso_id)
                ->firstOrFail()->id;


            $Asistencias = new Asistencias();
            $Asistencias->seleccion_id = $seleccion;
            $Asistencias->dia = $request->dia;
            $Asistencias->abreviacion = $request->abreviacion;
            $Asistencias->save();

            return $Asistencias;
        } catch (QueryException $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';

                $fecha = substr($duplicateValue, 0, 10);
                $id = substr($duplicateValue, 11);

                return response()->json(['error' => "Ya se registro la asistencia del dia $fecha para este Alumno $id"], 422);
            }
            return response()->json(['error' => 'Error en la accion realizada2: ' . $errormsj], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar la asistencia. El alumno ' . $request->alumno_id . ' no tiene seleccionada la materia ' . $request->curso_id], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request)
    {
        $request->merge([
            'abreviacion' => strtoupper($request->abreviacion)
        ]);

        $validator = validator($request->all(), [
            'alumno_id' => 'required|exists:alumnos,id',
            'curso_id' => 'required|exists:cursos,id',
            'dia' => 'required|date',
            'abreviacion' => 'required|in:A,T,F'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {


            $seleccion = Selecciones::where('alumno_id', $request->alumno_id)
            ->where('curso_id', $request->curso_id)
            ->firstOrFail()->id;


            $Asistencias = Asistencias::where('seleccion_id',  $seleccion)
            ->where('dia', $request->dia)
            ->firstOrFail();

            $Asistencias->abreviacion = $request->abreviacion;
            $Asistencias->save();

            return response()->json(['message' => 'Asistencia actualizada correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            if ($e->getModel() === Selecciones::class) {
                return response()->json(['error' => 'No se pudo registrar la asistencia. El alumno ' . $request->alumno_id . ' no tiene seleccionada la materia ' . $request->curso_id], 404);

            } elseif ($e->getModel() === Asistencias::class) {
                return response()->json(['error' => 'No se pudo modifcar la asistencia. El alumno ' . $request->alumno_id . ' no tiene la asistencia del dia ' . $request->dia], 404);

            }


        } catch (QueryException $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? 'Tienes un valor que';

                return response()->json(['error' =>'Error: '. $duplicateValue.' ya esta en uso'], 422);
            }

            return response()->json(['error' => 'Error en la acción realizada'], 500);
        } catch (Exception $e) {
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
            $seleccion = Asistencias::where('alumno_id', $request->alumno_id)
                ->where('curso_id', $request->curso_id)
                ->firstOrFail();

            $seleccion->delete();

            return response()->json(['message' => 'Seleccion de clase eliminada correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El alumno ' . $request->alumno_id . ' no tiene seleccionada la materia ' . $request->curso_id], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'El alumno ' . $request->alumno_id . ' no tiene seleccionada la materia ' . $request->curso_id], 404);
        }
    }
}
