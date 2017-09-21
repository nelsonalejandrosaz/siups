<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Alumno_escuela;
use App\Alumno;
use App\Escuela;
use App\Expediente;
use Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class AlumnoController extends Controller
{

    public function AlumnosLista()
    {
        if (Auth::user()->rol[0]->id == 2) {
          $alumnos_escuela = Alumno_escuela::all();
        } else {
          $alumnos_escuela = Alumno_escuela::where('escuela_id', Auth::user()->escuela_id)->get();
        }        
        return view('alumnos.alumnos_lista')->with(['alumnos_escuela' => $alumnos_escuela]);
    }

    public function import_csv_file(Request $request){
       $this->validate($request,[
        'csv_file'=>'required',
      ]);
		Excel::load(Input::file('csv_file'), function($hoja){
			$hoja->each(function($fila){
				$alumno = new Alumno;
				$alumno->carnet = $fila->carnet;
				$alumno->nombre = $fila->nombre;
				$alumno->apellido = $fila->apellido;
				$alumno->direccion = $fila->direccion;
				$alumno->telefono = $fila->telefono;
				$alumno->correo = $fila->correo;
				$alumno->lugar_trabajo = $fila->lugar_trabajo;
				$alumno->telefono_trabajo = $fila->telefono_trabajo;
				Alumno::firstOrCreate($alumno->toArray());
				$alumno = Alumno::where('carnet','=',$fila->carnet)->first();
				$escuela = new Escuela;
				$escuela = Escuela::where('codigo','=',$fila->codigo_escuela)->first(); //revisar
				$alumno_escuela = new Alumno_escuela;
				$alumno_escuela->alumno()->associate($alumno);
				$alumno_escuela->escuela()->associate($escuela);
				// $alumno_escuela->save();
				$ae = Alumno_escuela::firstOrCreate(['carnet' => $alumno->carnet, 'escuela_id' => $escuela->id]);
        Expediente::create(['alumno_escuela_id' => $ae->id, 'estado_expediente_id' => 1, 'observaciones' => 'Ninguna']);
				// Alumno::firstOrCreate($fila->toArray());
				// return $fila;
			});
		});
      session()->flash('mensaje', 'CSV cargado');
    	return redirect()->route('alumnoLista');
	}

   public function registroAlumno()
  {
    return view('alumnos.alumno_registro_manual');
  }

  public function guardarAlumno(Request $request)
  {
    //dd($request->all());
    $this->validate($request, [
        'carnet'=>'required|size:7',
        'nombre'=>'required',
        'apellido'=>'required',
        'correo'=>'email',
      ]);
    $alumno = new Alumno;
    $alumno->carnet = $request->carnet;
    $alumno->nombre = $request->nombre;
    $alumno->apellido = $request->apellido;
    $alumno->telefono = $request->telefono;
    $alumno->lugar_trabajo = $request->lugar_trabajo;
    $alumno->telefono_trabajo = $request->telefono_trabajo;
    $alumno->correo = $request->correo;
    $alumno->direccion = $request->direccion;

    // Se busca si el alumno existe, si este no existe
    if ((Alumno::where('carnet','=',$request->carnet)->first()) == null) {
      // Si el alumno no existe se crea una instacia de el y se guarda en la BD
      $alumno = Alumno::create($alumno->toArray());
      // Se busca la escuela del coordinador
      $escuela = Escuela::where('id', Auth::user()->escuela_id)->first();
      $ae = Alumno_escuela::create(['carnet' => $alumno->carnet, 'escuela_id' => $escuela->id]);
      // Se crea el Expediente del alumno con el estado sin abrir
      Expediente::create(['alumno_escuela_id' => $ae->id, 'estado_expediente_id' => 1, 'observaciones' => 'Ninguna']);
      session()->flash('mensaje', 'Alumno ingresado con exito');
      return redirect()->route('alumnoVer',['carnet'=>$alumno->carnet]);
    } else { 
      // Si el alumno existe, se revisa si esta en la misma escuela, si este no es de la misma escuela se procede a crear una nueva instancia de alumno_escuela
      $alumno = Alumno::where('carnet',$request->carnet)->first();
      $alumnos_escuela = $alumno->alumno_escuela;
      foreach ($alumnos_escuela as $alumno_escuela) {
        if ($alumno_escuela->escuela_id == Auth::user()->escuela_id) {
          // Si el alumno es de la misma escuela de devuelve el error
          session()->flash('advertencia', 'Alumno ya existe');
          return redirect()->route('alumnoNuevo') ;
        }
      }
      // Se busca la escuela del coordinador
      $escuela = Escuela::where('id', Auth::user()->escuela_id)->first();
      $ae = Alumno_escuela::create(['carnet' => $alumno->carnet, 'escuela_id' => $escuela->id]);
      // Se crea el Expediente del alumno con el estado sin abrir
      Expediente::create(['alumno_escuela_id' => $ae->id, 'estado_expediente_id' => 1, 'observaciones' => 'Ninguna']);
      session()->flash('mensaje', 'Alumno ingresado con exito');
      return redirect()->route('alumnoVer',['carnet'=>$alumno->carnet]) ;         
    }
  }

  public function editarAlumno($carnet)
  {
      $alumno = Alumno::find($carnet);
      return view('alumnos.alumno_editar')->with(['alumno' => $alumno]);
  }

  public function editarAlumnoGuardar(Request $request){
    $alumno = Alumno::find($request->carnet);
    $alumno->carnet = $request->carnet;
    $alumno->nombre = $request->nombre;
    $alumno->apellido = $request->apellido;
    $alumno->telefono = $request->telefono;
    $alumno->lugar_trabajo = $request->lugar_trabajo;
    $alumno->telefono_trabajo = $request->telefono_trabajo;
    $alumno->correo = $request->correo;
    $alumno->direccion = $request->direccion;
    $alumno->update();
    session()->flash('mensaje', 'Alumno modificado corectamente');
   return redirect()->route('alumnoVer',['carnet'=>$alumno->carnet]) ;
  }

    public function verAlumno($carnet)
  {
    //para que un coordinador de otra escuela no pueda ver los alumnos de otra escuela si introduce el carnet
    $alumno_escuela=Alumno_escuela::where('carnet',$carnet)->first();
    if ( Auth::user()->escuela_id == $alumno_escuela->escuela->id || Auth::user()->rol[0]->nombre=='jefe') {

      $alumno = Alumno::where('carnet',$carnet)->first();
      return view('alumnos.alumno_ver')->with(['alumno' => $alumno]);
  }
  return redirect()->route('permisoDenegado');
}
}
