@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Género</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit Género</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.gender.update', $gender->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{$gender->name}}">
                        </div>
                        <div class="form-group">
                            <label for="inputState">Estado</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$gender->status == 1 ? 'selected': ''}} value="1">Activo</option>
                              <option {{$gender->status == 0 ? 'selected': ''}} value="0">Inactivo</option>
                            </select>
                        </div>
                        <button type="submmit" class="btn btn-primary">Modificar</button>
                    </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
