@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
{{$settings->site_name}} || Finalizar compra
@endsection

@section('content')

    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>Entrega</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('cart-details')}}">Vista del Carrito</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Entrega</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="wsus__cart_view">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="wsus__check_form">
                            <div class="d-flex">
                                <h5>Detalles de envío</h5>
                            <a href="javascript:;" style="margin-left:auto;" class="common_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">Agregar
                                nueva dirección</a>
                            </div>

                            <div class="row">
                                @foreach ($addresses as $address)
                                <div class="col-xl-6">
                                    <div class="wsus__checkout_single_address p-3 border rounded shadow-sm">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input shipping_address" 
                                                data-id="{{$address->id}}" 
                                                data-lat="{{$address->latitude ?? ''}}" 
                                                data-lng="{{$address->longitude ?? ''}}" 
                                                type="radio" 
                                                name="flexRadioDefault" 
                                                id="address_{{$address->id}}">
                                            <label class="form-check-label ms-2" for="address_{{$address->id}}">
                                                Seleccionar esta dirección
                                            </label>
                                        </div>

                                        <table class="table table-sm mb-0">
                                            <tbody>
                                                <tr><th>Nombre</th><td>{{ $address->name }}</td></tr>
                                                <tr><th>Teléfono</th><td>{{ $address->phone }}</td></tr>
                                                <tr><th>Correo</th><td>{{ $address->email }}</td></tr>
                                                <tr><th>Departamento</th><td>{{ $address->departamento->nombre ?? '' }}</td></tr>
                                                <tr><th>Provincia</th><td>{{ $address->provincia->nombre ?? '' }}</td></tr>
                                                <tr><th>Distrito</th><td>{{ $address->distrito->nombre ?? '' }}</td></tr>
                                                <tr><th>Código Postal</th><td>{{ $address->zip }}</td></tr>
                                                <tr><th>Dirección</th><td>{{ $address->address }}</td></tr>
                                                <tr>
                                                    <th>GPS</th>
                                                    <td>
                                                        @if($address->latitude && $address->longitude)
                                                            <span class="text-success">Ubicación GPS registrada</span>
                                                        @else
                                                            <span class="text-danger">Sin ubicación GPS</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <!-- Botones de edición y eliminación -->
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <button class="btn btn-sm btn-edit-address edit-address-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editAddressModal" 
                                                data-id="{{ $address->id }}">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </button>

                                            <button class="btn btn-sm btn-delete-address delete-address-btn" 
                                                data-id="{{ $address->id }}">
                                                <i class="fas fa-trash me-1"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                                    
                            
                            <!-- Mapa para visualizar la ubicación -->
                            <div class="row mt-4" id="mapContainer" style="display: none;">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Ubicación de entrega</h5>
                                            <p class="text-muted mb-0">Distancia: <span id="distance">0</span> km</p>
                                        </div>
                                        <div class="card-body">
                                            <div id="map" style="height: 400px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <div class="row mt-0" id="storePickupInfo" style="display: none;">
                            <div class="col-12">
                                <div class="card shadow-sm border-0 rounded-4">
                                    <div class="card-header rounded-top-4" style="background-color: var(--general);">
                                        <h5 class="mb-0" style="color: #ffffff"><i class="fas fa-store me-2"></i> Recojo en tienda</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <!-- Sección de retiro en tienda -->
                                        <div class="tiendas-section">
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
                                                                @php
                                                                    use App\Models\RetiroTienda;
                                                                    $tiendasRetiro = RetiroTienda::where('estado', 1)->orderBy('nombre_tienda', 'asc')->get();
                                                                @endphp
                                                                @if(count($tiendasRetiro) > 0)
                                                                    @foreach($tiendasRetiro as $tienda)
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
                                                                <button type="button" class="btn btn-primary" id="btn-como-llegar">
                                                                    Seleccionar Esta Tienda
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="wsus__order_details" id="sticky_sidebar">
                            <p class="wsus__product">Métodos de entrega</p>
                            @foreach ($shippingMethods as $method)
                                @if ($method->type === 'min_cost' && getCartTotal() >= $method->min_cost)
                                    <div class="form-check">
                                        <input class="form-check-input shipping_method" type="radio" name="exampleRadios" id="method_{{$method->id}}"
                                            value="{{$method->id}}" data-id="{{$method->cost}}" data-type="{{$method->type}}" 
                                            data-pickup="{{strtolower($method->name) === 'recojo en tienda' || strtolower($method->name) === 'retiro en tienda' ? 'true' : 'false'}}">
                                        <label class="form-check-label" style="color: #000000" for="method_{{$method->id}}">
                                            {{$method->name}}
                                            <span>costo: ({{$settings->currency_icon}}{{$method->cost}})</span>
                                        </label>
                                    </div>
                                @elseif ($method->type === 'flat_cost')
                                    <div class="form-check">
                                        <input class="form-check-input shipping_method" type="radio" name="exampleRadios" id="method_{{$method->id}}"
                                            value="{{$method->id}}" data-id="{{$method->cost}}" data-type="{{$method->type}}"
                                            data-pickup="{{strtolower($method->name) === 'recojo en tienda' || strtolower($method->name) === 'retiro en tienda' ? 'true' : 'false'}}">
                                        <label style="color: #000000" class="form-check-label" for="method_{{$method->id}}">
                                            {{$method->name}}
                                            <span id="delivery_cost_{{$method->id}}">costo: ({{$settings->currency_icon}}{{$method->cost}})</span>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                            
                            <!-- Formulario actualizado para incluir flag de recojo en tienda -->
                            <div class="wsus__order_details_summery">
                                <p style="color: #000000">subtotal: <span style="color: #6c757d">{{$settings->currency_icon}}{{getCartTotal()}}</span></p>
                                <p style="color: #000000">gastos de envío (+): <span style="color: #6c757d" id="shipping_fee">{{$settings->currency_icon}}0</span></p>
                                <p style="color: #000000">cupón (-): <span style="color: #6c757d">{{$settings->currency_icon}}{{getCartDiscount()}}</span></p>
                                <p style="color: #000000"><b>total:</b> <span style="color: #6c757d"><b id="total_amount" data-id="{{getMainCartTotal()}}">{{$settings->currency_icon}}{{getMainCartTotal()}}</b></span></p>
                            </div>
                            <div class="terms_area">
                                <div class="form-check">
                                    <input class="form-check-input agree_term" type="checkbox" value="" id="flexCheckChecked3"
                                        checked>
                                    <label style="color: #000000" class="form-check-label" for="flexCheckChecked3">
                                        He leído y acepto los <a href="#" style="color: #000000">términos y condiciones *</a> del sitio web
                                    </label>
                                </div>
                            </div>
                            <form action="" id="checkOutForm">
                                <input type="hidden" name="shipping_method_id" value="" id="shipping_method_id">
                                <input type="hidden" name="shipping_address_id" value="" id="shipping_address_id">
                                <input type="hidden" name="delivery_fee" value="0" id="delivery_fee">
                                <input type="hidden" name="is_pickup" value="0" id="is_pickup">
                                <input type="hidden" name="store_pickup_id" value="" id="store_pickup_id">
                            </form>
                            <a href="" id="submitCheckoutForm" class="common_btn">Realizar pedido</a>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- MODAL PARA AGREGAR DIRECCION -->
    <div class="wsus__popup_address">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar nueva dirección</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="wsus__check_form p-3">
                            <form action="{{route('user.checkout.address.create')}}" method="POST">
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
                                                <div id="address_map" style="height: 400px;"></div>
                                                <input type="hidden" name="latitude" id="latitude">
                                                <input type="hidden" name="longitude" id="longitude">
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

    <!-- MODAL PARA EDITAR DIRECCIÓN -->

    <div class="wsus__popup_address">
        <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAddressModalLabel">Editar dirección</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="wsus__check_form p-3">
                            <form id="editAddressForm" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="edit_address_id" name="address_id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Nombre *" id="edit_name" name="name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Teléfono *" id="edit_phone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="email" placeholder="Correo *" id="edit_email" name="email">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <select class="select_2" name="dep_id" id="edit_departamento_id">
                                                <option value="">Seleccione un departamento *</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <select class="select_2" name="prov_id" id="edit_provincia_id">
                                                <option value="">Seleccione una provincia *</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <select class="select_2" name="dist_id" id="edit_distrito_id">
                                                <option value="">Seleccione un distrito *</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Código Postal *" id="edit_zip" name="zip">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Dirección *" id="edit_address" name="address">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mt-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Seleccione su ubicación en el mapa</h5>
                                            </div>
                                            <div class="card-body">
                                                <div id="edit_address_map" style="height: 400px;"></div>
                                                <input type="hidden" name="latitude" id="edit_latitude">
                                                <input type="hidden" name="longitude" id="edit_longitude">
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

    <!--============================
        PÁGINA DE FINALIZAR COMPRA FIN
    ==============================-->
@endsection


<style>

    .wsus_menu_category_bar .far{
        line-height: 2.5;
    }

    .wsus_close_mini_cart .far{
        line-height: 1.8;
    }

    .wsus__scroll_btn .fa-chevron-up{
        line-height: 2;
    }
    
    .store-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: all 0.2s;
    }
    .store-item:hover {
        background-color: #f8f9fa;
    }
    .store-item.active {
        background-color: rgba(var(--general-rgb), 0.1);
        border-left: 3px solid var(--general);
    }
    .store-item h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    .store-item p {
        margin-bottom: 3px;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .store-badge-near {
        background-color: var(--general);
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 5px;
        font-weight: 400;
    }
    /* Estilos para el retiro en tienda */
    .tiendas-section {
        padding: 0;
    }
    .tiendas-card, .mapa-card {
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
    #mapa {
        height: 500px;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 0;
        overflow: hidden;
        background-color: #fff;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 150px !important;
        padding: 0;
    }
    .popup-content {
        padding: 5px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
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
    .popup-content p {
        color: #555;
        margin: 3px 0;
        font-size: 0.75rem;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .popup-content .popup-horario {
        display: flex;
        align-items: center;
        font-size: 0.75rem;
    }
    .popup-content i {
        color: var(--general);
        margin-right: 4px;
        font-size: 0.8rem;
    }
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


@push('scripts')
<!-- Incluir Leaflet CSS y JavaScript -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Coordenadas de la tienda
        const storeLatitude = {{ $settings->latitude ?? 0 }};
        const storeLongitude = {{ $settings->longitude ?? 0 }};
        
        // Variables para los mapas
        let map, addressMap, marker, addressMarker;
        let selectedLat = null;
        let selectedLng = null;
        let editAddressMap, editAddressMarker;
        
        // Inicializar el mapa para selección de dirección
        function initAddressMap() {
            // Si ya existe el mapa, eliminarlo para evitar duplicados
            if (addressMap) {
                addressMap.remove();
            }
            
            // Crear mapa centrado en las coordenadas de la tienda o en un lugar por defecto
            addressMap = L.map('address_map').setView([storeLatitude || -12.046374, storeLongitude || -77.042793], 15);
            
            // Añadir capa base de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(addressMap);
            
            // Crear marcador movible
            addressMarker = L.marker([storeLatitude || -12.046374, storeLongitude || -77.042793], {
                draggable: true,
                title: 'Arrastre para seleccionar su ubicación'
            }).addTo(addressMap);
            
            // Actualizar coordenadas al mover el marcador
            addressMarker.on('dragend', function(event) {
                const position = addressMarker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });
            
            // Permitir al usuario hacer click en el mapa para colocar el marcador
            addressMap.on('click', function(event) {
                addressMarker.setLatLng(event.latlng);
                document.getElementById('latitude').value = event.latlng.lat;
                document.getElementById('longitude').value = event.latlng.lng;
            });
            
            // Corregir el tamaño del mapa después de cargar
            setTimeout(function() {
                addressMap.invalidateSize();
            }, 100);
        }

        function initEditAddressMap(lat, lng) {
            // Si ya existe el mapa, eliminarlo para evitar duplicados
            if (editAddressMap) {
                editAddressMap.remove();
            }
            
            // Crear mapa centrado en las coordenadas proporcionadas o en un lugar por defecto
            editAddressMap = L.map('edit_address_map').setView([
                lat || storeLatitude || -12.046374, 
                lng || storeLongitude || -77.042793
            ], 15);
            
            // Añadir capa base de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(editAddressMap);
            
            // Crear marcador movible
            editAddressMarker = L.marker([
                lat || storeLatitude || -12.046374, 
                lng || storeLongitude || -77.042793
            ], {
                draggable: true,
                title: 'Arrastre para seleccionar su ubicación'
            }).addTo(editAddressMap);
            
            // Actualizar coordenadas al mover el marcador
            editAddressMarker.on('dragend', function(event) {
                const position = editAddressMarker.getLatLng();
                document.getElementById('edit_latitude').value = position.lat;
                document.getElementById('edit_longitude').value = position.lng;
            });
            
            // Permitir al usuario hacer click en el mapa para colocar el marcador
            editAddressMap.on('click', function(event) {
                editAddressMarker.setLatLng(event.latlng);
                document.getElementById('edit_latitude').value = event.latlng.lat;
                document.getElementById('edit_longitude').value = event.latlng.lng;
            });
            
            // Corregir el tamaño del mapa después de cargar
            setTimeout(function() {
                editAddressMap.invalidateSize();
            }, 100);
        }
        
        // Inicializar el mapa de visualización de dirección
        function initMap(lat, lng) {
            if (!lat || !lng) {
                $('#mapContainer').hide();
                return;
            }
            
            $('#mapContainer').show();
            
            // Si ya existe el mapa, eliminarlo para evitar duplicados
            if (map) {
                map.remove();
            }
            
            // Crear mapa
            map = L.map('map').setView([lat, lng], 12);
            
            // Añadir capa base de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Marcador para la ubicación de entrega (rojo por defecto)
            marker = L.marker([lat, lng], {
                title: 'Su ubicación'
            }).addTo(map);
            
            // Marcador para la tienda (azul)
            const storeIcon = L.icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            const storeMarker = L.marker([storeLatitude, storeLongitude], {
                title: 'Nuestra tienda',
                icon: storeIcon
            }).addTo(map);
            
            // Dibujar línea entre los dos puntos
            const polyline = L.polyline([
                [storeLatitude, storeLongitude],
                [lat, lng]
            ], {
                color: 'red',
                weight: 3,
                opacity: 0.7
            }).addTo(map);
            
            // Ajustar el mapa para mostrar ambos marcadores
            const bounds = L.latLngBounds([
                [storeLatitude, storeLongitude],
                [lat, lng]
            ]);
            map.fitBounds(bounds);
            
            // Corregir el tamaño del mapa después de cargar
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        }
        
        // Calcular tarifa de envío
        function calculateDeliveryFee(shippingMethodId, lat, lng) { 
            if (!lat || !lng || !shippingMethodId) return;
            
            $.ajax({
                url: "{{ route('user.checkout.calculate-delivery-fee') }}",
                method: 'POST',
                data: {
                    shipping_method_id: shippingMethodId,
                    latitude: lat,
                    longitude: lng
                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                success: function(response) {
                    const fee = response.fee;
                    const distance = response.distance;
                    
                    $('#shipping_fee').text("{{$settings->currency_icon}}" + fee);
                    $('#delivery_fee').val(fee);
                    $('#distance').text(distance);
                    
                    // Actualizar el costo total
                    let currentTotalAmount = parseFloat($('#total_amount').data('id'));
                    let totalAmount = currentTotalAmount + parseFloat(fee);
                    $('#total_amount').text("{{$settings->currency_icon}}" + totalAmount.toFixed(2));
                    
                    // Actualizar la etiqueta del método de envío
                    $('#delivery_cost_' + shippingMethodId).html('costo: ({{$settings->currency_icon}}' + fee + ') - ' + distance + ' km');
                },
                error: function(error) {
                    console.error('Error al calcular la tarifa de envío:', error);
                    toastr.error('Error al calcular la tarifa de envío');
                }
            });
        }
        
        // Inicializar geocodificación para búsqueda de direcciones
        function setupGeocoding() {
            // Agregar campo de búsqueda si no existe
            if ($('.geocode-search').length === 0) {
                $('#address_map').before('<div class="form-group mb-3"><input type="text" class="form-control geocode-search" placeholder="Buscar dirección..."></div>');
                $('#edit_address_map').before('<div class="form-group mb-3"><input type="text" class="form-control edit-geocode-search" placeholder="Buscar dirección..."></div>');
            }
            
            // Función para realizar la geocodificación usando Nominatim (servicio de OpenStreetMap)
            function geocodeAddress(query, isEdit) {
                if (!query.trim()) return;
                
                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/search',
                    type: 'GET',
                    data: {
                        q: query,
                        format: 'json',
                        limit: 1
                    },
                    success: function(data) {
                        if (data && data.length > 0) {
                            const result = data[0];
                            const lat = parseFloat(result.lat);
                            const lng = parseFloat(result.lon);
                            
                            if (isEdit) {
                                // Actualizar mapa de edición
                                editAddressMarker.setLatLng([lat, lng]);
                                editAddressMap.setView([lat, lng], 15);
                                $('#edit_latitude').val(lat);
                                $('#edit_longitude').val(lng);
                            } else {
                                // Actualizar mapa de creación
                                addressMarker.setLatLng([lat, lng]);
                                addressMap.setView([lat, lng], 15);
                                $('#latitude').val(lat);
                                $('#longitude').val(lng);
                            }
                        } else {
                            toastr.warning('No se encontraron resultados para esta dirección');
                        }
                    },
                    error: function() {
                        toastr.error('Error al buscar la dirección');
                    }
                });
            }
            
            // Configurar eventos de búsqueda
            $(document).on('keypress', '.geocode-search', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    geocodeAddress($(this).val(), false);
                }
            });
            
            $(document).on('keypress', '.edit-geocode-search', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    geocodeAddress($(this).val(), true);
                }
            });
        }
        
        // Inicializar mapa de dirección cuando se abre el modal
        $('#exampleModal').on('shown.bs.modal', function () {
            setTimeout(function() {
                initAddressMap();
                setupGeocoding();
            }, 500);
        });
        
        // Inicializar mapa de edición cuando se abre el modal
        $('#editAddressModal').on('shown.bs.modal', function () {
            setTimeout(function() {
                setupGeocoding();
            }, 500);
        });
        
        // Resetear el formulario
        $('input[type="radio"]').prop('checked', false);
        $('#shipping_method_id').val("");
        $('#shipping_address_id').val("");
        $('#delivery_fee').val("0");

        // Al seleccionar un método de envío
        $('.shipping_method').on('click', function(){
            const shippingMethodId = $(this).val();
            const shippingFee = $(this).data('id');
            const shippingType = $(this).data('type');
            const isPickup = $(this).data('pickup') === true;
            let currentTotalAmount = parseFloat($('#total_amount').data('id'));
            
            $('#shipping_method_id').val(shippingMethodId);
            $('#is_pickup').val(isPickup ? 1 : 0);
            
            // Mostrar u ocultar secciones según el método de envío
            if (isPickup) {
                // Es recojo en tienda, ocultar sección de direcciones y mostrar info de tienda
                $('.wsus__check_form').hide();
                $('#mapContainer').hide();
                $('#storePickupInfo').show();
                $('#shipping_fee').text("{{$settings->currency_icon}}0");
                $('#delivery_fee').val(0);

                // Obtener el total original del carrito desde data-id
                let originalTotal = parseFloat($('#total_amount').data('id'));
                $('#total_amount').text("{{$settings->currency_icon}}" + originalTotal.toFixed(2));
                
                // Initialize the store pickup map after showing the container
                setTimeout(function() {
                    if (window.storePickupMapManager && typeof window.storePickupMapManager.initializeMap === 'function') {
                        window.storePickupMapManager.initializeMap();
                    }
                }, 500);
                
                // No necesitamos una dirección de envío para recojo en tienda
                $('#shipping_address_id').val("0");
                
                // Recuperar tienda seleccionada del localStorage si existe
                const savedStoreId = localStorage.getItem('selected_store_id');
                if (savedStoreId) {
                    $('#store_pickup_id').val(savedStoreId);
                }
            } else {
                // Es envío a domicilio, mostrar sección de direcciones y ocultar info de tienda
                $('.wsus__check_form').show();
                $('#storePickupInfo').hide();
                $('#store_pickup_id').val("");  // Limpiar el ID de la tienda seleccionada

                // Si hay dirección seleccionada, simular click para reactivar mapa y tarifa
                const selectedAddress = $('.shipping_address:checked');
                if (selectedAddress.length > 0) {
                    selectedAddress.trigger('click');
                }
                // Si hay dirección seleccionada con coordenadas y es flat_cost (envío por distancia)
                if (shippingType === 'flat_cost' && selectedLat && selectedLng) {
                    calculateDeliveryFee(shippingMethodId, selectedLat, selectedLng);
                } else {
                    // Para otros métodos de envío o sin coordenadas
                    $('#shipping_fee').text("{{$settings->currency_icon}}" + shippingFee);
                    $('#delivery_fee').val(shippingFee);
                    let totalAmount = currentTotalAmount + parseFloat(shippingFee);
                    $('#total_amount').text("{{$settings->currency_icon}}" + totalAmount.toFixed(2));
                }
            }
        });

        $(window).resize(function() {
            if (window.storePickupMapManager && window.storePickupMapManager.mapa) {
                window.storePickupMapManager.mapa.invalidateSize();
            }
            
            if (map) map.invalidateSize();
            if (addressMap) addressMap.invalidateSize();
            if (editAddressMap) editAddressMap.invalidateSize();
        });

        // Al seleccionar una dirección
        $('.shipping_address').on('click', function(){
            const addressId = $(this).data('id');
            const lat = $(this).data('lat');
            const lng = $(this).data('lng');
            
            $('#shipping_address_id').val(addressId);
            
            // Guardar las coordenadas seleccionadas
            selectedLat = lat;
            selectedLng = lng;
            
            // Inicializar el mapa si hay coordenadas
            if (lat && lng) {
                initMap(lat, lng);
                
                // Si ya hay un método de envío seleccionado, recalcular la tarifa
                const shippingMethodId = $('#shipping_method_id').val();
                if (shippingMethodId) {
                    const shippingMethod = $('.shipping_method:checked');
                    if (shippingMethod.length > 0 && shippingMethod.data('type') === 'flat_cost') {
                        calculateDeliveryFee(shippingMethodId, lat, lng);
                    }
                }
            } else {
                $('#mapContainer').hide();
            }
        });

        // Lógica para cargar provincias al seleccionar departamento
        $('#departamento_id').on('change', function() {
            const departamentoId = $(this).val();
            
            if (departamentoId) {
                // Habilitar y cargar las provincias
                $('#provincia_id').prop('disabled', false);
                $('#provincia_id').html('<option value="">Cargando provincias...</option>');
                $.ajax({
                    url: "{{ route('user.checkout.get-provincias') }}",
                    type: "POST",
                    data: {
                        dep_id: departamentoId
                    },
                    headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                    success: function(data) {
                        $('#provincia_id').empty();
                        $('#provincia_id').append('<option value="">Seleccione una provincia *</option>');
                        
                        $.each(data, function(key, value) {
                            $('#provincia_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        
                        // Resetear y deshabilitar el selector de distritos
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
                // Si no hay departamento seleccionado, deshabilitar los otros selectores
                $('#provincia_id').prop('disabled', true);
                $('#provincia_id').empty();
                $('#provincia_id').append('<option value="">Seleccione una provincia *</option>');
                
                $('#distrito_id').prop('disabled', true);
                $('#distrito_id').empty();
                $('#distrito_id').append('<option value="">Seleccione un distrito *</option>');
            }
        });
        
        // Lógica para cargar distritos al seleccionar provincia
        $('#provincia_id').on('change', function() {
            const provinciaId = $(this).val();
            
            if (provinciaId) {
                // Habilitar y cargar los distritos
                $('#distrito_id').prop('disabled', false);
                $('#distrito_id').html('<option value="">Cargando distritos...</option>');
                $.ajax({
                    url: "{{ route('user.checkout.get-distritos') }}",
                    type: "POST",
                    data: {
                        prov_id: provinciaId
                    },
                    headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                // Si no hay provincia seleccionada, deshabilitar el selector de distritos
                $('#distrito_id').prop('disabled', true);
                $('#distrito_id').empty();
                $('#distrito_id').append('<option value="">Seleccione un distrito *</option>');
            }
        });

        // Submit checkout form
        $('#submitCheckoutForm').on('click', function(e){
            e.preventDefault();
            
            const isPickup = $('#is_pickup').val() == 1;
            const storePickupId = $('#store_pickup_id').val();
            const shippingAddressId = $('#shipping_address_id').val();
            
            // Validar que se haya seleccionado algo
            if (isPickup && !storePickupId) {
                toastr.error('Por favor selecciona una tienda para el recojo');
                return false;
            }
            
            if (!isPickup && !shippingAddressId) {
                toastr.error('Por favor selecciona una dirección de envío');
                return false;
            }
            
            // Si todo está correcto, enviar el formulario
            const formData = $('#checkOutForm').serialize();
            
            $.ajax({
                url: "{{ route('user.checkout.form-submit') }}",
                method: 'POST',
                data: formData,
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                beforeSend: function(){
                    $('#submitCheckoutForm').html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                },
                success: function(data){
                    if(data.status === 'success'){
                        window.location.href = data.redirect_url;
                    }
                },
                error: function(data){
                    console.log(data);
                    toastr.error('Algo salió mal, intenta nuevamente');
                    $('#submitCheckoutForm').html('Realizar pedido');
                }
            });
        });

        $('.edit-address-btn').on('click', function() {
            const addressId = $(this).data('id');
            
            // Limpiar formulario
            $('#editAddressForm')[0].reset();
            
            // Actualizar acción del formulario
            $('#editAddressForm').attr('action', "{{ route('user.checkout.address.update') }}");
            $('#edit_address_id').val(addressId);
            
            // Cargar datos de la dirección
            $.ajax({
                url: "{{ route('user.checkout.address.get') }}",
                method: 'POST',
                data: {
                    address_id: addressId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const address = response.address;
                    
                    // Llenar campos del formulario
                    $('#edit_name').val(address.name);
                    $('#edit_phone').val(address.phone);
                    $('#edit_email').val(address.email);
                    $('#edit_zip').val(address.zip);
                    $('#edit_address').val(address.address);
                    
                    // Seleccionar departamento
                    $('#edit_departamento_id').val(address.dep_id).trigger('change');
                    
                    // Crear función para cargar provincias y luego distritos
                    setTimeout(function() {
                        // Cargar provincias
                        $.ajax({
                            url: "{{ route('user.checkout.get-provincias') }}",
                            type: "POST",
                            data: {
                                dep_id: address.dep_id
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                $('#edit_provincia_id').empty();
                                $('#edit_provincia_id').append('<option value="">Seleccione una provincia *</option>');
                                
                                $.each(data, function(key, value) {
                                    $('#edit_provincia_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                                });
                                
                                // Seleccionar provincia
                                $('#edit_provincia_id').val(address.prov_id).trigger('change');
                                
                                // Cargar distritos
                                $.ajax({
                                    url: "{{ route('user.checkout.get-distritos') }}",
                                    type: "POST",
                                    data: {
                                        prov_id: address.prov_id
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(data) {
                                        $('#edit_distrito_id').empty();
                                        $('#edit_distrito_id').append('<option value="">Seleccione un distrito *</option>');
                                        
                                        $.each(data, function(key, value) {
                                            $('#edit_distrito_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                                        });
                                        
                                        // Seleccionar distrito
                                        $('#edit_distrito_id').val(address.dist_id);
                                    }
                                });
                            }
                        });
                    }, 300);
                    
                    // Configurar coordenadas GPS
                    $('#edit_latitude').val(address.latitude);
                    $('#edit_longitude').val(address.longitude);
                    
                    // Inicializar mapa
                    setTimeout(function() {
                        initEditAddressMap(
                            address.latitude ? parseFloat(address.latitude) : null,
                            address.longitude ? parseFloat(address.longitude) : null
                        );
                    }, 500);
                },
                error: function(error) {
                    console.error('Error al cargar datos de la dirección:', error);
                    toastr.error('Error al cargar datos de la dirección');
                }
            });
        });

        $('.delete-address-btn').on('click', function () {
            const addressId = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar dirección?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.checkout.address.delete') }}",
                        method: 'POST',
                        data: {
                            address_id: addressId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: 'La dirección ha sido eliminada.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', response.message || 'No se pudo eliminar la dirección.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Ocurrió un problema al eliminar la dirección.', 'error');
                        }
                    });
                }
            });
        });


        // Lógica para cargar provincias al seleccionar departamento (para editar)
        $('#edit_departamento_id').on('change', function() {
            const departamentoId = $(this).val();
            
            if (departamentoId) {
                // Habilitar y cargar las provincias
                $('#edit_provincia_id').prop('disabled', false);
                $('#edit_provincia_id').html('<option value="">Cargando provincias...</option>');
                $.ajax({
                    url: "{{ route('user.checkout.get-provincias') }}",
                    type: "POST",
                    data: {
                        dep_id: departamentoId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#edit_provincia_id').empty();
                        $('#edit_provincia_id').append('<option value="">Seleccione una provincia *</option>');
                        
                        $.each(data, function(key, value) {
                            $('#edit_provincia_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        
                        // Resetear y deshabilitar el selector de distritos
                        $('#edit_distrito_id').empty();
                        $('#edit_distrito_id').append('<option value="">Seleccione un distrito *</option>');
                        $('#edit_distrito_id').prop('disabled', true);
                    },
                    error: function(error) {
                        console.error('Error al cargar provincias:', error);
                        toastr.error('Error al cargar las provincias');
                    }
                });
            } else {
                // Si no hay departamento seleccionado, deshabilitar los otros selectores
                $('#edit_provincia_id').prop('disabled', true);
                $('#edit_provincia_id').empty();
                $('#edit_provincia_id').append('<option value="">Seleccione una provincia *</option>');
                
                $('#edit_distrito_id').prop('disabled', true);
                $('#edit_distrito_id').empty();
                $('#edit_distrito_id').append('<option value="">Seleccione un distrito *</option>');
            }
        });
        

        // Lógica para cargar distritos al seleccionar provincia (para editar)
        $('#edit_provincia_id').on('change', function() {
            const provinciaId = $(this).val();
            
            if (provinciaId) {
                // Habilitar y cargar los distritos
                $('#edit_distrito_id').prop('disabled', false);
                $('#edit_distrito_id').html('<option value="">Cargando distritos...</option>');
                $.ajax({
                    url: "{{ route('user.checkout.get-distritos') }}",
                    type: "POST",
                    data: {
                        prov_id: provinciaId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#edit_distrito_id').empty();
                        $('#edit_distrito_id').append('<option value="">Seleccione un distrito *</option>');
                        
                        $.each(data, function(key, value) {
                            $('#edit_distrito_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar distritos:', error);
                        toastr.error('Error al cargar los distritos');
                    }
                });
            } else {
                // Si no hay provincia seleccionada, deshabilitar el selector de distritos
                $('#edit_distrito_id').prop('disabled', true);
                $('#edit_distrito_id').empty();
                $('#edit_distrito_id').append('<option value="">Seleccione un distrito *</option>');
            }
        });

        // Código para el manejo del retiro en tienda
        window.storePickupMapManager = {
            mapa: null,
            marcadores: [],
            tiendaSeleccionadaId: null,
            tiendas: @json($tiendasRetiro),
            
            initializeMap: function() {
                if ($('#mapa').length === 0 || this.mapa !== null) return;
                
                this.mapa = L.map('mapa');
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.mapa);
                
                this.marcadores.forEach(function(marker) {
                    marker.remove();
                });
                this.marcadores = [];
                
                if (this.tiendas.length > 0) {
                    this.mapa.setView([this.tiendas[0].latitud, this.tiendas[0].longitud], 13);
                    
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
                        
                        marcador.on('click', function() {
                            self.seleccionarTienda(tienda.id);
                        });
                    });
                } else {
                    this.mapa.setView([-12.046374, -77.042793], 10);
                }
                
                setTimeout(function() {
                    if (self.mapa) self.mapa.invalidateSize();
                }, 300);
            },
            
            seleccionarTienda: function(tiendaId) {
                this.tiendaSeleccionadaId = tiendaId;
                
                $('.tienda-item').removeClass('tienda-activa');
                $('.tienda-item[data-id="' + tiendaId + '"]').addClass('tienda-activa');
                
                var tiendaSeleccionada = this.tiendas.find(function(t) {
                    return t.id == tiendaId;
                });
                
                if (tiendaSeleccionada && this.mapa) {
                    this.mapa.flyTo([tiendaSeleccionada.latitud, tiendaSeleccionada.longitud], 16, {
                        duration: 1.5
                    });
                    
                    this.marcadores.forEach(function(m) {
                        if (m.tiendaId == tiendaId) {
                            m.openPopup();
                        }
                    });
                    
                    $('#tienda-seleccionada-container').fadeIn(300);
                    
                    $('#detalles-adicionales').html(`
                        <p>${tiendaSeleccionada.instrucciones_retiro || 'Esta tienda ofrece todos nuestros productos y promociones.'}</p>
                    `);
                    
                    $('#servicios-disponibles').html(`
                        <ul class="list-unstyled">
                            ${tiendaSeleccionada.documentos_requeridos ? '<li><i class="fas fa-check-circle" style="color: var(--general);"></i> ' + tiendaSeleccionada.documentos_requeridos + '</li>' : ''}
                        </ul>
                    `);
                    
                    var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${tiendaSeleccionada.latitud},${tiendaSeleccionada.longitud}`;
                    $('#btn-como-llegar').attr('onclick', `window.open('${googleMapsUrl}', '_blank')`);
                    
                    $('html, body').animate({
                        scrollTop: $("#tienda-seleccionada-container").offset().top - 100
                    }, 500);
                    
                    // Guardar en el formulario y en localStorage
                    $('#store_pickup_id').val(tiendaId);
                    localStorage.setItem('selected_store_id', tiendaId);
                }
            },
            
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
                        
                        var marcadorUsuario = L.marker([latUsuario, lngUsuario], {
                            icon: iconoUsuario
                        }).addTo(self.mapa).bindPopup('Tu ubicación actual').openPopup();
                        
                        var bounds = L.latLngBounds([latUsuario, lngUsuario]);
                        self.tiendas.forEach(function(tienda) {
                            bounds.extend([tienda.latitud, tienda.longitud]);
                        });
                        self.mapa.fitBounds(bounds, { padding: [50, 50] });
                        
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
                            $('.tienda-item').removeClass('tienda-cercana');
                            $('.badge-cercana').remove();
                            
                            setTimeout(function() {
                                var $tiendaCercana = $('.tienda-item[data-id="' + tiendaMasCercana.id + '"]');
                                $tiendaCercana.addClass('tienda-cercana');
                                $tiendaCercana.find('h5').append('<span class="badge-cercana">Más cercana</span>');
                                
                                $('.lista-tiendas').animate({
                                    scrollTop: $tiendaCercana.position().top - $('.lista-tiendas').position().top
                                }, 500);
                            }, 1000);
                        }
                        
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
            
            calcularDistancia: function(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }
        };
        
        // Eventos para el retiro en tienda
        $(document).on('click', '.tienda-item', function() {
            if (!window.storePickupMapManager.mapa) return;
            
            var tiendaId = $(this).data('id');
            $(this).addClass('tienda-activa').siblings().removeClass('tienda-activa');
            window.storePickupMapManager.seleccionarTienda(tiendaId);
        });
        
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
            
            if ($('.tienda-item:visible').length === 0) {
                if ($('.no-resultados').length === 0) {
                    $('.lista-tiendas').append('<div class="alert alert-info mt-3 no-resultados">No se encontraron tiendas con ese criterio de búsqueda.</div>');
                }
            } else {
                $('.no-resultados').remove();
            }
        });
        
        $('#detectar-ubicacion').on('click', function() {
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Detectando...').prop('disabled', true);
            window.storePickupMapManager.detectarUbicacion();
        });
        
        $(document).on('mouseenter', '.tienda-item', function() {
            if (!window.storePickupMapManager.mapa) return;
            
            var tiendaId = $(this).data('id');
            
            window.storePickupMapManager.marcadores.forEach(function(m) {
                if (m.tiendaId == tiendaId && m._icon) {
                    m._icon.style.filter = "brightness(1.3)";
                    m._icon.style.transform = m._icon.style.transform + " scale(1.2)";
                    m._icon.style.zIndex = 1000;
                }
            });
        }).on('mouseleave', '.tienda-item', function() {
            if (!window.storePickupMapManager.mapa) return;
            
            window.storePickupMapManager.marcadores.forEach(function(m) {
                if (m._icon) {
                    m._icon.style.filter = "";
                    m._icon.style.transform = m._icon.style.transform.replace(" scale(1.2)", "");
                    m._icon.style.zIndex = "";
                }
            });
        });

        // Botón para seleccionar tienda
        $('#btn-como-llegar').on('click', function() {
            if (window.storePickupMapManager.tiendaSeleccionadaId) {
                // Asignar el ID de la tienda seleccionada al campo oculto
                $('#store_pickup_id').val(window.storePickupMapManager.tiendaSeleccionadaId);
                toastr.success('Tienda seleccionada correctamente');
            } else {
                toastr.error('Por favor selecciona una tienda primero');
            }
        });
    });
</script>
@endpush

