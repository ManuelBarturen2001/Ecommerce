@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || Restablecer Contraseña
@endsection

@section('content')
    <!--============================
        INICIO DE MIGAS DE PAN
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>Restablecer contraseña</h4>
                        <ul>
                            <li><a href="#">iniciar sesión</a></li>
                            <li><a href="#">restablecer contraseña</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        FIN DE MIGAS DE PAN
    ==============================-->


    <!--============================
        INICIO DE CAMBIO DE CONTRASEÑA
    ==============================-->
    <section id="wsus__login_register">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-md-10 col-lg-7 m-auto">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf


                        <div class="wsus__change_password">
                            <h4>restablecer contraseña</h4>
                                <!-- Token para restablecer contraseña -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="wsus__single_pass">
                                <label>correo electrónico</label>
                                <input id="email" type="email" name="email" value="{{old('email', $request->email)}}" placeholder="Correo Electrónico">
                            </div>

                            <div class="wsus__single_pass">
                                <label>nueva contraseña</label>
                                <input id="password" type="password" name="password" placeholder="Nueva Contraseña">
                            </div>


                            <div class="wsus__single_pass">
                                <label>confirmar contraseña</label>
                                <input id="password_confirmation" type="password"
                                name="password_confirmation" type="text" placeholder="Confirmar Contraseña">
                            </div>


                            <button class="common_btn" type="submit">enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        FIN DE CAMBIO DE CONTRASEÑA
    ==============================-->
@endsection
