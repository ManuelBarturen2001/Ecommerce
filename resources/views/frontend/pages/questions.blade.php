@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
{{$settings->site_name}} || Preguntas Frecuentes
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
                            <h4>Preguntas Frecuentes</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Preguntas Frecuentes</li>
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
        FAQ START
    ==============================-->
    <section id="wsus__faq">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <div class="section-heading">
                        <h2 class="mb-3">¿Cómo podemos ayudarte?</h2>
                        <p class="text-muted">Encuentra respuestas a las dudas más comunes sobre nuestros productos y servicios</p>
                    </div>
                </div>
            </div>
            
            <div class="faq-categories mb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="faq-category-card text-center p-3" onclick="filterCategory('compras')">
                            <i class="fas fa-shopping-cart fa-2x mb-2" style="color: var(--general);"></i>
                            <h6>Compras</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="faq-category-card text-center p-3" onclick="filterCategory('envios')">
                            <i class="fas fa-truck fa-2x mb-2" style="color: var(--general);"></i>
                            <h6>Envíos</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="faq-category-card text-center p-3" onclick="filterCategory('pagos')">
                            <i class="fas fa-credit-card fa-2x mb-2" style="color: var(--general);"></i>
                            <h6>Pagos</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="faq-category-card text-center p-3" onclick="filterCategory('devoluciones')">
                            <i class="fas fa-exchange-alt fa-2x mb-2" style="color: var(--general);"></i>
                            <h6>Devoluciones</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="faq-category-card text-center p-3" onclick="filterCategory('cuenta')">
                            <i class="fas fa-user fa-2x mb-2" style="color: var(--general);"></i>
                            <h6>Mi cuenta</h6>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="accordion custom-accordion" id="faqAccordion">
                <!-- Compras -->
                <div class="faq-category" data-category="compras">
                    <h4 class="category-title mb-4"><i class="fas fa-shopping-cart me-2" style="color: var(--general);"></i>Compras</h4>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="compras-heading1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#compras-collapse1" aria-expanded="false">
                                ¿Es seguro comprar en línea en su tienda?
                            </button>
                        </h2>
                        <div id="compras-collapse1" class="accordion-collapse collapse" aria-labelledby="compras-heading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Sí, nuestra tienda en línea es completamente segura. Utilizamos tecnología de encriptación SSL de 256 bits para proteger toda la información personal y financiera que compartes con nosotros. Además, nunca almacenamos los datos completos de tu tarjeta de crédito en nuestros servidores. Contamos con sistemas de seguridad avanzados y regularmente realizamos auditorías para garantizar la protección de tus datos.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="compras-heading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#compras-collapse2" aria-expanded="false">
                                ¿Cómo puedo realizar un seguimiento de mi pedido?
                            </button>
                        </h2>
                        <div id="compras-collapse2" class="accordion-collapse collapse" aria-labelledby="compras-heading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Para realizar un seguimiento de tu pedido, simplemente inicia sesión en tu cuenta y dirígete a la sección "Mis pedidos". Allí encontrarás todos tus pedidos recientes con su estado actual y un número de seguimiento una vez que el pedido haya sido enviado. Alternativamente, puedes usar el enlace de seguimiento que te enviamos por correo electrónico cuando despachamos tu pedido. Si tienes problemas para localizar esta información, no dudes en contactar a nuestro equipo de atención al cliente.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="compras-heading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#compras-collapse3" aria-expanded="false">
                                ¿Puedo modificar mi pedido después de realizarlo?
                            </button>
                        </h2>
                        <div id="compras-collapse3" class="accordion-collapse collapse" aria-labelledby="compras-heading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Podemos modificar tu pedido siempre y cuando no haya entrado en proceso de preparación. Si deseas hacer cambios, contáctanos inmediatamente a través de nuestro correo electrónico de atención al cliente o por WhatsApp, indicando tu número de pedido y los cambios que deseas realizar. Una vez que el pedido entre en proceso de preparación o envío, ya no será posible realizar modificaciones.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Envíos -->
                <div class="faq-category" data-category="envios">
                    <h4 class="category-title mb-4"><i class="fas fa-truck me-2" style="color: var(--general);"></i>Envíos</h4>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="envios-heading1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#envios-collapse1" aria-expanded="false">
                                ¿Cuánto tiempo tardará en llegar mi pedido?
                            </button>
                        </h2>
                        <div id="envios-collapse1" class="accordion-collapse collapse" aria-labelledby="envios-heading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Los tiempos de entrega dependen de tu ubicación. Para Lima Metropolitana, los pedidos suelen entregarse en 1-3 días hábiles. Para provincias, el tiempo de entrega es de 3-7 días hábiles, dependiendo de la accesibilidad de la zona. Ten en cuenta que estos plazos comienzan a contar desde que confirmamos tu pago. En temporadas de alta demanda, como festividades, puede haber ligeros retrasos que comunicaremos oportunamente.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="envios-heading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#envios-collapse2" aria-expanded="false">
                                ¿Realizan envíos internacionales?
                            </button>
                        </h2>
                        <div id="envios-collapse2" class="accordion-collapse collapse" aria-labelledby="envios-heading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Actualmente ofrecemos envíos internacionales a varios países de Latinoamérica y Estados Unidos. El costo y tiempo de envío varía según el destino y el peso del paquete. Durante el proceso de compra, podrás ver las opciones disponibles para tu país y los costos asociados. Si tu país no aparece en las opciones o tienes dudas sobre un envío internacional, contáctanos directamente para verificar disponibilidad y opciones personalizadas.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="envios-heading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#envios-collapse3" aria-expanded="false">
                                ¿Qué métodos de envío están disponibles?
                            </button>
                        </h2>
                        <div id="envios-collapse3" class="accordion-collapse collapse" aria-labelledby="envios-heading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Ofrecemos varios métodos de envío para adaptarnos a tus necesidades:
                                <br>- Envío estándar: La opción más económica, con entrega en 3-7 días hábiles.
                                <br>- Envío express: Entrega garantizada en 1-2 días hábiles (disponible solo en ciertas zonas).
                                <br>- Recojo en tienda: Puedes recoger tu pedido sin costo adicional en cualquiera de nuestras tiendas físicas.
                                <br>
                                <br>Trabajamos con empresas de mensajería reconocidas que garantizan la seguridad de tu paquete hasta la entrega.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagos -->
                <div class="faq-category" data-category="pagos">
                    <h4 class="category-title mb-4"><i class="fas fa-credit-card me-2" style="color: var(--general);"></i>Pagos</h4>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="pagos-heading1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pagos-collapse1" aria-expanded="false">
                                ¿Qué métodos de pago aceptan?
                            </button>
                        </h2>
                        <div id="pagos-collapse1" class="accordion-collapse collapse" aria-labelledby="pagos-heading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Aceptamos diversos métodos de pago para tu comodidad:
                                <br>- Tarjetas de crédito y débito (Visa, Mastercard, American Express)
                                <br>- PayPal
                                <br>- Transferencias bancarias
                                <br>- Yape/Plin
                                <br>- Pago contra entrega (solo disponible en ciertas zonas)
                                <br>
                                <br>Todos nuestros métodos de pago cuentan con sistemas de seguridad avanzados para proteger tu información financiera.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="pagos-heading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pagos-collapse2" aria-expanded="false">
                                ¿Ofrecen pagos en cuotas?
                            </button>
                        </h2>
                        <div id="pagos-collapse2" class="accordion-collapse collapse" aria-labelledby="pagos-heading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Sí, ofrecemos la opción de pagar en cuotas al utilizar tarjetas de crédito de los principales bancos. El número de cuotas disponibles y los intereses aplicables dependerán de tu entidad bancaria. Durante el proceso de pago, podrás seleccionar el número de cuotas que mejor se adapte a tus necesidades. Para compras superiores a S/300, también ofrecemos promociones especiales de cuotas sin intereses en fechas específicas.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="pagos-heading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pagos-collapse3" aria-expanded="false">
                                ¿Cómo sé si mi pago fue procesado correctamente?
                            </button>
                        </h2>
                        <div id="pagos-collapse3" class="accordion-collapse collapse" aria-labelledby="pagos-heading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Una vez que tu pago sea procesado correctamente, recibirás una confirmación en la pantalla y enviaremos automáticamente un correo electrónico con los detalles de tu compra y el estado de tu pago. Si no recibes este correo dentro de los 30 minutos posteriores a tu compra, te recomendamos revisar tu carpeta de spam o contactar a nuestro servicio de atención al cliente para verificar el estado de tu pago.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Devoluciones -->
                <div class="faq-category" data-category="devoluciones">
                    <h4 class="category-title mb-4"><i class="fas fa-exchange-alt me-2" style="color: var(--general);"></i>Devoluciones</h4>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="devoluciones-heading1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#devoluciones-collapse1" aria-expanded="false">
                                ¿Cuál es su política de devoluciones?
                            </button>
                        </h2>
                        <div id="devoluciones-collapse1" class="accordion-collapse collapse" aria-labelledby="devoluciones-heading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Aceptamos devoluciones dentro de los 15 días posteriores a la recepción del producto, siempre que el artículo se encuentre en su estado original, sin usar y con todas sus etiquetas y empaques. Para iniciar una devolución, debes contactar a nuestro servicio de atención al cliente y proporcionar tu número de pedido. Ten en cuenta que algunos productos, como artículos personalizados o de higiene personal, no son elegibles para devolución por razones de seguridad e higiene.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="devoluciones-heading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#devoluciones-collapse2" aria-expanded="false">
                                ¿Cómo puedo solicitar un cambio de producto?
                            </button>
                        </h2>
                        <div id="devoluciones-collapse2" class="accordion-collapse collapse" aria-labelledby="devoluciones-heading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Para solicitar un cambio de producto, debes contactarnos dentro de los 15 días posteriores a la recepción de tu pedido. Envíanos un correo electrónico a atención@cliente.com con el asunto "Solicitud de cambio" e incluye tu número de pedido, el producto que deseas cambiar y el motivo del cambio. Para cambios por talla o color, verificaremos la disponibilidad del producto deseado. Si el producto de reemplazo tiene un precio diferente, se te cobrará o reembolsará la diferencia según corresponda.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="devoluciones-heading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#devoluciones-collapse3" aria-expanded="false">
                                ¿Qué hago si mi producto llega defectuoso?
                            </button>
                        </h2>
                        <div id="devoluciones-collapse3" class="accordion-collapse collapse" aria-labelledby="devoluciones-heading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Si recibes un producto defectuoso o dañado, contáctanos dentro de las 48 horas posteriores a la recepción. Envíanos fotos claras del producto y del empaque, junto con una descripción del problema. Nuestro equipo evaluará el caso y te ofrecerá una solución, que puede ser un reemplazo del producto, una reparación o un reembolso completo. Asumimos los costos de envío para la devolución de productos defectuosos. Tu satisfacción es nuestra prioridad, por lo que resolveremos cualquier problema lo más rápido posible.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mi cuenta -->
                <div class="faq-category" data-category="cuenta">
                    <h4 class="category-title mb-4"><i class="fas fa-user me-2" style="color: var(--general);"></i>Mi cuenta</h4>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="cuenta-heading1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta-collapse1" aria-expanded="false">
                                ¿Cómo puedo crear una cuenta?
                            </button>
                        </h2>
                        <div id="cuenta-collapse1" class="accordion-collapse collapse" aria-labelledby="cuenta-heading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Crear una cuenta es muy sencillo. Haz clic en el icono de usuario ubicado en la esquina superior derecha de nuestra página web y selecciona "Registrarse". Completa el formulario con tu nombre, correo electrónico y crea una contraseña segura. También puedes registrarte rápidamente utilizando tus cuentas de Google o Facebook. Una vez completado el registro, recibirás un correo de confirmación. Al crear una cuenta, podrás acceder a ofertas exclusivas, hacer seguimiento de tus pedidos y guardar tus direcciones para futuras compras.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="cuenta-heading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta-collapse2" aria-expanded="false">
                                ¿Cómo puedo recuperar mi contraseña?
                            </button>
                        </h2>
                        <div id="cuenta-collapse2" class="accordion-collapse collapse" aria-labelledby="cuenta-heading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Si olvidaste tu contraseña, haz clic en "Iniciar sesión" y luego en el enlace "¿Olvidaste tu contraseña?" que aparece debajo del formulario de inicio de sesión. Ingresa el correo electrónico asociado a tu cuenta y te enviaremos un enlace para restablecer tu contraseña. Este enlace será válido por 24 horas. Si no recibes el correo, revisa tu carpeta de spam o correo no deseado. Por razones de seguridad, no podemos enviarte tu contraseña actual por correo electrónico ni proporcionarla por teléfono.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3 rounded shadow-sm">
                        <h2 class="accordion-header" id="cuenta-heading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta-collapse3" aria-expanded="false">
                                ¿Cómo puedo actualizar mi información personal?
                            </button>
                        </h2>
                        <div id="cuenta-collapse3" class="accordion-collapse collapse" aria-labelledby="cuenta-heading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Para actualizar tu información personal, inicia sesión en tu cuenta y haz clic en "Mi perfil" o "Configuración de cuenta". Desde allí, podrás editar tu nombre, dirección, número de teléfono y preferencias de comunicación. También puedes agregar o modificar direcciones de envío y facturación. Recuerda guardar los cambios antes de salir de la página. Mantener tu información actualizada nos ayuda a brindarte un mejor servicio y garantiza que tus pedidos lleguen correctamente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-12 mt-5">
                <div class="wsus__faq_text text-center py-4">
                    <h3 class="mb-4">¿No encontraste respuesta a tu pregunta?</h3>
                    <p class="mb-4">Nuestro equipo de atención al cliente está disponible para ayudarte 24/7</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a class="common_btn" href="{{route('contact')}}" style="background-color: var(--general);">Contáctanos</a>
                        <a class="call btn btn-outline-secondary" href="tel:+51987654321"><i class="fas fa-phone-alt me-2"></i>987-654-321</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        FAQ END
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

    .faq-category-card {
        border-radius: 10px;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .faq-category-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-3px);
        border-color: var(--general);
    }
    
    .accordion-button:not(.collapsed) {
        background-color: var(--general);
        color: white;
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--general);
    }
    
    .accordion-button::after {
        background-size: 16px;
    }
    
    .category-title {
        color: var(--general);
        border-bottom: 2px solid var(--general);
        padding-bottom: 10px;
        display: inline-block;
    }
    
    .wsus__faq_text {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .common_btn {
        border-radius: 5px;
        padding: 10px 25px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .common_btn:hover {
        opacity: 0.9;
        color: white;
        transform: translateY(-2px);
    }
    
    .call {
        padding: 10px 25px;
        border-radius: 5px;
    }
    
    /* Inicialmente ocultar todas las categorías excepto la primera */
    .faq-category:not([data-category="compras"]) {
        display: none;
    }
</style>

<script>
    function filterCategory(category) {
        // Ocultar todas las categorías
        document.querySelectorAll('.faq-category').forEach(function(el) {
            el.style.display = 'none';
        });
        
        // Mostrar solo la categoría seleccionada
        document.querySelector(`.faq-category[data-category="${category}"]`).style.display = 'block';
        
        // Resaltar la categoría seleccionada
        document.querySelectorAll('.faq-category-card').forEach(function(el) {
            el.style.backgroundColor = '';
        });
        
        event.currentTarget.style.backgroundColor = 'rgba(var(--general-rgb), 0.1)';
    }
    
    // Inicializar con la primera categoría seleccionada
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.faq-category-card').style.backgroundColor = 'rgba(var(--general-rgb), 0.1)';
    });
</script>
