@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
{{$settings->site_name}} || Acerca de
@endsection

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
                            <h4>Contáctanos</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Contáctanos</li>
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


    <!--============================
        PÁGINA DE CONTACTO INICIO
    ==============================-->
    <section id="wsus__contact">
        <div class="container">
            <div class="wsus__contact_area">
                <div class="row">
                    <div class="col-xl-4">
                        <div class="row">
                            @if ($settings->contact_email)

                            <div class="col-xl-12">
                                <div class="wsus__contact_single">
                                    <i class="fal fa-envelope"></i>
                                    <h5>dirección de correo</h5>
                                    <a href="mailto:example@gmail.com">{{@$settings->contact_email}}</a>
                                    <span><i class="fal fa-envelope"></i></span>
                                </div>
                            </div>
                            @endif
                            @if ($settings->contact_phone)
                            <div class="col-xl-12">
                                <div class="wsus__contact_single">
                                    <i class="far fa-phone-alt"></i>
                                    <h5>número de teléfono</h5>
                                    <a href="macallto:{{@$settings->contact_phone}}">{{@$settings->contact_phone}}</a>
                                    <span><i class="far fa-phone-alt"></i></span>
                                </div>
                            </div>
                            @endif
                            @if ($settings->contact_address)

                            <div class="col-xl-12">
                                <div class="wsus__contact_single">
                                    <i class="fal fa-map-marker-alt"></i>
                                    <h5>dirección de contacto</h5>
                                    <a href="javascript:;">{{@$settings->contact_address}}</a>
                                    <span><i class="fal fa-map-marker-alt"></i></span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="wsus__contact_question">
                            <h5>Envíanos un mensaje</h5>
                            <form id="contact-form">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="wsus__con_form_single">
                                            <input type="text" placeholder="Tu nombre" name="name">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="wsus__con_form_single">
                                            <input type="email" placeholder="Correo electrónico" name="email">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="wsus__con_form_single">
                                            <input type="text" placeholder="Asunto" name="subject">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="wsus__con_form_single">
                                            <textarea cols="3" rows="5" placeholder="Mensaje" name="message"></textarea>
                                        </div>
                                        <button type="submit" class="common_btn" id="form-submit">enviar ahora</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="wsus__con_map">
                            <iframe
                                src="{{$settings->map}}"
                                width="1600" height="450" style="border:0;" allowfullscreen="100"
                                loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PÁGINA DE CONTACTO FIN
    ==============================-->
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#contact-form').on('submit', function(e){
                e.preventDefault();
                let data = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url: "{{route('handle-contact-form')}}",
                    data: data,
                    beforeSend: function(){
                        $('#form-submit').text('enviando..');
                        $('#form-submit').attr('disabled', true);
                    },
                    success: function(data){
                        if(data.status == 'success'){
                            toastr.success(data.message);
                            $('#contact-form')[0].reset();
                            $('#form-submit').text('enviar ahora')
                            $('#form-submit').attr('disabled', false);
                        }
                    },
                    error: function(data){
                        let errors = data.responseJSON.errors;

                        $.each(errors, function(key, value){
                            toastr.error(value);
                        })

                        $('#form-submit').text('enviar ahora');
                        $('#form-submit').attr('disabled', false);
                    }
                })
            })
        })
    </script>
@endpush
