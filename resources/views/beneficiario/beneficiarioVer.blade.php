@extends('adminlte::layouts.app')

{{-- Titulo de la pagina --}}
@section('htmlheader_title')
  Ver beneficiario
@endsection

{{-- Seccion para agregar estilos CSS extras a los que se cargan por defecto --}}
@section('CSSExtras')

@endsection

{{-- Titulo del header --}}
@section('contentheader_title')
  Ver beneficiario
@endsection

{{-- Descripcion del header OPCIONAL --}}
@section('contentheader_description')
 
@endsection

{{-- Seccion principal de la aplicacion --}}
@section('main-content')

{{-- Include de los mensajes de errror --}}
@include('partials.alertamensajes')

<!--comienza la vista del formulario de registro alumnos-->
<div class="row">
  <div class="col-md-12">
    <!-- Horizontal Form -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos de la ficha del beneficiario</h3>
      </div><!-- /.box-header -->
      <form class="form-horizontal" action="{{ route('beneficiarioEditarPost', ['id' => $beneficiario->id]) }}" method="post">
        {{ csrf_field() }}
          <!-- inicio box-body -->
          <div class="box-body">
              <div class="col-xs-6">
                <h4 class="box-title">Datos del contacto</h4>
                {{-- nombre --}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nombres:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $beneficiario->nombre }}" name="nombre" disabled="true">
                  </div>
                </div>
                {{-- apellidos --}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">Apellidos:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $beneficiario->apellido }}" name="apellido" disabled="true">
                  </div>
                </div>
                {{-- DUI --}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">DUI:</label>
                  <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ $beneficiario->dui}}" name="dui" disabled="true">
                  </div>
                </div>
                {{-- correo --}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">Correo:</label>
                  <div class="col-sm-10">
                  <input type="email" class="form-control" value="{{ $beneficiario->correo }}" name="correo" disabled="true">
                  </div>
                </div>
                {{-- telefono --}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">Telefono:</label>
                  <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ $beneficiario->telefono }}" name="telefono" disabled="true">
                  </div>
                </div>
              </div>
            <div class="col-xs-6">
              <h4 class="box-title">Datos de organizacion</h4>
              {{-- nombre organizacion --}}
              <div class="form-group">
                <label class="col-sm-2 control-label">Nombre :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ $beneficiario->organizacion }}" name="organizacion" disabled="true">
                </div>
              </div>
              {{-- telefono organizacion  --}}
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Telefono :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ $beneficiario->telefono_organizacion }}" name="telefonoOrganizacion" disabled="true">
                </div>
              </div>
              {{-- correo --}}
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Correo :</label>
                <div class="col-sm-10">
                <input type="email" class="form-control" value="{{ $beneficiario->correo_organizacion }}" name="correoOrganizacion" disabled="true">
                </div>
              </div>
              {{-- direccion organizacion --}}
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Direccion :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ $beneficiario->direccion_organizacion }}" name="direccionOrganizacion" disabled="true">
                </div>
              </div>
            </div>
          </div><!-- /.box-body -->
        <div class="box-footer">
        <a href="{{ route('beneficiarioLista') }}" class="btn btn-lg btn-default">Ver Lista</a>
         @if( Auth::user()->rol[0]->nombre == "coordinador_Sups")
        <a href="{{ route('beneficiarioEditar',['id'=>$beneficiario->id]) }}" class="btn btn-lg btn-warning pull-right">Editar</a>
        @endif
        </div><!-- /.box-footer -->
      </form>
    </div><!-- /.box -->
  </div>
</div>

@endsection

{{-- Seccion para insertar JS extras a los que se cargan por defecto --}}
@section('JSExtras')

@endsection
