<div id="header-sticky" class="header__main-area d-none d-xl-block">
    <div class="container">
        <div class="header__for-megamenu p-relative">
            <div class="row align-items-center">
                <div class="col-xl-3">
                    <div class="header__logo">
                        <a href="index.html"><img src="{{ asset('eshago_logo.png') }}" alt="logo"></a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="header__menu main-menu text-center">
                        <nav id="mobile-menu">
                            <ul>
                                <li class="has-homemenu">
                                    <a href="/">Home</a>
                                </li>
                                <li class="has-megamenu" >
                                    <a href="/shopping">Shop</a>
                                </li>
                                <li class="">
                                    <a href="about.html">What is Eshagi</a>
                                </li>
                                <li><a href="about.html">About Us</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="header__info d-flex align-items-center">
                        @auth
                            <div class="header__info-search tpcolor__purple ml-10">
                                <button class="tp-search-toggle"><i class="icon-search"></i></button>
                            </div>
                            <div class="header__info-user tpcolor__yellow ml-10">
                                <a href="/home" class="btn btn-warning">My Account</a>
                            </div>
                        @else
                            <div class="header__info-search tpcolor__purple ml-10">
                                <button class="tp-search-toggle"><i class="icon-search"></i></button>
                            </div>
                            <div class="header__info-user tpcolor__yellow ml-10">
                                <a href="/login" class="btn btn-primary">Join Today</a>
                            </div>
                        @endif
                        <div class="header__info-cart tpcolor__oasis ml-10 tp-cart-toggle">
                            <button><i><img src="{{ asset('shop/assets/img/icon/cart-1.svg') }}" alt=""></i>
                                <span id="cartNum"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
