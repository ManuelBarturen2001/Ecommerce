@extends('frontend.layouts.master') 
@section('hide_scroll_cart', true)
@section('title') 
{{$settings->site_name}} || Términos y condiciones 
@endsection 


<style>
    /* Estilos para Términos y Condiciones */
    .wsus_menu_category_bar .far{
        line-height: 2.5;
    }

    .wsus_close_mini_cart .far{
        line-height: 1.8;
    }

    .wsus__scroll_btn .fa-chevron-up{
        line-height: 2;
    }
    .terms-container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 40px;
        margin: 30px 0 60px;
    }
    
    .terms-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--general);
    }
    
    .terms-header h3 {
        color: #333;
        font-weight: 600;
    }
    
    .last-updated {
        background-color: #f8f9fa;
        padding: 8px 15px;
        border-radius: 4px;
        display: inline-block;
        font-size: 14px;
        color: #666;
        margin-bottom: 25px;
    }
    
    .terms-content h4 {
        color: var(--general);
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 600;
        font-size: 20px;
    }
    
    .terms-content p {
        color: #555;
        line-height: 1.7;
        margin-bottom: 15px;
    }
    
    .terms-content ul, .terms-content ol {
        margin-bottom: 20px;
        padding-left: 20px;
    }
    
    .terms-content li {
        margin-bottom: 8px;
        line-height: 1.6;
    }
    
    .terms-content strong {
        color: #444;
    }
    
    .terms-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eaeaea;
        text-align: center;
    }
    
    .terms-footer .contact-btn {
        background-color: var(--general);
        color: white;
        padding: 10px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
        margin-top: 15px;
        transition: all 0.3s ease;
    }
    
    .terms-footer .contact-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
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
                            <h4>Términos y Condiciones</h4> 
                            <nav aria-label="breadcrumb"> 
                                <ol class="breadcrumb"> 
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li> 
                                    <li class="breadcrumb-item active" aria-current="page">Términos y Condiciones</li> 
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

    <!-- CONTENIDO DE TÉRMINOS Y CONDICIONES -->
    <section id="wsus__cart_view">
        <div class="container">
            <div class="terms-container">
                <!-- Encabezado -->
                <div class="terms-header">
                    <p>Por favor, lea detenidamente estos términos antes de utilizar nuestros servicios.</p>
                </div>
                
                <!-- Fecha de actualización -->
                <div class="last-updated">
                    <i class="far fa-calendar-alt me-2"></i> Última actualización: {{date('d/m/Y', strtotime(@$terms->updated_at))}}
                </div>
                
                <!-- Contenido de términos -->
                <div class="terms-content">
                    {!!@$terms->content!!}
                </div>
                
                <!-- Pie de términos -->
                <div class="terms-footer">
                    <p>Si tiene alguna pregunta sobre estos Términos y Condiciones, por favor contáctenos.</p>
                    <a href="{{route('contact')}}" class="contact-btn">Contactar con Nosotros</a>
                </div>
            </div>
        </div>
    </section>
@endsection