@extends('admin.layouts.master')

@section('content')
      <!-- Contenido Principal -->
        <section class="section">
          <div class="section-header">
            <h1>Métodos de Retiro</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Crear Método</h4>
                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.withdraw-method.store')}}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                        <div class="form-group">
                            <label>Monto Mínimo</label>
                            <input type="text" class="form-control" name="minimum_amount" value="">
                        </div>
                        <div class="form-group">
                            <label>Monto Máximo</label>
                            <input type="text" class="form-control" name="maximum_amount" value="">
                        </div>
                        <div class="form-group">
                            <label>Comisión de Retiro (%)</label>
                            <input type="text" class="form-control" name="withdraw_charge" value="">
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="description" class="summernote"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear</button>
                    </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
