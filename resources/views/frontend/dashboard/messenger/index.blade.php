@extends('frontend.dashboard.layouts.master')
@section('title')
{{$settings->site_name}} || Mis Mensajes
@endsection
@section('content')
<!-- Botón de menú móvil -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
</button>
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
<!--============================
        BREADCRUMB START
    ==============================-->
<section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="breadcrumb__content">
                        <h4>Mensajes</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Mensajes</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
    BREADCRUMB END
==============================-->
<!-- Dashboard Area -->
<section class="dashboard-area">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-xl-2">
                    @include('frontend.dashboard.layouts.sidebar')
                </div>
                 <!-- Main Content -->
                <div class="col-lg-9 col-xl-10">
                  <div class="dashboard-main-content">
                    <div class="dashboard-card">
                      <div class="dashboard-card-header">
                          <h4>Mis Mensajes</h4>
                      </div>
                      <div class="chat-container">
                        <div class="row">
                            <div class="col-xl-4 col-md-5">
                                <div class="chat-contact-list d-flex align-items-start">
                                    <div class="nav flex-column nav-pills me-3 w-100" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <h2 class="contact-list-title">Lista de Vendedores</h2>
                                        <div class="contact-list-body">
                                            @foreach ($chatUsers as $chatUser)
                                            @php
                                                $unseenMessages = \App\Models\Chat::where(['sender_id' => $chatUser->receiverProfile->id, 'receiver_id' => auth()->user()->id, 'seen' => 0])->exists();
                                            @endphp
                                            <button class="chat-user-profile"
                                                data-id="{{ $chatUser->receiverProfile->id }}"
                                                data-bs-toggle="pill"
                                                data-bs-target="#v-pills-home" type="button" role="tab"
                                                aria-controls="v-pills-home" aria-selected="true">
                                                <div class="contact-avatar {{ $unseenMessages ? 'msg-notification' : ''}}">
                                                    <img src="{{ asset($chatUser->receiverProfile->image) }}"
                                                        alt="user" class="img-fluid">
                                                    <span class="pending d-none" id="pending-6">0</span>
                                                </div>
                                                <div class="contact-details">
                                                    <h4>{{ $chatUser->receiverProfile->name }}</h4>
                                                </div>
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8 col-md-7">
                                <div class="chat-main-area">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show" id="v-pills-home" role="tabpanel"
                                            aria-labelledby="v-pills-home-tab">
                                            <div id="chat_box">
                                                <div class="chat-window">
                                                    <div class="chat-header">
                                                        <h2 id="chat-inbox-title">Selecciona un vendedor para chatear</h2>
                                                    </div>
                                                    <div class="chat-messages" data-inbox="">
                                                        <div class="empty-chat-placeholder">
                                                            <i class="fas fa-comments"></i>
                                                            <p>Selecciona un vendedor para comenzar a chatear</p>
                                                        </div>
                                                    </div>
                                                    <div class="chat-input-area">
                                                        <form id="message-form">
                                                            @csrf
                                                            <div class="input-group">
                                                                <input type="text" placeholder="Escribe tu mensaje aquí..." 
                                                                    class="message-box form-control" autocomplete="off" name="message">
                                                                <input type="hidden" name="receiver_id"
                                                                    value="" id="receiver_id">
                                                                <button type="submit" class="btn-send">
                                                                    <i class="fas fa-paper-plane send-button"></i>
                                                                </button>
                                                            </div>
                                                        </form>
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
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
// Mobile menu handling (mantener funcionalidad existente)
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
      if (mobileMenuToggle) {
          mobileMenuToggle.addEventListener('click', function() {
              const sidebar = document.querySelector('.user-sidebar');
              if (sidebar) {
                  sidebar.classList.toggle('active');
                  mobileMenuOverlay.classList.toggle('active');
                  document.body.classList.toggle('menu-open');
              }
          });
      }

      if (mobileMenuOverlay) {
          mobileMenuOverlay.addEventListener('click', function() {
              const sidebar = document.querySelector('.user-sidebar');
              if (sidebar) {
                  sidebar.classList.remove('active');
                  this.classList.remove('active');
                  document.body.classList.remove('menu-open');
              }
          });
      }
      const closeSidebarBtn = document.getElementById('closeSidebarBtn');

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', function() {
            const sidebar = document.querySelector('.user-sidebar');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

            if (sidebar && mobileMenuOverlay) {
                sidebar.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    }


  });
</script>
<script>
    const mainChatInbox = $('.chat-messages');

    function formatDateTime(dateTimeString) {
        const options = {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        }
        const formatedDateTime = new Intl.DateTimeFormat('es-Es', options).format(new Date(dateTimeString));
        return formatedDateTime;
    }

    function scrollTobottom() {
        mainChatInbox.scrollTop(mainChatInbox.prop("scrollHeight"));
    }

    $(document).ready(function(){
        $('.chat-user-profile').on('click', function(){
            let receiverId = $(this).data('id');
            let senderImage = $(this).find('img').attr('src');
            let chatUserName = $(this).find('h4').text();
            mainChatInbox.attr('data-inbox', receiverId);
            $('#receiver_id').val(receiverId);
            $(this).find('.contact-avatar').removeClass('msg-notification');
            
            // Activar el botón seleccionado
            $('.chat-user-profile').removeClass('active');
            $(this).addClass('active');
            
            $.ajax({
                method: 'get',
                url: '{{ route("user.get-messages") }}',
                data: {
                    receiver_id: receiverId
                },
                beforeSend: function() {
                    mainChatInbox.html("<div class='loading-messages'><i class='fas fa-spinner fa-spin'></i><p>Cargando mensajes...</p></div>");
                    // set chat inbox title
                    $('#chat-inbox-title').text(`Chateando con: ${chatUserName}`)
                },
                success: function(response) {
                    mainChatInbox.html(""); // Limpiar el indicador de carga
                    
                    if(response.length === 0) {
                        mainChatInbox.html("<div class='no-messages'><p>No hay mensajes previos. ¡Comienza la conversación!</p></div>");
                    } else {
                        $.each(response, function(index, value) {
                            if(value.sender_id == USER.id) {
                                var message = `<div class="message-item outgoing">
                                        <div class="message-avatar">
                                            <img src="${USER.image}"
                                                alt="user" class="img-fluid">
                                        </div>
                                        <div class="message-content">
                                            <p>${value.message}</p>
                                            <span class="message-time">${formatDateTime(value.created_at)}</span>
                                        </div>
                                    </div>`
                            } else {
                                var message = `<div class="message-item incoming">
                                        <div class="message-avatar">
                                            <img src="${senderImage}"
                                                alt="user" class="img-fluid">
                                        </div>
                                        <div class="message-content">
                                            <p>${value.message}</p>
                                            <span class="message-time">${formatDateTime(value.created_at)}</span>
                                        </div>
                                    </div>`
                            }
                            mainChatInbox.append(message);
                        });
                    }
                    
                    // scroll to bottom
                    scrollTobottom();
                },
                error: function(xhr, status, error) {
                    mainChatInbox.html("<div class='error-messages'><i class='fas fa-exclamation-circle'></i><p>Error al cargar mensajes</p></div>");
                },
                complete: function() {
                    // Asegurar que el área de entrada esté visible
                    $('.chat-input-area').show();
                }
            })
        })

        $('#message-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let messageData = $('.message-box').val();

            var formSubmitting = false;

            if(formSubmitting || messageData === "" ) {
                return;
            }

            // Eliminar cualquier mensaje de "no hay mensajes" si existe
            $('.no-messages').remove();

            // set message in inbox
            let message = `
            <div class="message-item outgoing">
                <div class="message-avatar">
                    <img src="${USER.image}"
                        alt="user" class="img-fluid">
                </div>
                <div class="message-content">
                    <p>${messageData}</p>
                    <span class="message-time">Enviando...</span>
                </div>
            </div>
            `
            mainChatInbox.append(message);
            $('.message-box').val('');
            scrollTobottom()

            $.ajax({
                method: 'POST',
                url: '{{ route("user.send-message") }}',
                data: formData,
                beforeSend: function() {
                    $('.send-button').prop('disabled', true);
                    formSubmitting = true;
                },
                success: function(response) {
                    // Actualizar el mensaje con la hora correcta
                    mainChatInbox.find('.message-item.outgoing:last-child .message-time').text(formatDateTime(new Date()));
                },
                error: function(xhr, status, error) {
                   toastr.error(xhr.responseJSON.message);
                   $('.send-button').prop('disabled', false);
                   formSubmitting = false;
                },
                complete: function() {
                    $('.send-button').prop('disabled', false);
                    formSubmitting = false;
                }
            })
        })
    })
</script>
@endpush

