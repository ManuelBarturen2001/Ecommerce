@extends('frontend.layouts.master') 
@section('hide_scroll_cart', true)
@section('title') 
{{$settings->site_name}} || Privacidad y Confidencialidad 
@endsection 

<style>
    /* Estilos para Privacidad y Confidencialidad */
    .wsus_menu_category_bar .far{
        line-height: 2.5;
    }

    .wsus_close_mini_cart .far{
        line-height: 1.8;
    }

    .wsus__scroll_btn .fa-chevron-up{
        line-height: 2;
    }
    
    .privacy-container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        padding: 40px;
        margin: 30px 0 60px;
    }
    
    .privacy-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--general);
        position: relative;
    }
    
    .privacy-header h3 {
        color: #333;
        font-weight: 600;
    }
    
    .privacy-header .privacy-icon {
        color: var(--general);
        font-size: 28px;
        margin-right: 15px;
        vertical-align: middle;
    }
    
    .last-updated {
        background-color: #f8f9fa;
        padding: 8px 15px;
        border-radius: 30px;
        display: inline-block;
        font-size: 14px;
        color: #666;
        margin-bottom: 25px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .privacy-content h4 {
        color: var(--general);
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 600;
        font-size: 20px;
        padding-left: 15px;
        border-left: 4px solid var(--general);
    }
    
    .privacy-content p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 15px;
    }
    
    .privacy-content ul, .privacy-content ol {
        margin-bottom: 20px;
        padding-left: 20px;
    }
    
    .privacy-content li {
        margin-bottom: 10px;
        line-height: 1.7;
    }
    
    .privacy-content strong {
        color: #444;
    }
    
    .privacy-section {
        background-color: #fdfdfd;
        border-radius: 6px;
        padding: 20px 25px;
        margin-bottom: 25px;
        border: 1px solid #f5f5f5;
        transition: all 0.3s ease;
    }
    
    .privacy-section:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        transform: translateY(-2px);
    }
    
    .privacy-footer {
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid #eaeaea;
        text-align: center;
    }
    
    .privacy-footer p {
        margin-bottom: 20px;
    }
    
    .contact-options {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
    }
    
    .contact-btn {
        background-color: var(--general);
        color: white;
        padding: 10px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .contact-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        color: white;
    }
    
    .contact-btn.outline {
        background-color: transparent;
        color: var(--general);
        border: 1px solid var(--general);
    }
    
    .contact-btn.outline:hover {
        background-color: var(--general);
        color: white;
    }
    
    /* Estilos responsive */
    @media (max-width: 767px) {
        .privacy-container {
            padding: 25px 20px;
            margin: 20px 0 40px;
        }
        
        .privacy-header {
            text-align: center;
        }
        
        .privacy-section {
            padding: 15px 20px;
        }
        
        .contact-options {
            flex-direction: column;
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
                            <h4>Privacidad y Confidencialidad</h4> 
                            <nav aria-label="breadcrumb"> 
                                <ol class="breadcrumb"> 
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li> 
                                    <li class="breadcrumb-item active" aria-current="page"> Privacidad y Confidencialidad</li> 
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

    <!-- CONTENIDO DE PRIVACIDAD Y CONFIDENCIALIDAD -->
    <section id="wsus__cart_view">
        <div class="container">
            <div class="privacy-container">
                <!-- Encabezado -->
                <div class="privacy-header">
                    <p>Nos comprometemos a proteger y respetar su privacidad. Por favor lea esta política cuidadosamente.</p>
                </div>
                
                <!-- Fecha de actualización -->
                <div class="last-updated">
                    <i class="far fa-calendar-alt me-2"></i> Última actualización: {{date('d/m/Y', strtotime(@$polipriv->updated_at))}}
                </div>
                
                <!-- Contenido de privacidad -->
                <div class="privacy-content">
                    <div class="privacy-section">
                        {!!@$polipriv->content!!}
                    </div>
                </div>
                
                <!-- Pie de privacidad -->
                <div class="privacy-footer">
                    <p>Si tiene alguna pregunta sobre nuestra Política de Privacidad y Confidencialidad, estamos aquí para ayudarle.</p>
                    
                    <div class="contact-options">
                        <a href="{{route('contact')}}" class="contact-btn">Contactar con Nosotros</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection