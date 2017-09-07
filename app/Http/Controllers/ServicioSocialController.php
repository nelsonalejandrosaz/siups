<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Servicio_Social;
use App\SocialServicio;
use App\Beneficiario;
use App\Tutor;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ServicioSocialController extends Controller
{

   public function ServicioSocialLista()
    {
       $SocialServicios = SocialServicio::all();
       return view('serviciosocial.servicioSocialLista')-with(['SocialServicios' => $SocialServicios]);
    }


   public function ServicioSocialRegistro()
  {
    $Beneficiarios = Beneficiario::all();
    $Tutors = Tutor::all();
    return view('serviciosocial.servicioSocialRegistro')->with(['Beneficiarios' => $Beneficiarios])->with(['Tutors' => $Tutors]);

  }

  public function ServicioSocialGuardar(Request $request)
  {

    $this->validate($request, [
        'nombreSS'=>'required|size:150',
        'inicioSS'=>'required',
        'horastSS'=>'required',
        'beneficiarioSS'=>'required',
        'tutorSS'=>'required',
      ]);
    $serviciosocial  = new SocialServicio ;
    $serviciosocial->nombreSS = $request->nombreSS;
      $serviciosocial->inicioSS = $request->inicioSS;
        $serviciosocial->finSS = $request->finSS;
          $serviciosocial->beneficiarioSS = $request->horastSS;
            $serviciosocial->tutorSS = $request->horasaSS;


    if ((SocialServicio::where('nombreSS','=',$request->nombreSS)->first()) == null) {
      SocialServicio::firstOrCreate($serviciosocia->toArray());

      SocialServicio::firstOrCreate(['nombreSS' => $nombreSS->nombreSS,]);
      session()->flash('mensaje', 'Ingresado con exito');
      //return redirect()->route('') ;
    } else {
      session()->flash('advertencia', 'Servicio social ya existente');
      //return redirect()->route('alumnoNuevo') ;
    }


  }


 }