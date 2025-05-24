@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || Olvidé mi contraseña
@endsection

@section('content')

    <!--============================
        INICIO DEL MIGAS DE PAN
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>olvidé mi contraseña</h4>
                        <ul>
                            <li><a href="#">iniciar sesión</a></li>
                            <li><a href="#">olvidé mi contraseña</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        FIN DEL MIGAS DE PAN
    ==============================-->


    <!--============================
        INICIO DE OLVIDÉ MI CONTRASEÑA
    ==============================-->
    <section id="wsus__login_register">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 m-auto">
                    <div class="wsus__forget_area">
                        <span class="qiestion_icon"><i class="fal fa-question-circle"></i></span>
                        <h4>¿Olvidaste tu contraseña?</h4>
                        <p>Ingresa el correo electrónico registrado en <span>e-shop</span></p>
                        <div class="wsus__login">
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="wsus__login_input">
                                    <i class="fal fa-envelope"></i>
                                    <input id="email" type="email" name="email" value="{{old('email')}}" placeholder="Tu correo electrónico">
                                </div>

                                <button class="common_btn" type="submit">Enviar</button>
                            </form>
                        </div>
                        <a class="see_btn mt-4" href="{{route('login')}}">ir a iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        FIN DE OLVIDÉ MI CONTRASEÑA
    ==============================-->
@endsection
