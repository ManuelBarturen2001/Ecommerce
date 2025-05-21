@extends('frontend.dashboard.layouts.master')
@section('title')
{{$settings->site_name}} || Crear Dirección
@endsection
@section('content')
<!-- Botón de menú móvil -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
</button>
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
<style>
        .geocoder-control-suggestions {
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: white;
            z-index: 1000;
        }
        .geocoder-control-suggestion {
            padding: 5px;
            cursor: pointer;
        }
        .geocoder-control-suggestion:hover {
            background-color: #f0f0f0;
        }
</style>
<!--============================
        BREADCRUMB START
    ==============================-->
    
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>Mis Direcciones</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.address.index')}}">Mis Direcciones</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Crear Dirección</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->

    <!-- Dashboard Area -->
    <section class="dashboard-area">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-xl-2">
                    @include('frontend.dashboard.layouts.sidebar')
                </div>
                 <!-- Main Content -->
                <div class="col-lg-9 col-xl-10">
                  <div class="dashboard-main-content">
                    <div class="dashboard-card">
                      <div class="dashboard-card-header">
                          <h4>Crear Nueva Dirección</h4>
                      </div>
                      <div class="orders-container">
                        <div class="row">
                            
                            <div class="dashboard_content mt-2 mt-md-0">
                                <div class="wsus__check_form p-3">
                                <form action="{{route('user.address.address.create')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="wsus__check_single_form">
                                                <input type="text" placeholder="Nombre *" name="name" value="{{old('name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="wsus__check_single_form">
                                                <input type="text" placeholder="Teléfono *" name="phone" value="{{old('phone')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="wsus__check_single_form">
                                                <input type="email" placeholder="Correo *" name="email" value="{{old('email')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="wsus__check_single_form">
                                                <select class="select_2" name="dep_id" id="departamento_id">
                                                    <option value="">Seleccione un departamento *</option>
                                                    @foreach($departamentos as $departamento)
                                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="wsus__check_single_form">
                                                <select class="select_2" name="prov_id" id="provincia_id" disabled>
                                                    <option value="">Seleccione una provincia *</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="wsus__check_single_form">
                                                <select class="select_2" name="dist_id" id="distrito_id" disabled>
                                                    <option value="">Seleccione un distrito *</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="wsus__check_single_form">
                                                <input type="text" placeholder="Código Postal *" name="zip" value="{{old('zip')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="wsus__check_single_form">
                                                <input type="text" placeholder="Dirección *" name="address" value="{{old('address')}}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mt-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Seleccione su ubicación en el mapa</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <input type="text" id="address-search" class="form-control" placeholder="Buscar una dirección...">
                                                    </div>
                                                    <div id="address_map" style="height: 400px;"></div>
                                                    <input type="hidden" name="latitude" id="latitude">
                                                    <input type="hidden" name="longitude" id="longitude">
                                                    <div class="mt-2">
                                                        <p><strong>Ubicación seleccionada:</strong> <span id="selected-location">Ninguna</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 mt-3">
                                            <div class="wsus__check_single_form">
                                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            
                        </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
    @endsection

@push('scripts')
<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
<script>
   // Configurar AJAX globalmente
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // =============================================
        // Lógica para departamentos, provincias y distritos
        // =============================================
        
        // Cargar provincias al seleccionar departamento
        $('#departamento_id').on('change', function() {
            const departamentoId = $(this).val();
            
            if (departamentoId) {
                $('#provincia_id').prop('disabled', false);
                $('#provincia_id').html('<option value="">Cargando provincias...</option>');
                
                $.ajax({
                    url: "/user/address/get-provincias",
                    type: "POST",
                    data: { dep_id: departamentoId },
                    success: function(data) {
                        $('#provincia_id').empty();
                        $('#provincia_id').append('<option value="">Seleccione una provincia *</option>');
                        
                        $.each(data, function(key, value) {
                            $('#provincia_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        
                        $('#distrito_id').empty();
                        $('#distrito_id').append('<option value="">Seleccione un distrito *</option>');
                        $('#distrito_id').prop('disabled', true);
                    },
                    error: function(error) {
                        console.error('Error al cargar provincias:', error);
                        toastr.error('Error al cargar las provincias');
                    }
                });
            } else {
                $('#provincia_id').prop('disabled', true);
                $('#provincia_id').empty();
                $('#provincia_id').append('<option value="">Seleccione una provincia *</option>');
                
                $('#distrito_id').prop('disabled', true);
                $('#distrito_id').empty();
                $('#distrito_id').append('<option value="">Seleccione un distrito *</option>');
            }
        });
        
        // Cargar distritos al seleccionar provincia
        $('#provincia_id').on('change', function() {
            const provinciaId = $(this).val();
            
            if (provinciaId) {
                $('#distrito_id').prop('disabled', false);
                $('#distrito_id').html('<option value="">Cargando distritos...</option>');
                
                $.ajax({
                    url: "/user/address/get-distritos",
                    type: "POST",
                    data: { prov_id: provinciaId },
                    success: function(data) {
                        $('#distrito_id').empty();
                        $('#distrito_id').append('<option value="">Seleccione un distrito *</option>');
                        
                        $.each(data, function(key, value) {
                            $('#distrito_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar distritos:', error);
                        toastr.error('Error al cargar los distritos');
                    }
                });
            } else {
                $('#distrito_id').prop('disabled', true);
                $('#distrito_id').empty();
                $('#distrito_id').append('<option value="">Seleccione un distrito *</option>');
            }
        });

        // =============================================
        // Lógica del Mapa
        // =============================================
        
        // Coordenadas iniciales (centro de Perú)
        let initialLat = -12.046374;
        let initialLng = -77.042793;
        let zoomLevel = 13;
        
        // Inicializar el mapa
        const map = L.map('address_map').setView([initialLat, initialLng], zoomLevel);
        
        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Marcador para la ubicación seleccionada
        let marker;
        
        // Función para actualizar el marcador
        function updateMarker(lat, lng, address) {
            // Si ya existe un marcador, eliminarlo
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Crear nuevo marcador
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);
            
            // Centrar mapa en el marcador
            map.setView([lat, lng]);
            
            // Actualizar campos ocultos
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#selected-location').text(address || `${lat.toFixed(6)}, ${lng.toFixed(6)}`);
            
            // Actualizar campos cuando se arrastra el marcador
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                $('#latitude').val(position.lat);
                $('#longitude').val(position.lng);
                $('#selected-location').text(`${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}`);
                
                // Geocodificación inversa para obtener la dirección
                reverseGeocode(position.lat, position.lng);
            });
        }
        
        // Función para geocodificación inversa
        function reverseGeocode(lat, lng) {
            $.ajax({
                url: 'https://nominatim.openstreetmap.org/reverse',
                type: 'GET',
                data: {
                    format: 'json',
                    lat: lat,
                    lon: lng,
                    // zoom: 18,
                    addressdetails: 1
                },
                success: function(data) {
                    if (data && data.display_name) {
                        const address = data.display_name;
                        $('#selected-location').text(address);
                        
                        // Opcionalmente, actualizar el campo de dirección
                        if ($('input[name="address"]').val(address) ) {
                            $('input[name="address"]').val(address);
                        }
                    }
                },
                error: function(error) {
                    console.error('Error en geocodificación inversa:', error);
                }
            });
        }
        
        // Función para geocodificación (buscar dirección)
        function geocodeAddress(query) {
            if (!query || typeof query !== 'string' || !query.trim()) {
                return;
            }
            
            $.ajax({
                url: 'https://nominatim.openstreetmap.org/search',
                type: 'GET',
                data: {
                    q: query,
                    format: 'json',
                    limit: 5
                },
                success: function(data) {
                    displaySearchResults(data);
                },
                error: function(error) {
                    console.error('Error en geocodificación:', error);
                    toastr.error('Error al buscar la dirección');
                }
            });
        }
        
        // Click en el mapa para establecer ubicación
        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
        
        // Control de búsqueda personalizado
        const searchInput = document.getElementById('address-search');
        
        // Prevenir envío del formulario al presionar Enter en el campo de búsqueda
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (e.target.value.length >= 3) {
                    geocodeAddress(e.target.value);
                }
                return false;
            }
        });
        
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value;
            clearTimeout(window.searchTimeout);
            
            window.searchTimeout = setTimeout(function() {
                if (query.length >= 3) {
                    geocodeAddress(query);
                } else {
                    clearSearchResults();
                }
            }, 500); // Debounce para no hacer demasiadas solicitudes
        });
        
        // Mostrar resultados de búsqueda
        function displaySearchResults(results) {
            clearSearchResults();
            
            if (!results || results.length === 0) {
                return;
            }
            
            const resultsContainer = document.createElement('div');
            resultsContainer.className = 'geocoder-control-suggestions';
            resultsContainer.id = 'search-results';
            resultsContainer.style.position = 'absolute';
            resultsContainer.style.zIndex = '1000';
            resultsContainer.style.backgroundColor = 'white';
            resultsContainer.style.border = '1px solid #ccc';
            resultsContainer.style.borderRadius = '4px';
            resultsContainer.style.width = '100%';
            resultsContainer.style.maxHeight = '200px';
            resultsContainer.style.overflowY = 'auto';
            
            results.forEach(function(result) {
                const item = document.createElement('div');
                item.className = 'geocoder-control-suggestion';
                item.style.padding = '8px 12px';
                item.style.cursor = 'pointer';
                item.style.borderBottom = '1px solid #eee';
                item.textContent = result.display_name;
                
                item.addEventListener('mouseover', function() {
                    item.style.backgroundColor = '#f5f5f5';
                });
                
                item.addEventListener('mouseout', function() {
                    item.style.backgroundColor = 'white';
                });
                
                item.addEventListener('click', function() {
                    // Actualizar el mapa con la ubicación seleccionada
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);
                    updateMarker(lat, lng, result.display_name);
                    searchInput.value = result.display_name;
                    
                    // Actualizar el campo de dirección
                    $('input[name="address"]').val(result.display_name);
                    
                    // Limpiar resultados
                    clearSearchResults();
                });
                
                resultsContainer.appendChild(item);
            });
            
            // Insertar después del campo de búsqueda
            searchInput.parentNode.appendChild(resultsContainer);
        }
        
        // Limpiar resultados de búsqueda
        function clearSearchResults() {
            const existingResults = document.getElementById('search-results');
            if (existingResults) {
                existingResults.remove();
            }
        }
        
        // Cerrar resultados al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && !e.target.closest('#search-results')) {
                clearSearchResults();
            }
        });
        
        // Si ya hay coordenadas guardadas, mostrarlas
        const savedLat = $('#latitude').val();
        const savedLng = $('#longitude').val();
        
        if (savedLat && savedLng && !isNaN(savedLat) && !isNaN(savedLng)) {
            updateMarker(parseFloat(savedLat), parseFloat(savedLng));
        }
        
        // Usar la API de geolocalización del navegador
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                if (!marker) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    map.setView([lat, lng], zoomLevel);
                    updateMarker(lat, lng);
                    reverseGeocode(lat, lng);
                }
            }, function(error) {
                console.warn('Error de geolocalización:', error.message);
            });
        }

        // Validación del formulario antes de enviar
        $('form').on('submit', function(e) {
            if (!$('#latitude').val() || !$('#longitude').val()) {
                e.preventDefault();
                toastr.error('Por favor, seleccione una ubicación en el mapa');
                return false;
            }
            
            // Verificar campos requeridos
            let isValid = true;
            $('input[required], select[required]').each(function() {
                if (!$(this).val()) {
                    const fieldName = $(this).attr('placeholder') || $(this).closest('.wsus__check_single_form').find('label').text() || "Este campo";
                    toastr.error(fieldName + ' es obligatorio');
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            return isValid;
        });
        
        // Evitar que los campos select con la clase select_2 envíen el formulario al presionar Enter
        $(document).on('keydown', '.select_2, #address-search', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
          // Mobile menu handling (mantener funcionalidad existente)
          const mobileMenuToggle = document.getElementById('mobileMenuToggle');
          const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
          
          if (mobileMenuToggle) {
              mobileMenuToggle.addEventListener('click', function() {
                  const sidebar = document.querySelector('.user-sidebar');
                  if (sidebar) {
                      sidebar.classList.toggle('active');
                      mobileMenuOverlay.classList.toggle('active');
                      document.body.classList.toggle('menu-open');
                  }
              });
          }

          if (mobileMenuOverlay) {
              mobileMenuOverlay.addEventListener('click', function() {
                  const sidebar = document.querySelector('.user-sidebar');
                  if (sidebar) {
                      sidebar.classList.remove('active');
                      this.classList.remove('active');
                      document.body.classList.remove('menu-open');
                  }
              });
          }
          const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function() {
                const sidebar = document.querySelector('.user-sidebar');
                const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

                if (sidebar && mobileMenuOverlay) {
                    sidebar.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.classList.remove('menu-open');
                }
            });
        }


      });
    </script>
@endpush
