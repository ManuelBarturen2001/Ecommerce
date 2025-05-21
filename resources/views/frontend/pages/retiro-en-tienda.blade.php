@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
    Nuestras Tiendas
@endsection

<style>
    /* Estilos generales */
    .wsus_menu_category_bar .far{
        line-height: 2.5;
    }

    .wsus_close_mini_cart .far{
        line-height: 1.8;
    }

    .wsus__scroll_btn .fa-chevron-up{
        line-height: 2;
    }
    :root {
        --text-color: #333;
        --light-bg: #f8f9fa;
    }
    
    .tiendas-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .section-header h2 {
        color: var(--general);
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    
    .section-header h2:after {
        content: "";
        position: absolute;
        width: 60%;
        height: 3px;
        background: var(--general);
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .section-header p {
        color: #7f8c8d;
        font-size: 1.1rem;
    }
    
    /* Tarjeta de tiendas */
    .tiendas-card, .mapa-card, .tienda-info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        height: 100%;
    }
    
    .tiendas-card:hover, .mapa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .card-header {
        background: var(--general);
        color: white;
        border-bottom: 0;
        padding: 18px 25px;
    }
    
    .card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.4rem;
    }
    
    .card-body {
        padding: 25px;
    }
    
    /* Buscador de tiendas */
    .buscar-container {
        position: relative;
        margin-bottom: 20px;
    }
    
    #buscar-tienda {
        background-color: #f8f9fa;
        border-radius: 50px;
        padding: 12px 20px 12px 45px;
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }
    
    #buscar-tienda:focus {
        box-shadow: 0 0 0 0.2rem rgba(var(--general-rgb), 0.25);
        border-color: var(--general);
        background-color: #fff;
    }
    
    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #95a5a6;
    }
    
    /* Lista de tiendas */
    .lista-tiendas {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 5px;
    }
    
    .lista-tiendas::-webkit-scrollbar {
        width: 6px;
    }
    
    .lista-tiendas::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .lista-tiendas::-webkit-scrollbar-thumb {
        background: #bdc3c7;
        border-radius: 10px;
    }
    
    .tienda-item {
        background-color: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid transparent;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .tienda-item:hover {
        background-color: #f7fbfe;
        border-left-color: var(--general);
        transform: translateX(5px);
    }
    
    .tienda-item.tienda-activa {
        background-color: rgba(var(--general-rgb), 0.1);
        border-left-color: var(--general);
        transform: translateX(5px);
    }
    
    .tienda-info h5 {
        margin-bottom: 8px;
        color: var(--text-color);
        font-weight: 600;
    }
    
    .tienda-info p {
        margin-bottom: 0;
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .tienda-info i {
        color: var(--general);
        margin-right: 5px;
    }
    
    .tienda-actions {
        margin-left: 10px;
    }
    
    /* Mapa */
    #mapa {
        height: 500px;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Estilos del contenedor principal del popup */
    .leaflet-popup-content-wrapper {
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        padding: 0;
        overflow: hidden;
        background-color: #fff;
    }

    /* Contenido interno del popup */
    .leaflet-popup-content {
        margin: 0;
        width: 240px !important; /* más compacto */
        padding: 0;
    }

    /* Contenido visible personalizado */
    .popup-content {
        padding: 12px 16px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Título */
    .popup-content h5 {
        color: var(--text-color);
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 2px solid var(--general);
    }

    /* Texto general */
    .popup-content p {
        color: #555;
        margin: 5px 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    /* Horario */
    .popup-content .popup-horario {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
    }

    /* Iconos */
    .popup-content i {
        color: var(--general);
        margin-right: 6px;
    }

    /* Botón */
    .popup-content .popup-buttons {
        margin-top: 10px;
        text-align: right;
    }

    .btn-como-llegar {
        background-color: var(--general);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-como-llegar:hover {
        background-color: white;
        color: var(--general);
        border: 1px solid var(--general);
        transform: scale(1.03);
    }

    
    .popup-buttons {
        display: flex;
        justify-content: space-between;
    }
    /* INFORMACION ADICIONAL */
    #tienda-seleccionada-container {
    margin-top: 40px;
    }

    .tienda-info-card {
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .tienda-info-card .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .tienda-info-card .card-body {
        padding: 25px;
    }

    .info-column {
        padding: 15px 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        height: 100%;
        border: 1px solid #e5e5e5;
    }

    .info-column h5 {
        font-size: 1.2rem;
        margin-bottom: 12px;
        color: var(--text-color);
        border-bottom: 2px solid var(--general);
        padding-bottom: 6px;
    }

    .info-column p {
        margin-bottom: 8px;
        font-size: 0.95rem;
        color: #555;
        display: flex;
        align-items: flex-start;
    }

    .info-column p i {
        margin-right: 8px;
        color: var(--general);
        width: 18px;
        text-align: center;
        margin-top: 3px;
    }

    .detalles-section {
        padding: 20px;
        border-radius: 8px;
        background-color: #f8f9fc;
        border-left: 4px solid var(--general);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .detalles-section h5 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .detalles-section p,
    .detalles-section ul li {
        font-size: 0.9rem;
        color: #666;
    }

    .detalles-section i {
        margin-right: 8px;
        color: var(--general);
    }

    .btn-como-llegar {
        background-color: var(--general);
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .btn-como-llegar:hover {
        filter: brightness(90%);
        transform: translateY(-1px);
    }

    
    .tienda-cercana {
        border-left: 4px solid #2ecc71;
        background-color: rgba(46, 204, 113, 0.1);
    }
    
    #detectar-ubicacion {
        background-color: var(--general);
        color: white;
        border: none;
    }
    
    #detectar-ubicacion:hover {
        filter: brightness(90%);
    }
    
    .badge-cercana {
        background-color: #2ecc71;
        color: white;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 10px;
        margin-left: 8px;
        display: inline-block;
    }
    
    /* Resposive */
    @media (max-width: 767px) {
        .tiendas-card, .mapa-card {
            margin-bottom: 20px;
        }
        
        #mapa {
            height: 350px;
        }
        
        .tienda-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .tienda-actions {
            margin-left: 0;
            margin-top: 10px;
            width: 100%;
        }
        
        .info-column {
            margin-bottom: 15px;
        }
    }
</style>

@section('content')
    <!--============================
        PANORAMA DE NAVEGACIÓN INICIO
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>Nuestras Tiendas</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Nuestras Tiendas</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PANORAMA DE NAVEGACIÓN FIN
    ==============================-->
<section class="section tiendas-section">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="card tiendas-card">
                    <div class="card-header">
                        <h4>Tiendas en todo el Perú</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group buscar-container">
                            <input type="text" class="form-control" id="buscar-tienda" placeholder="Buscar tienda por nombre o ubicación...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        <button type="button" class="btn btn-sm mb-3 w-100" id="detectar-ubicacion">
                            <i class="fas fa-location-arrow"></i> Usar mi ubicación
                        </button>
                        <div class="lista-tiendas">
                            @if(count($tiendas) > 0)
                                @foreach($tiendas as $tienda)
                                    <div class="tienda-item" data-lat="{{ $tienda->latitud }}" data-lng="{{ $tienda->longitud }}" data-id="{{ $tienda->id }}">
                                        <div class="tienda-info">
                                            <h5>{{ $tienda->nombre_tienda }}</h5>
                                            <p><i class="fas fa-map-marker-alt"></i> {{ $tienda->direccion }}, {{ $tienda->ciudad }}, Perú</p>
                                            <p><i class="fas fa-clock"></i> {{ $tienda->horario_apertura }} - {{ $tienda->horario_cierre }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    No hay tiendas disponibles en este momento.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card mapa-card">
                    <div class="card-header">
                        <h4>Mapa</h4>
                    </div>
                    <div class="card-body">
                        <div id="mapa"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="tienda-seleccionada-container" style="display: none;">
    <div class="col-12">
        <div class="card tienda-info-card mt-4 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h4 class="mb-0"><i class="fas fa-store-alt me-2"></i>Información Adicional</h4>
            </div>
            <div class="card-body">
                <div class="row" id="tienda-seleccionada-info" class="gy-3">
                    <!-- Información dinámica -->
                </div>
                
                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detalles-section">
                            <h5><i class="fas fa-info-circle"></i> Para el Retiro en Tienda</h5>
                            <p class="text-muted small">Ten en cuenta lo siguiente:</p>
                            <div id="detalles-adicionales"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detalles-section">
                            <h5><i class="fas fa-star"></i> Documentos Requeridos</h5>
                            <div id="servicios-disponibles"></div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn-como-llegar" id="btn-como-llegar">
                        <i class="fas fa-directions me-2"></i> Cómo llegar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</section>
@endsection

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function() {
        // Inicializar el mapa
        var mapa = L.map('mapa');
        var modalMapa = null;
        var tiendaSeleccionadaId = null;
        
        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapa);
        
        var marcadores = [];
        var tiendas = @json($tiendas);
        
        // Si hay tiendas, centrar el mapa en la primera tienda
        if (tiendas.length > 0) {
            mapa.setView([tiendas[0].latitud, tiendas[0].longitud], 13);
            
            // Añadir marcadores para cada tienda usando el marcador predeterminado de Leaflet
            tiendas.forEach(function(tienda) {
                var marcador = L.marker([tienda.latitud, tienda.longitud])
                    .addTo(mapa)
                    .bindPopup(`
                        <div class="popup-content">
                            <h5>${tienda.nombre_tienda}</h5>
                            <p><i class="fas fa-map-marker-alt"></i> ${tienda.direccion}, ${tienda.ciudad}, Perú</p>
                            <p class="popup-horario"><i class="fas fa-clock"></i> ${tienda.horario_apertura} - ${tienda.horario_cierre}</p>
                            <div class="popup-buttons">
                                <a href="https://www.google.com/maps/dir/?api=1&destination=${tienda.latitud},${tienda.longitud}" target="_blank" class="btn btn-sm btn-como-llegar">Cómo llegar</a>
                            </div>
                        </div>
                    `, {
                        minWidth: 300,
                        maxWidth: 300
                    });
                
                marcador.tiendaId = tienda.id;
                marcadores.push(marcador);
                
                // Evento click en marcador
                marcador.on('click', function() {
                    seleccionarTienda(tienda.id);
                });
            });
        } else {
            // Si no hay tiendas, mostrar un mapa genérico de Perú
            mapa.setView([-12.046374, -77.042793], 10); // Coordenadas de Lima, Perú
        }
        
        // Función para seleccionar tienda
        function seleccionarTienda(tiendaId) {
            tiendaSeleccionadaId = tiendaId;
            
            // Animación visual para resaltar la tienda seleccionada
            $('.tienda-item').removeClass('tienda-activa');
            $('.tienda-item[data-id="' + tiendaId + '"]').addClass('tienda-activa');
            
            // Buscar la tienda por ID
            var tiendaSeleccionada = tiendas.find(function(t) {
                return t.id == tiendaId;
            });
            
            if (tiendaSeleccionada) {
                // Animación del mapa
                mapa.flyTo([tiendaSeleccionada.latitud, tiendaSeleccionada.longitud], 16, {
                    duration: 1.5
                });
                
                // Abrir popup
                marcadores.forEach(function(m) {
                    if (m.tiendaId == tiendaId) {
                        m.openPopup();
                    }
                });
                
                // Mostrar información detallada en la sección inferior con animación
                $('#tienda-seleccionada-container').fadeIn(300);
                $('#tienda-seleccionada-info').html(`
                    <div class="col-md-6">
                        <div class="info-column">
                            <h5>${tiendaSeleccionada.nombre_tienda}</h5>
                            <p><i class="fas fa-map-marker-alt"></i> <strong>Dirección:</strong>&nbsp;${tiendaSeleccionada.direccion}, ${tiendaSeleccionada.ciudad}, Perú</p>
                            <p><i class="fas fa-phone"></i> <strong>Teléfono:</strong>&nbsp;${tiendaSeleccionada.telefono || 'No disponible'}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-column">
                            <p><i class="fas fa-clock"></i> <strong>Horario:</strong>&nbsp;${tiendaSeleccionada.horario_apertura} - ${tiendaSeleccionada.horario_cierre}</p>
                            <p><i class="fas fa-calendar"></i> <strong>Días disponibles:</strong>&nbsp;${tiendaSeleccionada.dias_disponibles || 'Lunes a Sábado'}</p>
                        </div>
                    </div>
                `);
                
                // Cargar detalles adicionales y servicios disponibles
                $('#detalles-adicionales').html(`
                    <p>${tiendaSeleccionada.instrucciones_retiro || 'Esta tienda ofrece todos nuestros productos y promociones.'}</p>
                `);
                
                $('#servicios-disponibles').html(`
                    <ul class="list-unstyled">
                        ${tiendaSeleccionada.documentos_requeridos ? '<li><i class="fas fa-check-circle" style="color: var(--general);"></i> ' + tiendaSeleccionada.documentos_requeridos + '</li>' : ''}
                    </ul>
                `);
                
                // Actualizar botón de "Cómo llegar"
                var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${tiendaSeleccionada.latitud},${tiendaSeleccionada.longitud}`;
                $('#btn-como-llegar').attr('onclick', `window.open('${googleMapsUrl}', '_blank')`);
                
                // Scroll suave a la sección de tienda seleccionada
                $('html, body').animate({
                    scrollTop: $("#tienda-seleccionada-container").offset().top - 100
                }, 500);
            }
        }
        
        // Click en item de tienda con efecto visual
        $(document).on('click', '.tienda-item', function() {
            var tiendaId = $(this).data('id');
            $(this).addClass('tienda-activa').siblings().removeClass('tienda-activa');
            seleccionarTienda(tiendaId);
        });
        
        // Búsqueda de tiendas con resaltado
        $('#buscar-tienda').on('keyup', function() {
            var texto = $(this).val().toLowerCase();
            $('.tienda-item').each(function() {
                var nombreTienda = $(this).find('h5').text().toLowerCase();
                var direccion = $(this).find('p').text().toLowerCase();
                
                if (nombreTienda.includes(texto) || direccion.includes(texto)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Si no hay resultados, mostrar mensaje
            if ($('.tienda-item:visible').length === 0) {
                if ($('.no-resultados').length === 0) {
                    $('.lista-tiendas').append('<div class="alert alert-info mt-3 no-resultados">No se encontraron tiendas con ese criterio de búsqueda.</div>');
                }
            } else {
                $('.no-resultados').remove();
            }
        });
        
        // Función para detectar la ubicación del usuario
        function detectarUbicacion() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latUsuario = position.coords.latitude;
                    var lngUsuario = position.coords.longitude;

                    var iconoUsuario = L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // Puedes usar otra URL o archivo local
                        iconSize: [30, 30], // Tamaño del icono
                        iconAnchor: [15, 30], // Punto del icono que se alinea con la ubicación
                        popupAnchor: [0, -30] // Ajuste del popup respecto al icono
                    });
                    
                    // Añadir marcador de la ubicación del usuario
                    var marcadorUsuario = L.marker([latUsuario, lngUsuario], {
                        // Utilizamos el marcador estándar, pero con una clase personalizada
                        // className: 'marcador-usuario'
                        icon: iconoUsuario
                    }).addTo(mapa).bindPopup('Tu ubicación actual').openPopup();
                    
                    // Ajustar el mapa para mostrar tanto la ubicación del usuario como las tiendas
                    var bounds = L.latLngBounds([latUsuario, lngUsuario]);
                    tiendas.forEach(function(tienda) {
                        bounds.extend([tienda.latitud, tienda.longitud]);
                    });
                    mapa.fitBounds(bounds, { padding: [50, 50] });
                    
                    // Calcular tienda más cercana
                    var tiendaMasCercana = null;
                    var distanciaMinima = Infinity;
                    
                    tiendas.forEach(function(tienda) {
                        var distancia = calcularDistancia(
                            latUsuario, lngUsuario,
                            tienda.latitud, tienda.longitud
                        );
                        
                        if (distancia < distanciaMinima) {
                            distanciaMinima = distancia;
                            tiendaMasCercana = tienda;
                        }
                    });
                    
                    if (tiendaMasCercana) {
                        // Resaltar visualmente la tienda más cercana en la lista
                        $('.tienda-item').removeClass('tienda-cercana');
                        $('.badge-cercana').remove();
                        
                        setTimeout(function() {
                            var $tiendaCercana = $('.tienda-item[data-id="' + tiendaMasCercana.id + '"]');
                            $tiendaCercana.addClass('tienda-cercana');
                            $tiendaCercana.find('h5').append('<span class="badge-cercana">Más cercana</span>');
                            
                            // Hacer scroll a la tienda más cercana
                            $('.lista-tiendas').animate({
                                scrollTop: $tiendaCercana.position().top - $('.lista-tiendas').position().top
                            }, 500);
                        }, 1000);
                    }
                }, function(error) {
                    console.log("Error al obtener la ubicación: ", error.message);
                    alert("No se pudo detectar tu ubicación. Verifica los permisos de ubicación en tu navegador.");
                    $('#detectar-ubicacion').html('<i class="fas fa-location-arrow"></i> Usar mi ubicación')
                        .prop('disabled', false)
                        .removeClass('btn-success')
                        .addClass('btn-info');
                });
            } else {
                alert("Tu navegador no soporta geolocalización.");
                $('#detectar-ubicacion').prop('disabled', false);
            }
        }
        
        // Función para calcular la distancia entre dos puntos geográficos (fórmula Haversine)
        function calcularDistancia(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radio de la tierra en km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c; // Distancia en km
        }
        
        // Botón para detectar ubicación
        $('#detectar-ubicacion').on('click', function() {
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Detectando...').prop('disabled', true);
            detectarUbicacion();
            
            // Actualizar estado del botón cuando se detecta la ubicación
            setTimeout(() => {
                if (!$(this).prop('disabled')) return; // Si ya fue actualizado por el error
                $(this).html('<i class="fas fa-check-circle"></i> Ubicación detectada')
                    .prop('disabled', false)
                    .removeClass('btn-info')
                    .addClass('btn-success');
            }, 2000);
        });
        
        // Efecto de hover para tiendas en la lista
        $(document).on('mouseenter', '.tienda-item', function() {
            var tiendaId = $(this).data('id');
            
            // Destacar el marcador correspondiente
            marcadores.forEach(function(m) {
                if (m.tiendaId == tiendaId && m._icon) {
                    m._icon.style.filter = "brightness(1.3)";
                    m._icon.style.transform = m._icon.style.transform + " scale(1.2)";
                    m._icon.style.zIndex = 1000;
                }
            });
        }).on('mouseleave', '.tienda-item', function() {
            // Restaurar todos los marcadores
            marcadores.forEach(function(m) {
                if (m._icon) {
                    m._icon.style.filter = "";
                    m._icon.style.transform = m._icon.style.transform.replace(" scale(1.2)", "");
                    m._icon.style.zIndex = "";
                }
            });
        });
        
        // Ajustar el tamaño del mapa cuando se redimensiona la ventana
        $(window).resize(function() {
            mapa.invalidateSize();
        });
    });
</script>
@endpush