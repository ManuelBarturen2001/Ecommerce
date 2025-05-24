@extends('admin.layouts.master')

@section('content')
      <!-- Contenido Principal -->
  <section class="section">
    <div class="section-header">
      <h1>Perfil del Vendedor</h1>
    </div>

    <div class="section-body">

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Actualizar Perfil del Vendedor</h4>
            </div>
            <div class="card-body">
              <form action="{{route('admin.vendor-profile.store')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                      <label>Vista previa</label>
                      <br>
                      <img width="200px" src="{{asset($profile->banner)}}" alt="">
                  </div>
                  <div class="form-group">
                      <label>Banner</label>
                      <input type="file" class="form-control" name="banner">
                  </div>

                  <div class="form-group">
                      <label>Nombre de la Tienda</label>
                      <input type="text" class="form-control" name="shop_name" value="{{$profile->shop_name}}">
                  </div>

                  <div class="form-group">
                      <label>Teléfono</label>
                      <input type="text" class="form-control" name="phone" value="{{$profile->phone}}">
                  </div>
                  <div class="form-group">
                      <label>Correo electrónico</label>
                      <input type="text" class="form-control" name="email"  value="{{$profile->email}}">
                  </div>
                  <div class="form-group">
                      <label>Dirección</label>
                      <input type="text" class="form-control" name="address" value="{{$profile->address}}">
                  </div>
                  <div class="form-group">
                      <label>Descripción</label>
                      <textarea class="summernote" name="description">{{$profile->description}}</textarea>
                  </div>
                  <div class="form-group">
                      <label>Facebook</label>
                      <input type="text" class="form-control" name="fb_link" value="{{$profile->fb_link}}">
                  </div>
                  <div class="form-group">
                      <label>Twitter</label>
                      <input type="text" class="form-control" name="tw_link" value="{{$profile->tw_link}}">
                  </div>
                  <div class="form-group">
                      <label>Instagram</label>
                      <input type="text" class="form-control" name="insta_link" value="{{$profile->insta_link}}">
                  </div>

                  <button type="submit" class="btn btn-primary">Actualizar</button>
              </form>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>
@endsection
