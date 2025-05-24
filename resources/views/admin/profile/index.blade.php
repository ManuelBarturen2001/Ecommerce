@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Perfil</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Tablero</a></div>
        <div class="breadcrumb-item">Perfil</div>
      </div>
    </div>
    <div class="section-body">

      <div class="row mt-sm-4">

        <div class="col-12 col-md-12 col-lg-7">
          <div class="card">
            <form method="post" class="needs-validation" novalidate="" action="{{route('admin.profile.update')}}" enctype="multipart/form-data">
                @csrf
              <div class="card-header">
                <h4>Actualizar Perfil</h4>
              </div>
              <div class="card-body">
                  <div class="row">
                    <div class="form-group col-12">
                        <div class="mb-3">
                            <img width="100px" src="{{asset(Auth::user()->image)}}" alt="">
                        </div>
                        <label>Imagen</label>
                        <input type="file" name="image" class="form-control">
                      </div>

                    <div class="form-group col-md-6 col-12">
                      <label>Nombre</label>
                      <input type="text" name="name" class="form-control" value="{{Auth::user()->name}}">
                    </div>
                    <div class="form-group col-md-6 col-12">
                      <label>Correo Electrónico</label>
                      <input type="text" name="email" class="form-control" value="{{Auth::user()->email}}" >
                    </div>
                  </div>

              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary">Guardar Cambios</button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">

              <form method="post" class="needs-validation" novalidate="" action="{{route('admin.password.update')}}" enctype="multipart/form-data">
                  @csrf
                <div class="card-header">
                  <h4>Actualizar Contraseña</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                      <div class="form-group col-12">
                        <label>Contraseña Actual</label>
                        <input type="password" name="current_password" class="form-control" >
                      </div>
                      <div class="form-group col-12">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" >
                      </div>
                      <div class="form-group col-12">
                        <label>Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" >
                      </div>

                    </div>

                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-primary">Guardar Cambios</button>
                </div>
              </form>
            </div>
          </div>

      </div>
    </div>
  </section>
@endsection
