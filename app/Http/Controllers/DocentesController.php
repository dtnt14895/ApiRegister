<?php

namespace App\Http\Controllers;

use App\Models\Docentes;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DocentesController extends Controller
{

    public function index()
    {
        $docentes = Docentes::where('status', 1)->get();
        $docentes->load('cursos');
        return $docentes;
    }

    public function create(Request $request)
    {
        $validator = validator($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $Docente = new Docentes();
            $Docente->nombre = $request->nombre;
            $Docente->apellido = $request->apellido;
            $Docente->email = $request->email;
            $Docente->save();

            return "Registro " . $request->nombre . " añadido satisfactoriamente";
        } catch (QueryException  $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? 'Tienes un valor que';

                return response()->json(['error' => 'Error: ' . $duplicateValue . ' ya esta en uso'], 422);
            }

            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }

    public function show($id)
    {
        $validator = validator(['id' => $id], [
            'id' => 'required|numeric'
            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            $Docente = Docentes::findOrFail($id);

            $Docente->cursos;

            return $Docente;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El alumno ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = validator($request->all(), [
            'id' => 'required|numeric',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $Docente = Docentes::findOrFail($request->id);
            $Docente->nombre = $request->nombre;
            $Docente->apellido = $request->apellido;
            $Docente->email = $request->email;
            $Docente->status = $request->status;;
            $Docente->save();

            return response()->json(['msj' => 'Docente actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El docente ' . $request->id . ' no existe no fue encontrado'], 404);
        } catch (QueryException  $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? 'Tienes un valor que';

                return response()->json(['error' => 'Error: ' . $duplicateValue . ' ya esta en uso'], 422);
            }

            return response()->json(['error' => 'Error en la acción realizada'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }

    public function destroy($id)
    {
        $validator = validator(['id' => $id], [
            'id' => 'required|numeric'
            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $curso = Docentes::findOrFail($id);
            if ($curso->status) {
                $curso->status = 0;
                $curso->save();
                return response()->json(['msj' => 'Docente eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Docente ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Docente ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
