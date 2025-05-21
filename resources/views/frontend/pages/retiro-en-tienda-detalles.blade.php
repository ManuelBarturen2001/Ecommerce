<style>
    /* Estilos generales */
    :root {
        --text-color: #333;
        --light-bg: #f8f9fa;
    }
    
    .tiendas-section {
        padding: 20px 0;
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
        border-bottom: 0;
        padding: 18px 25px;
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
    
    /* Estilos del popup mejorados para dispositivos móviles */
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 0;
        overflow: hidden;
        background-color: #fff;
    }

    /* Contenido interno del popup optimizado */
    .leaflet-popup-content {
        margin: 0;
        width: 150px !important;
        padding: 0;
    }

    /* Contenido visible personalizado - más compacto */
    .popup-content {
        padding: 5px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Título más pequeño */
    .popup-content h5 {
        color: var(--text-color);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 5px;
        padding-bottom: 4px;
        border-bottom: 1px solid var(--general);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Texto general más compacto */
    .popup-content p {
        color: #555;
        margin: 3px 0;
        font-size: 0.75rem;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Horario */
    .popup-content .popup-horario {
        display: flex;
        align-items: center;
        font-size: 0.75rem;
    }

    /* Iconos más pequeños */
    .popup-content i {
        color: var(--general);
        margin-right: 4px;
        font-size: 0.8rem;
    }

    /* Botones más compactos */
    .popup-content .popup-buttons {
        margin-top: 6px;
        text-align: right;
    }

    .btn-como-llegar {
        background-color: var(--general);
        color: white;
        border: none;
        transition: all 0.3s ease;
        padding: 4px 8px;
        font-size: 0.75rem;
    }

    .btn-como-llegar:hover {
        background-color: white;
        color: var(--general);
        border: 1px solid var(--general);
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
    
    /* Responsive mejorado */
    @media (max-width: 767px) {
        .tiendas-card, .mapa-card {
            margin-bottom: 15px;
        }
        
        #mapa {
            height: 300px;
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
        
        /* Popup aún más compacto en móviles */
        .leaflet-popup-content {
            margin: 0;
            width: 120px !important;
            padding: 0;
        }
        
        .popup-content h5 {
            font-size: 0.85rem;
        }
        
        .popup-content p {
            font-size: 0.7rem;
            margin: 2px 0;
        }
        
        .popup-content .popup-buttons {
            margin-top: 5px;
        }
        
        .btn-como-llegar {
            padding: 3px 6px;
            font-size: 0.7rem;
        }
        
        .card-body {
            padding: 15px;
        }
    }
    
    /* Estilos para tablets */
    @media (min-width: 768px) and (max-width: 991px) {
        #mapa {
            height: 400px;
        }
        
        .leaflet-popup-content {
            width: 190px !important;
        }
        
        .popup-content h5 {
            font-size: 0.85rem;
        }
        
        .popup-content p {
            font-size: 0.75rem;
        }
    }
</style>


<section class="section tiendas-section">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="card tiendas-card">
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
                                            <p><i class="fas fa-phone"></i> {{ $tienda->telefono }}</p>
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
                    <div class="card-body">
                        <div id="mapa"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="tienda-seleccionada-container" style="display: none;">
            <div class="col-12">
                <div class="card tienda-info-card mt-1 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h4 class="mb-0"><i class="fas fa-store-alt me-2"></i>Información Adicional</h4>
                    </div>
                    <div class="card-body">
                        <div class="row" id="tienda-seleccionada-info" class="gy-3">
                            <!-- Información dinámica -->
                        </div>
                        
                        <div class="row g-12">
                            <div class="col-md-12">
                                <div class="detalles-section">
                                    <h5><i class="fas fa-info-circle"></i> Para el Retiro en Tienda</h5>
                                    <p class="text-muted small">Ten en cuenta lo siguiente:</p>
                                    <div id="detalles-adicionales"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="detalles-section">
                                    <h5><i class="fas fa-star"></i> Documentos Requeridos</h5>
                                    <div id="servicios-disponibles"></div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn-como-llegar" id="btn-como-llegar">
                                Seleccionar Esta Tienda
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




@push('scripts')

<script>
    $(document).ready(function() {
        // Create a namespace for store pickup map functionality to avoid conflicts
        window.storePickupMapManager = {
            mapa: null,
            marcadores: [],
            tiendaSeleccionadaId: null,
            tiendas: @json($tiendas),
            
            // Initialize the store pickup map
            initializeMap: function() {
                // Only initialize if the map container exists and the map doesn't already exist
                if ($('#mapa').length === 0 || this.mapa !== null) return;
                
                // Initialize the map
                this.mapa = L.map('mapa');
                
                // Add OpenStreetMap tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.mapa);
                
                // Clear existing markers if any
                this.marcadores.forEach(function(marker) {
                    marker.remove();
                });
                this.marcadores = [];
                
                // If there are stores, center the map on the first store
                if (this.tiendas.length > 0) {
                    this.mapa.setView([this.tiendas[0].latitud, this.tiendas[0].longitud], 13);
                    
                    // Add markers for each store
                    var self = this;
                    this.tiendas.forEach(function(tienda) {
                        var marcador = L.marker([tienda.latitud, tienda.longitud])
                            .addTo(self.mapa)
                            .bindPopup(`
                                <div class="popup-content">
                                    <h5>${tienda.nombre_tienda}</h5>
                                    <p><i class="fas fa-map-marker-alt"></i> ${tienda.direccion}</p>
                                    <p class="popup-horario"><i class="fas fa-clock"></i> ${tienda.horario_apertura} - ${tienda.horario_cierre}</p>
                                    <p><i class="fas fa-calendar"></i> ${tienda.dias_disponibles}</p>
                                    <div>
                                        <a href="https://www.google.com/maps/dir/?api=1&destination=${tienda.latitud},${tienda.longitud}" target="_blank" class="btn-como-llegar"><i class="fas fa-directions me-2"></i>Cómo llegar</a>
                                    </div>
                                </div>
                            `, {
                                minWidth: 300,
                                maxWidth: 300
                            });

                        marcador.tiendaId = tienda.id;
                        self.marcadores.push(marcador);
                        
                        // Click event on marker
                        marcador.on('click', function() {
                            self.seleccionarTienda(tienda.id);
                        });
                    });
                } else {
                    // If there are no stores, show a generic map of Peru
                    this.mapa.setView([-12.046374, -77.042793], 10); // Lima, Peru coordinates
                }
                
                // Fix map size after initialization
                setTimeout(function() {
                    if (self.mapa) self.mapa.invalidateSize();
                }, 300);
            },
            
            // Function to select a store
            seleccionarTienda: function(tiendaId) {
                this.tiendaSeleccionadaId = tiendaId;
                
                // Visual animation to highlight the selected store
                $('.tienda-item').removeClass('tienda-activa');
                $('.tienda-item[data-id="' + tiendaId + '"]').addClass('tienda-activa');
                
                // Find the store by ID
                var tiendaSeleccionada = this.tiendas.find(function(t) {
                    return t.id == tiendaId;
                });
                
                if (tiendaSeleccionada && this.mapa) {
                    // Map animation
                    this.mapa.flyTo([tiendaSeleccionada.latitud, tiendaSeleccionada.longitud], 16, {
                        duration: 1.5
                    });
                    
                    // Open popup
                    this.marcadores.forEach(function(m) {
                        if (m.tiendaId == tiendaId) {
                            m.openPopup();
                        }
                    });
                    
                    // Show detailed information in the lower section with animation
                    $('#tienda-seleccionada-container').fadeIn(300);
                    
                    // Load additional details and available services
                    $('#detalles-adicionales').html(`
                        <p>${tiendaSeleccionada.instrucciones_retiro || 'Esta tienda ofrece todos nuestros productos y promociones.'}</p>
                    `);
                    
                    $('#servicios-disponibles').html(`
                        <ul class="list-unstyled">
                            ${tiendaSeleccionada.documentos_requeridos ? '<li><i class="fas fa-check-circle" style="color: var(--general);"></i> ' + tiendaSeleccionada.documentos_requeridos + '</li>' : ''}
                        </ul>
                    `);
                    
                    // Update "How to get there" button
                    var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${tiendaSeleccionada.latitud},${tiendaSeleccionada.longitud}`;
                    $('#btn-como-llegar').attr('onclick', `window.open('${googleMapsUrl}', '_blank')`);
                    
                    // Smooth scroll to the selected store section
                    $('html, body').animate({
                        scrollTop: $("#tienda-seleccionada-container").offset().top - 100
                    }, 500);
                }
            },
            
            // Function to detect user location
            detectarUbicacion: function() {
                var self = this;
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latUsuario = position.coords.latitude;
                        var lngUsuario = position.coords.longitude;

                        var iconoUsuario = L.icon({
                            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                            iconSize: [30, 30],
                            iconAnchor: [15, 30],
                            popupAnchor: [0, -30]
                        });
                        
                        // Add marker for user location
                        var marcadorUsuario = L.marker([latUsuario, lngUsuario], {
                            icon: iconoUsuario
                        }).addTo(self.mapa).bindPopup('Tu ubicación actual').openPopup();
                        
                        // Adjust the map to show both the user location and the stores
                        var bounds = L.latLngBounds([latUsuario, lngUsuario]);
                        self.tiendas.forEach(function(tienda) {
                            bounds.extend([tienda.latitud, tienda.longitud]);
                        });
                        self.mapa.fitBounds(bounds, { padding: [50, 50] });
                        
                        // Calculate the nearest store
                        var tiendaMasCercana = null;
                        var distanciaMinima = Infinity;
                        
                        self.tiendas.forEach(function(tienda) {
                            var distancia = self.calcularDistancia(
                                latUsuario, lngUsuario,
                                tienda.latitud, tienda.longitud
                            );
                            
                            if (distancia < distanciaMinima) {
                                distanciaMinima = distancia;
                                tiendaMasCercana = tienda;
                            }
                        });
                        
                        if (tiendaMasCercana) {
                            // Visually highlight the closest store in the list
                            $('.tienda-item').removeClass('tienda-cercana');
                            $('.badge-cercana').remove();
                            
                            setTimeout(function() {
                                var $tiendaCercana = $('.tienda-item[data-id="' + tiendaMasCercana.id + '"]');
                                $tiendaCercana.addClass('tienda-cercana');
                                $tiendaCercana.find('h5').append('<span class="badge-cercana">Más cercana</span>');
                                
                                // Scroll to the nearest store
                                $('.lista-tiendas').animate({
                                    scrollTop: $tiendaCercana.position().top - $('.lista-tiendas').position().top
                                }, 500);
                            }, 1000);
                        }
                        
                        // Update button status
                        $('#detectar-ubicacion').html('<i class="fas fa-check-circle"></i> Ubicación detectada')
                            .prop('disabled', false)
                            .removeClass('btn-info')
                            .addClass('btn-success');
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
            },
            
            // Function to calculate distance between two geographic points (Haversine formula)
            calcularDistancia: function(lat1, lon1, lat2, lon2) {
                const R = 6371; // Earth radius in km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c; // Distance in km
            }
        };
        
        // Setup event handlers for store pickup map
        function setupStorePickupEvents() {
            // Click on store item with visual effect
            $(document).on('click', '.tienda-item', function() {
                if (!window.storePickupMapManager.mapa) return;
                
                var tiendaId = $(this).data('id');
                $(this).addClass('tienda-activa').siblings().removeClass('tienda-activa');
                window.storePickupMapManager.seleccionarTienda(tiendaId);
            });
            
            // Store search with highlighting
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
                
                // If no results, show message
                if ($('.tienda-item:visible').length === 0) {
                    if ($('.no-resultados').length === 0) {
                        $('.lista-tiendas').append('<div class="alert alert-info mt-3 no-resultados">No se encontraron tiendas con ese criterio de búsqueda.</div>');
                    }
                } else {
                    $('.no-resultados').remove();
                }
            });
            
            // Button to detect location
            $('#detectar-ubicacion').on('click', function() {
                $(this).html('<i class="fas fa-spinner fa-spin"></i> Detectando...').prop('disabled', true);
                window.storePickupMapManager.detectarUbicacion();
            });
            
            // Hover effect for stores in the list
            $(document).on('mouseenter', '.tienda-item', function() {
                if (!window.storePickupMapManager.mapa) return;
                
                var tiendaId = $(this).data('id');
                
                // Highlight the corresponding marker
                window.storePickupMapManager.marcadores.forEach(function(m) {
                    if (m.tiendaId == tiendaId && m._icon) {
                        m._icon.style.filter = "brightness(1.3)";
                        m._icon.style.transform = m._icon.style.transform + " scale(1.2)";
                        m._icon.style.zIndex = 1000;
                    }
                });
            }).on('mouseleave', '.tienda-item', function() {
                if (!window.storePickupMapManager.mapa) return;
                
                // Restore all markers
                window.storePickupMapManager.marcadores.forEach(function(m) {
                    if (m._icon) {
                        m._icon.style.filter = "";
                        m._icon.style.transform = m._icon.style.transform.replace(" scale(1.2)", "");
                        m._icon.style.zIndex = "";
                    }
                });
            });
        }
        
        // Call setup for store pickup events
        setupStorePickupEvents();
    });
</script>
@endpush