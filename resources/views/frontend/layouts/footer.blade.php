@php
    $footerInfo = Cache::rememberForever('footer_info', function(){
            return \App\Models\FooterInfo::first();
    });
    $footerSocials = Cache::rememberForever('footer_socials', function(){
        return \App\Models\FooterSocial::where('status', 1)->get();
    });
    $footerGridTwoLinks = Cache::rememberForever('footer_grid_two', function(){
        return \App\Models\FooterGridTwo::where('status', 1)->get();
    });
    $footerTitle = \App\Models\FooterTitle::first();
    $footerGridThreeLinks =Cache::rememberForever('footer_grid_three', function(){
        return \App\Models\FooterGridThree::where('status', 1)->get();
    });
@endphp
<!-- HTML Footer Mejorado -->
<footer class="footer">
    <div class="container">
        <div class="row justify-content-between">
            <!-- Logo y Contacto -->
            <div class="col-xl-3 col-sm-7 col-md-6 col-lg-3">
                <div class="footer__content">
                    <a class="footer__logo" href="{{url('/')}}">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                        width="1024.000000pt" height="557.000000pt" viewBox="0 0 1024.000000 557.000000"
                        preserveAspectRatio="xMidYMid meet">

                        <g transform="translate(0.000000,557.000000) scale(0.100000,-0.100000)"
                        fill="currentColor" stroke="none">
                        <path d="M4505 5370 c-121 -31 -228 -67 -379 -126 -93 -36 -104 -44 -176 -119
                        -191 -197 -601 -686 -591 -703 4 -8 39 -41 77 -74 300 -262 416 -359 427 -356
                        7 2 66 61 132 132 l120 129 3 -907 2 -906 985 0 985 0 0 413 c0 982 3 1398 9
                        1395 4 -2 63 -61 130 -133 68 -71 127 -131 132 -133 12 -5 509 439 509 455 0
                        15 -493 595 -585 689 l-80 82 -155 60 c-170 66 -292 105 -387 123 l-62 11 -64
                        -64 c-230 -234 -614 -238 -851 -9 l-71 69 -110 -28z m1119 -250 c-41 -75 -149
                        -174 -236 -215 -227 -108 -502 -76 -664 77 -57 53 -139 156 -132 164 2 2 27
                        -18 56 -45 113 -106 277 -165 462 -165 197 0 351 56 464 169 33 34 65 62 69
                        63 5 1 -4 -20 -19 -48z"/>
                        <path d="M7700 5223 c-211 -35 -392 -208 -497 -473 -69 -173 -84 -267 -90
                        -549 -4 -179 -3 -242 6 -237 6 4 11 20 11 36 0 40 54 202 91 270 48 90 150
                        212 230 275 41 32 120 80 175 107 101 48 102 48 108 90 4 24 14 50 23 60 31
                        34 95 14 104 -32 1 -8 -1 -38 -5 -66 l-7 -51 51 -54 c28 -29 66 -79 85 -109
                        37 -61 95 -221 96 -266 1 -71 14 -68 52 13 75 162 127 363 127 495 0 144 -53
                        289 -135 371 -93 93 -276 145 -425 120z"/>
                        <path d="M8862 4840 c-81 -17 -152 -58 -216 -123 -137 -139 -226 -397 -226
                        -654 l0 -82 113 112 c115 115 177 157 290 197 50 18 53 21 72 77 23 67 58 93
                        97 72 13 -7 26 -17 30 -23 12 -18 9 -70 -7 -115 l-14 -41 55 -62 c64 -74 147
                        -234 169 -328 26 -108 30 -262 11 -360 -10 -46 -24 -100 -32 -119 -29 -70 -5
                        -38 63 81 146 259 210 435 234 647 39 347 -116 620 -399 705 -68 20 -185 28
                        -240 16z"/>
                        <path d="M7634 4476 c-178 -90 -342 -285 -388 -462 -33 -128 -16 -242 59 -387
                        25 -48 45 -95 45 -105 0 -31 -38 -62 -75 -62 -40 0 -75 37 -114 120 l-26 55
                        -16 -195 c-39 -443 -16 -590 106 -698 91 -80 271 -111 395 -68 137 48 231 216
                        270 486 17 121 37 340 30 340 -3 0 -14 -13 -23 -30 -21 -36 -72 -76 -107 -85
                        -20 -5 -33 0 -54 20 -24 22 -28 32 -23 62 3 19 19 51 34 70 101 127 129 168
                        171 252 l47 94 -1 116 c-1 102 -4 128 -32 211 -33 100 -85 196 -140 261 -42
                        49 -67 50 -158 5z"/>
                        <path d="M2044 4156 c-54 -24 -109 -114 -175 -290 -28 -75 -40 -94 -74 -119
                        -106 -77 -214 -106 -328 -87 -129 20 -201 85 -238 213 -34 119 -44 136 -95
                        159 -52 23 -99 19 -150 -13 -46 -28 -59 -69 -113 -354 -41 -213 -61 -355 -61
                        -440 0 -68 -2 -72 -41 -120 -23 -28 -54 -75 -68 -105 -23 -48 -26 -68 -26
                        -160 0 -95 3 -110 29 -163 50 -104 145 -160 329 -195 79 -15 147 -17 476 -15
                        381 2 386 2 605 -28 188 -27 261 -32 493 -36 832 -15 1226 142 1241 494 5 104
                        -16 171 -68 223 -44 44 -103 68 -269 110 -129 32 -263 81 -359 129 -34 17 -65
                        31 -70 31 -5 0 -77 -49 -162 -110 -118 -85 -160 -110 -185 -110 -60 0 -87 67
                        -42 107 12 11 74 57 137 102 63 46 116 86 118 91 2 5 -17 22 -42 39 l-45 31
                        -133 -93 c-219 -152 -234 -159 -276 -114 -46 48 -26 74 144 188 74 49 134 93
                        134 97 -1 10 -76 62 -91 62 -7 0 -83 -47 -169 -104 -87 -57 -167 -107 -178
                        -110 -22 -7 -61 8 -74 28 -16 23 -8 73 15 93 12 11 81 58 152 104 72 45 132
                        84 134 86 3 2 -40 49 -95 106 -55 56 -128 137 -162 180 -83 103 -139 127 -218
                        93z m-1043 -1120 c200 -72 254 -79 639 -81 382 -1 415 -4 765 -77 456 -94 525
                        -107 619 -119 122 -15 367 -7 528 16 153 23 165 18 107 -41 -94 -97 -234 -149
                        -514 -191 -198 -29 -628 -25 -964 10 -229 23 -308 27 -582 27 -336 0 -471 11
                        -623 50 -93 23 -141 56 -167 114 -23 52 -25 162 -4 214 15 35 81 112 96 112 4
                        0 49 -15 100 -34z"/>
                        <path d="M8854 4111 c-157 -53 -333 -211 -409 -366 -60 -122 -66 -230 -23
                        -403 23 -93 18 -118 -27 -148 -50 -32 -90 8 -119 118 -8 31 -16 47 -20 38
                        -159 -426 -179 -498 -179 -630 0 -124 24 -190 92 -253 126 -118 312 -157 438
                        -94 131 67 186 149 338 502 42 99 80 184 83 190 3 5 -14 -7 -39 -27 -97 -80
                        -189 -78 -189 3 0 46 16 67 72 95 105 53 193 169 233 309 28 94 17 252 -25
                        379 -33 97 -110 240 -153 283 -22 22 -22 22 -73 4z"/>
                        <path d="M5970 1850 c-115 -24 -209 -102 -252 -209 -19 -48 -23 -75 -23 -182
                        l0 -126 43 -86 c38 -77 61 -106 206 -256 100 -103 171 -185 185 -213 65 -136
                        18 -288 -90 -288 -60 0 -97 39 -109 115 -6 33 -10 76 -10 95 l0 35 -120 -3
                        -120 -3 0 -58 c0 -125 38 -235 106 -310 126 -140 383 -142 516 -4 20 21 52 70
                        70 108 31 67 33 76 33 185 0 199 -38 272 -262 501 -180 185 -203 222 -203 337
                        0 68 2 76 29 103 22 22 39 29 68 29 74 0 113 -54 113 -155 l0 -55 120 0 c102
                        0 122 3 127 16 3 9 1 53 -6 98 -16 103 -67 213 -119 256 -71 59 -204 90 -302
                        70z"/>
                        <path d="M720 1253 c0 -318 5 -618 10 -667 22 -215 143 -328 350 -329 172 0
                        288 74 343 219 22 58 22 67 25 707 l3 647 -131 0 -131 0 3 -82 c1 -46 1 -332
                        0 -636 l-2 -554 -29 -29 c-39 -39 -106 -41 -148 -5 l-28 24 -3 641 -2 641
                        -130 0 -130 0 0 -577z"/>
                        <path d="M1630 1056 l0 -776 125 0 125 0 2 291 3 291 43 3 c38 3 43 1 49 -23
                        4 -15 36 -146 73 -292 l66 -265 132 -3 c73 -1 132 0 132 3 0 3 -40 148 -90
                        321 l-90 317 35 38 c83 92 109 188 109 404 1 127 -3 173 -17 220 -34 112 -91
                        177 -192 222 -33 14 -78 18 -272 21 l-233 4 0 -776z m395 518 c55 -26 65 -61
                        64 -226 0 -212 -19 -245 -143 -255 l-66 -6 0 252 0 251 56 0 c31 0 71 -7 89
                        -16z"/>
                        <path d="M2530 1056 l0 -776 183 0 c269 0 351 18 436 96 75 67 111 174 111
                        335 0 195 -54 318 -159 362 l-35 15 30 15 c42 22 80 65 106 122 21 45 23 65
                        23 195 0 181 -14 229 -89 305 -85 87 -127 98 -388 103 l-218 4 0 -776z m388
                        520 c47 -24 57 -57 57 -181 0 -127 -10 -155 -60 -180 -18 -8 -56 -15 -84 -15
                        l-51 0 0 195 0 195 55 0 c31 0 68 -6 83 -14z m33 -634 c40 -44 51 -98 47 -226
                        -2 -92 -6 -116 -23 -138 -28 -39 -74 -58 -139 -58 l-56 0 0 231 0 231 71 -4
                        c65 -3 74 -7 100 -36z"/>
                        <path d="M3625 1808 c-17 -71 -253 -1513 -249 -1520 3 -4 58 -8 123 -8 l117 0
                        25 158 24 157 121 3 c119 3 122 2 127 -20 3 -13 15 -85 28 -160 l22 -138 125
                        0 c124 0 124 0 119 23 -3 12 -29 155 -57 317 -77 447 -200 1151 -206 1183 l-6
                        27 -154 0 c-151 0 -154 0 -159 -22z m210 -668 l41 -310 -88 0 c-87 0 -88 0
                        -88 25 0 14 16 134 35 267 20 134 39 264 42 291 3 26 8 45 11 42 3 -3 24 -145
                        47 -315z"/>
                        <path d="M4350 1055 l0 -775 115 0 115 0 2 457 3 457 57 -174 c31 -96 97 -302
                        148 -458 l91 -283 112 3 112 3 3 773 2 772 -120 0 -121 0 3 -440 c2 -242 1
                        -440 -2 -440 -3 0 -68 197 -145 438 l-141 437 -117 3 -117 3 0 -776z"/>
                        <path d="M6500 1705 l0 -125 115 0 115 0 0 -650 0 -650 125 0 125 0 0 650 0
                        649 118 3 117 3 3 123 3 122 -361 0 -360 0 0 -125z"/>
                        <path d="M7325 1788 c7 -24 74 -233 149 -464 l136 -421 0 -312 0 -311 120 0
                        c107 0 120 2 121 18 1 9 2 152 3 317 l1 300 144 445 c79 245 146 451 148 458
                        4 9 -26 12 -130 12 l-134 0 -52 -202 c-29 -112 -62 -240 -74 -285 -11 -46 -24
                        -83 -27 -83 -3 0 -11 24 -18 53 -7 28 -40 157 -74 285 l-61 232 -133 0 -132 0
                        13 -42z"/>
                        <path d="M8288 1072 c-2 -417 -2 -766 0 -775 3 -15 30 -17 293 -17 l289 0 0
                        125 0 125 -170 0 -170 0 2 650 3 650 -122 0 -123 0 -2 -758z"/>
                        <path d="M9020 1055 l0 -775 295 0 295 0 0 125 0 125 -170 0 -170 0 0 210 0
                        209 133 3 132 3 3 123 3 122 -136 0 -135 0 0 190 0 190 165 0 165 0 0 125 0
                        125 -290 0 -290 0 0 -775z"/>
                        </g>
                        </svg>

                    </a>
                    <div class="footer__contact">
                        <a class="footer__contact-item" href="callto:{{@$footerInfo->phone}}">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{@$footerInfo->phone}}</span>
                        </a>
                        <a class="footer__contact-item" href="mailto:{{@$footerInfo->email}}">
                            <i class="far fa-envelope"></i>
                            <span>{{@$footerInfo->email}}</span>
                        </a>
                        <p class="footer__contact-item">
                            <i class="fal fa-map-marker-alt"></i>
                            <span>{{@$footerInfo->address}}</span>
                        </p>
                    </div>
                    <ul class="footer__social">
                        @foreach ($footerSocials as $link)
                        <li><a class="{{strtolower(str_replace('fab fa-', '', $link->icon))}}" href="{{$link->url}}"><i class="{{$link->icon}}"></i></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Enlaces Columna 1 -->
            <div class="col-xl-2 col-sm-5 col-md-4 col-lg-2">
                <div class="footer__content">
                    <h5 class="footer__title">{{$footerTitle->footer_grid_two_title}}</h5>
                    <ul class="footer__menu">
                        @foreach ($footerGridTwoLinks as $link)
                            <li><a href="{{$link->url}}"><i class="fas fa-angle-right"></i> {{$link->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Enlaces Columna 2 -->
            <div class="col-xl-2 col-sm-5 col-md-4 col-lg-2">
                <div class="footer__content">
                    <h5 class="footer__title">{{$footerTitle->footer_grid_three_title}}</h5>
                    <ul class="footer__menu">
                        @foreach ($footerGridThreeLinks as $link)
                            <li><a href="{{$link->url}}"><i class="fas fa-angle-right"></i> {{$link->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Boletín de Suscripción -->
            <div class="col-xl-4 col-sm-7 col-md-8 col-lg-5">
                <div class="footer__content footer__newsletter">
                    <h5 class="footer__title">Suscríbete a nuestro boletín</h5>
                    <p>Recibe toda la información más reciente sobre eventos, ventas y ofertas.</p>
                    <form action="" method="POST" id="newsletter" class="footer__form">
                        @csrf
                        <div class="footer__form-group">
                            <input type="email" placeholder="Correo electrónico" name="email" class="footer__input">
                            <button type="submit" class="footer__btn">Suscribirse</button>
                        </div>
                    </form>
                    <div class="footer__payment">
                        <p>Usamos pagos seguros para:</p>
                        <img src="{{asset('frontend/images/credit2.png')}}" alt="tarjeta" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="footer__copyright">
                        <p>{{@$footerInfo->copyright}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer__logo {
        color: var(--general);
    }

    .footer__logo :hover{
        color: var(--general);
    }

</style>