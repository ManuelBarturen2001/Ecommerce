@extends('admin.layouts.master')

@section('content')
    <!-- Contenido Principal -->
    <section class="section">
        <div class="section-header">
            <h1>Slider</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Crear Slider</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.slider.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Banner</label>
                                    <input type="file" class="form-control" name="banner">
                                </div>

                                <div class="form-group">
                                    <label>Tipo</label>
                                    <input type="text" class="form-control" name="type" value="{{old('type')}}">
                                </div>
                                <div class="form-group">
                                    <label>Título</label>
                                    <input type="text" class="form-control" name="title" value="{{old('title')}}">
                                </div>
                                <div class="form-group">
                                    <label>Precio Inicial</label>
                                    <input type="text" class="form-control" name="starting_price" value="{{old('starting_price')}}">
                                </div>
                                <div class="form-group">
                                    <label>URL del Botón</label>
                                    <input type="text" class="form-control" name="btn_url" value="{{old('btn_url')}}">
                                </div>
                                <div class="form-group">
                                    <label>Orden</label>
                                    <input type="text" class="form-control" name="serial" value="{{old('serial')}}">
                                </div>
                                <div class="form-group">
                                    <label for="inputState">Estado</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
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

