@extends('admin.layouts.master')

@section('content')
      <!-- Contenido Principal -->
        <section class="section">
          <div class="section-header">
            <h1>Ítems de Variante de Producto</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Actualizar Ítem de Variante</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.products-variant-item.update', $variantItem->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nombre de Variante</label>
                            <input type="text" class="form-control" name="variant_name" value="{{$variantItem->productVariant->name}}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nombre del Ítem</label>
                            <input type="text" class="form-control" name="name" value="{{$variantItem->name}}">
                        </div>

                        <div class="form-group">
                            <label>Precio <code>(Establecer 0 para que sea gratuito)</code></label>
                            <input type="text" class="form-control" name="price" value="{{$variantItem->price}}">
                        </div>

                        <div class="form-group">
                            <label for="inputState">¿Es Predeterminado?</label>
                            <select id="inputState" class="form-control" name="is_default">
                                <option value="">Seleccionar</option>
                              <option {{$variantItem->is_default == 1 ? 'selected' : ''}} value="1">Sí</option>
                              <option {{$variantItem->is_default == 0 ? 'selected' : ''}} value="0">No</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputState">Estado</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$variantItem->status == 1 ? 'selected' : ''}} value="1">Activo</option>
                              <option {{$variantItem->status == 0 ? 'selected' : ''}} value="0">Inactivo</option>
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
