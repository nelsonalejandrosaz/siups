<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tutor;
use App\Especialidad;

class TutorController extends Controller
{




   public function TutoresLista(){
		$tutores=Tutor::all();
    $especialidads = Especialidad::all();
		return view('tutor.tutoresLista')->with(['tutores' => $tutores]);
	}



    public function AgregarTutor(){
      $especialidads = Especialidad::all();
    	return view ('tutor.tutorAgregar')->with(['especialidads' => $especialidads]) ;
    }



    public function guardarTutor(Request $request)
  	{
    $this->validate($request, [

        'nombre'=>'required',
        'apellido'=>'required',
        'correo'=>'email',
        'especialidad_id'=>'required',

      ]);

     if((Tutor::where('dui','=',$request->dui)->first()) == null)
     {
    $Tutor = Tutor::create([

    	'nombre' => $request->input('nombre'),
   		'apellido' => $request->input('apellido'),
    	'correo' => $request->input('correo'),
    	'dui' => $request->get('dui'),
    	'carnet'=>$request->get('carnet'),
      'especialidad_id'=>$request->input('especialidad_id'),
    	]);
    return redirect()->route('TutorVer',['id'=>$Tutor->id]) ;
    }
    else {
      //si ya existe muestra mensaje error
      session()->flash('message.content', 'Dui de ese tutor ya existe, ingrese nuevo Tutor');
      return redirect()->route('tutor.tutorNuevo');
        }
    }



     public function verTutor($id)
  	{
      $tutor = Tutor::find($id);
      return view('tutor.tutorver')->with(['tutor' => $tutor]);
  	}



  	public function editarTutor($id = 1) //REVISAR?
  	{
      $tutor = Tutor::find($id);
      return view('tutor.tutorEditar')->with(['tutor' => $tutor]);
  	}


  	public function editarTutorGuardar(Request $request, $id)
  	{
       $this->validate($request, [
        'nombre'=>'required',
        'apellido'=>'required',
        'correo'=>'email',
        'especialidad_id'=>'required',
        ]);
       //verifica que dui de tutor nos e repita
        $tutor = Tutor::find($id);
        if((Tutor::where('dui','=',$request->dui)->first()) == null||
        $tutor->dui == $request->dui)
     {

    	$tutor = Tutor::find($id);
    	$tutor->nombre = $request->input('nombre');
    	$tutor->apellido = $request->input('apellido');
    	$tutor->correo = $request->input('correo');
      $tutor->especialidad_id = $request->input('especialidad_id');
    	$tutor->dui = $request->input('dui');
    	$tutor->carnet = $request->input('carnet');
    	$tutor->save();

   		return redirect()->route('TutorVer',['id'=>$tutor->id]) ;
      session()->flash('mensaje', 'Tutor modificado corectamente');
    }
  	else {
      //si ya existe muestra mensaje error
      session()->flash('message.content', 'Dui de ese tutor ya existe, ingrese nuevo Tutor');
       return view('tutor.tutorEditar')->with(['tutor' => $tutor]);
     }
    }
}
