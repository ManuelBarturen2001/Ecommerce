@extends('admin.layouts.master')

@section('content')
<!-- Contenido Principal -->
<section class="section">
  <div class="section-header">
    <h1>Marca</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Actualizar Marca</h4>
          </div>
          <div class="card-body">
            <form action="{{route('admin.brand.update', $brand->id)}}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="form-group">
                <label>Vista Previa</label>
                <br>
                <img width="200" src="{{asset($brand->logo)}}" alt="">
              </div>

              <div class="form-group">
                <label>Logo</label>
                <input type="file" class="form-control" name="logo">
              </div>

              <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="name" value="{{$brand->name}}">
              </div>

              <div class="form-group">
                <label for="inputState">¿Es Destacado?</label>
                <select id="inputState" class="form-control" name="is_featured">
                  <option value="">Seleccionar</option>
                  <option {{$brand->is_featured == 1 ? 'selected': ''}} value="1">Sí</option>
                  <option {{$brand->is_featured == 0 ? 'selected': ''}} value="0">No</option>
                </select>
              </div>

              <div class="form-group">
                <label for="inputState">Estado</label>
                <select id="inputState" class="form-control" name="status">
                  <option {{$brand->status == 1 ? 'selected' : ''}} value="1">Activo</option>
                  <option {{$brand->status == 0 ? 'selected' : ''}} value="0">Inactivo</option>
                </select>
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
