@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Editar Tienda</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.retiro-en-tienda.index') }}">Retiro en Tienda</a></div>
                <div class="breadcrumb-item">Editar Tienda</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Información de la Tienda</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.retiro-en-tienda.update', $tienda->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre de la Tienda <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nombre_tienda" value="{{ $tienda->nombre_tienda }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" name="telefono" value="{{ $tienda->telefono }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dirección <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="direccion" value="{{ $tienda->direccion }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ciudad <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ciudad" value="{{ $tienda->ciudad }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Horario de Apertura</label>
                                            <input type="time" class="form-control" name="horario_apertura" value="{{ $tienda->horario_apertura }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Horario de Cierre</label>
                                            <input type="time" class="form-control" name="horario_cierre" value="{{ $tienda->horario_cierre }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Días Disponibles</label>
                                    <input type="text" class="form-control" name="dias_disponibles" value="{{ $tienda->dias_disponibles }}" placeholder="Ej: Lunes a Viernes">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Latitud <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="latitud" id="latitud" value="{{ $tienda->latitud }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Longitud <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="longitud" id="longitud" value="{{ $tienda->longitud }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Mapa de Ubicación</label>
                                    <div id="map" style="height: 400px; border-radius: 5px;"></div>
                                    <small class="text-muted">Haga clic en el mapa para seleccionar la ubicación exacta de la tienda.</small>
                                </div>

                                <div class="form-group">
                                    <label>Instrucciones de Retiro</label>
                                    <textarea class="form-control" name="instrucciones_retiro" rows="3">{{ $tienda->instrucciones_retiro }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Documentos Requeridos</label>
                                    <textarea class="form-control" name="documentos_requeridos" rows="3">{{ $tienda->documentos_requeridos }}</textarea>
                                    <small class="text-muted">Ejemplo: DNI, Comprobante de compra, etc.</small>
                                </div>

                                <div class="form-group">
                                    <div class="control-label">Estado</div>
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="estado" class="custom-switch-input" {{ $tienda->estado ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Activo</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>Contenido Adicional</label>
                                    <textarea name="content" class="summernote">{{ $tienda->content }}</textarea>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">Actualizar Tienda</button>
                                    <a href="{{ route('admin.retiro-en-tienda.index') }}" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        // Coordenadas predeterminadas (Lima, Perú)
        var lat = {{ $tienda->latitud ?? -12.046374 }};
        var lng = {{ $tienda->longitud ?? -77.042793 }};
        
        // Inicializar el mapa
        var map = L.map('map').setView([lat, lng], 13);
        
        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Añadir marcador en la posición inicial
        var marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        
        // Actualizar coordenadas cuando se mueva el marcador
        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            $('#latitud').val(position.lat.toFixed(8));
            $('#longitud').val(position.lng.toFixed(8));
        });
        
        // Añadir marcador al hacer clic en el mapa
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            $('#latitud').val(e.latlng.lat.toFixed(8));
            $('#longitud').val(e.latlng.lng.toFixed(8));
        });
        
        // Actualizar el mapa cuando se cambien manualmente las coordenadas
        $('#latitud, #longitud').change(function() {
            var lat = parseFloat($('#latitud').val());
            var lng = parseFloat($('#longitud').val());
            
            if(!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 13);
            }
        });
        
        // Invalidar tamaño del mapa para corregir problemas de visualización en pestañas
        setTimeout(function() {
            map.invalidateSize();
        }, 500);
    });
</script>
@endpush