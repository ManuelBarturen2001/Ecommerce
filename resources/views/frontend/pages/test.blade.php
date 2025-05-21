@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
{{$settings->site_name}} || Finalizar compra
@endsection

@section('content')



    <section id="wsus__cart_view">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="wsus__check_form">
                        <div class="row mt-0" id="storePickupInfo" style="display: none;">
                            <div class="col-12">
                                <div class="card shadow-sm border-0 rounded-4">
                                    <div class="card-header rounded-top-4" style="background-color: var(--general);">
                                        <h5 class="mb-0" style="color: #ffffff"><i class="fas fa-store me-2"></i> Recojo en tienda</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row gy-4">
                                            <!-- Información de la tienda -->
                                            <div class="col-md-6">
                                                <h6 class="mb-3 text-secondary fw-bold">Detalles de la tienda</h6>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><strong>Nombre:</strong> {{ $settings->site_name }}</li>
                                                    <li class="list-group-item"><strong>Dirección:</strong> {{ $settings->contact_address ?? 'Dirección de la tienda' }}</li>
                                                    <li class="list-group-item"><strong>Horario:</strong> {{ $settings->store_hours ?? 'Lunes a Viernes: 9:00 AM - 6:00 PM, Sábados: 9:00 AM - 1:00 PM' }}</li>
                                                    <li class="list-group-item"><strong>Teléfono:</strong> {{ $settings->contact_phone }}</li>
                                                    <li class="list-group-item"><strong>Email:</strong> {{ $settings->contact_email }}</li>
                                                </ul>
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
                                if ($method->type === 'flat_cost')
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
                            </form>
                            <a href="" id="submitCheckoutForm" class="common_btn">Realizar pedido</a>
                        </div>
                    </div>
                </div>
            </div>
    </section>



    <!--============================
        PÁGINA DE FINALIZAR COMPRA FIN
    ==============================-->
@endsection

@push('scripts')
<!-- Incluir Google Maps JavaScript API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_-9Mls1y0uWh8u5y4kGApHh1Eo35_TZg&libraries=places"></script>
<script>

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
                // Si hay un mapa de la tienda, inicializarlo
                if ($('#store_map').length && storeLatitude && storeLongitude) {
                    setTimeout(function() {
                        initStoreMap();
                    }, 300);
                }
                
                // No necesitamos una dirección de envío para recojo en tienda
                $('#shipping_address_id').val("0");
            } else {
                // Es envío a domicilio, mostrar sección de direcciones y ocultar info de tienda
                $('.wsus__check_form').show();
                $('#storePickupInfo').hide();
                // Es envío a domicilio, mostrar sección de direcciones y ocultar info de tienda
                $('.wsus__check_form').show();
                $('#storePickupInfo').hide();

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


        // Submit checkout form
        $('#submitCheckoutForm').on('click', function(e){
            e.preventDefault();
            
            // Obtener el método de envío seleccionado
            const shippingMethodId = $('#shipping_method_id').val();
            const shippingMethod = $('.shipping_method:checked');
            const isStorePickup = shippingMethod.length > 0 && shippingMethod.data('pickup') === true;
            
            if(shippingMethodId === ""){
                toastr.error('Se requiere un método de envío');
            }
            // Solo validar dirección de envío si NO es recojo en tienda
            else if (!isStorePickup && $('#shipping_address_id').val() === ""){
                toastr.error('Se requiere una dirección de envío');
            }
            else if (!$('.agree_term').prop('checked')){
                toastr.error('Debe aceptar los términos y condiciones del sitio web');
            }
            else {
                $.ajax({
                    url: "{{route('user.checkout.form-submit')}}",
                    method: 'POST',
                    data: $('#checkOutForm').serialize(),
                    headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                    beforeSend: function(){
                        $('#submitCheckoutForm').html('<i class="fas fa-spinner fa-spin fa-1x"></i>')
                    },
                    success: function(data){
                        if(data.status === 'success'){
                            $('#submitCheckoutForm').text('Realizar pedido')
                            // Redirigir al usuario a la siguiente página
                            window.location.href = data.redirect_url;
                        }
                    },
                    error: function(data){
                        console.log(data);
                        $('#submitCheckoutForm').text('Realizar pedido');
                        toastr.error('Ha ocurrido un error al procesar su pedido');
                    }
                })
            }
        });

        



    });
</script>
@endpush