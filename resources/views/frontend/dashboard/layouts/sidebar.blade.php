<div class="user-sidebar">
  <div class="user-sidebar-header">
    <div class="user-profile-img">
      <img src="{{asset(auth()->user()->image)}}" alt="{{auth()->user()->name}}" class="img-fluid rounded-circle">
    </div>
    <h5 class="user-name">{{auth()->user()->name}}</h5>
    <span class="user-type">{{ auth()->user()->role === 'vendor' ? 'Vendedor' : 'Cliente' }}</span>
    <button id="closeSidebarBtn" class="sidebar-toggler d-block d-lg-none">
      <i class="fas fa-times"></i>
    </button>
  </div>

  <div class="user-sidebar-body">
    <ul class="user-sidebar-menu">

      <li>
        <a class="{{setActive(['user.profile'])}}" href="{{route('user.profile')}}">
          <i class="fas fa-user-circle"></i>
          <span>Mi Perfil</span>
        </a>
      </li>
      

      <li>
        <a class="{{setActive(['user.orders.*'])}}" href="{{route('user.orders.index')}}">
          <i class="fas fa-shopping-bag"></i>
          <span>Mis Pedidos</span>
        </a>
      </li>

      <li>
        <a class="{{setActive(['user.review.*'])}}" href="{{route('user.review.index')}}">
          <i class="fas fa-star"></i>
          <span>Mis Reseñas</span>
        </a>
      </li>

      <li>
        <a class="{{ setActive(['user.wishlist.*']) }}" href="{{ route('user.wishlist.index') }}">
          <i class="fas fa-heart"></i>
          <span>Mis Favoritos</span>
        </a>
      </li>

      <li>
        <a class="{{setActive(['user.address.*'])}}" href="{{route('user.address.index')}}">
          <i class="fas fa-map-marker-alt"></i>
          <span>Mis Direcciones</span>
        </a>
      </li>

      <li>
        <a class="{{setActive(['user.messages.index'])}}" href="{{route('user.messages.index')}}">
          <i class="fas fa-envelope"></i>
          <span>Mensajes</span>
          <!-- Opcionalmente agregar contador de mensajes -->
          <span class="badge rounded-pill bg-danger ms-auto">3</span>
        </a>
      </li>

      @if (auth()->user()->role === 'vendor')
      <li>
        <a class="{{setActive(['vendor.dashbaord'])}}" href="{{route('vendor.dashbaord')}}">
          <i class="fas fa-store"></i>
          <span>Panel de Vendedor</span>
        </a>
      </li>
      @endif

      @if (auth()->user()->role !== 'vendor')
      <li>
        <a class="{{setActive(['user.vendor-request.*'])}}" href="{{route('user.vendor-request.index')}}">
          <i class="fas fa-store-alt"></i>
          <span>Solicitar ser vendedor</span>
        </a>
      </li>
      @endif

      <li class="separator"></li>

      <!-- <li>
        <a href="{{url('/')}}" class="home-link">
          <i class="fas fa-home"></i>
          <span>Página Principal</span>
        </a>
      </li> -->

      <li>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
          @csrf
          <button type="submit" class="logout-button">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar Sesión</span>
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>