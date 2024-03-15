@extends('shop.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb__area grey-bg pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tp-breadcrumb__content">
                        <div class="tp-breadcrumb__list">
                            <span class="tp-breadcrumb__active"><a href="index.html">Home</a></span>
                            <span class="dvdr">/</span>
                            <span class="tp-breadcrumb__active"><a href="index.html">{{ $product->category->category_name }}</a></span>
                            <span class="dvdr">/</span>
                            <span>{{ $product->pname }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->

    <!-- shop-details-area-start -->
    <section class="shopdetails-area grey-bg pb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-md-12">
                    <div class="tpdetails__area mr-60 pb-30">
                        <div class="tpdetails__product mb-30">
                            <div class="tpdetails__title-box">
                                <h3 class="tpdetails__title">{{ $product->pname }}</h3>
                                <ul class="tpdetails__brand">
                                    <li> Brands: <a href="#">{{ $product->creator }}</a> </li>
                                    <li>
                                        <i class="icon-star_outline1"></i>
                                        <i class="icon-star_outline1"></i>
                                        <i class="icon-star_outline1"></i>
                                        <i class="icon-star_outline1"></i>
                                        <i class="icon-star_outline1"></i>
                                        <b>02 Reviews</b>
                                    </li>
                                    <li>
                                        SKU: <span>Sku</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="tpdetails__box">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="tpdetails__grid-img mb-10">
                                                    <img src="{{asset('shop/assets/img/product/products9-min.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="tpdetails__grid-img mb-10">
                                                    <img src="{{ asset('shop/assets/img/product/products11-min.jpg') }}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="tpdetails__grid-img mb-10">
                                                    <img src="{{ asset('shop/assets/img/product/products12-min.jpg') }}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="tpdetails__grid-img mb-10">
                                                    <img src="{{ asset('shop/assets/img/product/products13-min.jpg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="product__details">
                                            <div class="product__details-price-box">
                                                <h5 class="product__details-price">{{ $product->partner->localel->symbol }}  {{ $product->price }}</h5>
                                                @php
                                                    $prodDesc = explode(',',$product->descrip);
                                                 @endphp
                                                <ul class="product__details-info-list">
                                                   @php
                                                   for($i = 0 ; $i < count($prodDesc); $i++){
                                                       echo '<li>'.$prodDesc[$i],'</li>';
                                                   }
                                                  @endphp
                                                </ul>
                                            </div>
                                            <div class="product__details-cart">
                                                <div class="product__details-quantity d-flex align-items-center mb-15">
                                                    <b>Qty:</b>
                                                    <div class="product__details-count mr-10">
                                                        <span class="cart-minus"><i class="far fa-minus"></i></span>
                                                        <input class="tp-cart-input" type="text" value="1">
                                                        <span class="cart-plus"><i class="far fa-plus"></i></span>
                                                    </div>
                                                    <div class="product__details-btn">
                                                        <button type="button" class="tp-btn-2 addToCart" data-product-id="{{ $product->id }}" href="cart.html">Add to cart</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product__details-stock mb-25">
                                                <ul>
                                                    <li>Availability: <i>54 Instock</i></li>
                                                    <li>Categories: <span>{{ $product->category->category_name }}</span></li>
                                                </ul>
                                            </div>
                                            <div class="product__details-payment text-center">
                                                <img src="shop/assets/img/shape/payment-2.png" alt="">
                                                <span>Guarantee safe & Secure checkout</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tpdescription__box">
                            <div class="tpdescription__box-center d-flex align-items-center justify-content-center">
                                <nav>
                                    <div class="nav nav-tabs"  role="tablist">
                                        <button class="nav-link active" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button" role="tab" aria-controls="nav-description" aria-selected="true">Product Description</button>
                                        <button class="nav-link" id="nav-info-tab" data-bs-toggle="tab" data-bs-target="#nav-information" type="button" role="tab" aria-controls="nav-information" aria-selected="false">ADDITIONAL INFORMATION</button>
                                        <button class="nav-link" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab" aria-controls="nav-review" aria-selected="false">Reviews (1)</button>
                                    </div>
                                </nav>
                            </div>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab" tabindex="0">
                                    <div class="tpdescription__content">
                                        <p>{{ $product->descrip }}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-information" role="tabpanel" aria-labelledby="nav-info-tab" tabindex="0">
                                    <div class="tpdescription__content">
                                        <p>No addtional Content</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab" tabindex="0">
                                    <div class="tpreview__wrapper">
                                        <h4 class="tpreview__wrapper-title">1 review for Cheap and delicious fresh chicken</h4>
                                        <div class="tpreview__comment">
                                            <div class="tpreview__comment-img mr-20">
                                                <img src="shop/assets/img/testimonial/test-avata-1.png" alt="">
                                            </div>
                                            <div class="tpreview__comment-text">
                                                <div class="tpreview__comment-autor-info d-flex align-items-center justify-content-between">
                                                    <div class="tpreview__comment-author">
                                                        <span>admin</span>
                                                    </div>
                                                    <div class="tpreview__comment-star">
                                                        <i class="icon-star_outline1"></i>
                                                        <i class="icon-star_outline1"></i>
                                                        <i class="icon-star_outline1"></i>
                                                        <i class="icon-star_outline1"></i>
                                                        <i class="icon-star_outline1"></i>
                                                    </div>
                                                </div>
                                                <span class="date mb-20">--April 9, 2022: </span>
                                                <p>very good</p>
                                            </div>
                                        </div>
                                        <div class="tpreview__form">
                                            <h4 class="tpreview__form-title mb-25">Add a review </h4>
                                            <form action="#">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="tpreview__input mb-30">
                                                            <input type="text" placeholder="Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="tpreview__input mb-30">
                                                            <input type="email" placeholder="Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="tpreview__star mb-20">
                                                            <h4 class="title">Your Rating</h4>
                                                            <div class="tpreview__star-icon">
                                                                <a href="#"><i class="icon-star_outline1"></i></a>
                                                                <a href="#"><i class="icon-star_outline1"></i></a>
                                                                <a href="#"><i class="icon-star_outline1"></i></a>
                                                                <a href="#"><i class="icon-star_outline1"></i></a>
                                                                <a href="#"><i class="icon-star_outline1"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="tpreview__input mb-30">
                                                            <textarea name="text" placeholder="Message"></textarea>
                                                            <div class="tpreview__submit mt-30">
                                                                <button class="tp-btn">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-12">
                    <div class="tpsidebar pb-30">
                        <div class="tpsidebar__warning mb-30">
                            <ul>
                                <li>
                                    <div class="tpsidebar__warning-item">
                                        <div class="tpsidebar__warning-icon">
                                            <i class="icon-package"></i>
                                        </div>
                                        <div class="tpsidebar__warning-text">
                                            <p>Free shipping apply to all <br> orders over $90</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tpsidebar__warning-item">
                                        <div class="tpsidebar__warning-icon">
                                            <i class="icon-shield"></i>
                                        </div>
                                        <div class="tpsidebar__warning-text">
                                            <p>Guaranteed 100% Organic <br>  from nature farms</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tpsidebar__warning-item">
                                        <div class="tpsidebar__warning-icon">
                                            <i class="icon-package"></i>
                                        </div>
                                        <div class="tpsidebar__warning-text">
                                            <p>60 days returns if you change <br> your mind</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tpsidebar__banner mb-30">
                            <img src="shop/assets/img/shape/sidebar-product-1.png" alt="">
                        </div>
                        <div class="tpsidebar__product">
                            <h4 class="tpsidebar__title mb-15">Recent Products</h4>
                            <div class="tpsidebar__product-item">
                                <div class="tpsidebar__product-thumb p-relative">
                                    <img src="shop/assets/img/product/sidebar-pro-1.jpg" alt="">
                                    <div class="tpsidebar__info bage">
                                        <span class="tpproduct__info-hot bage__hot">HOT</span>
                                    </div>
                                </div>
                                <div class="tpsidebar__product-content">
                                 <span class="tpproduct__product-category">
                                    <a href="shop-details-3.html">Fresh Fruits</a>
                                 </span>
                                    <h4 class="tpsidebar__product-title">
                                        <a href="shop-details-3.html">Fresh Mangosteen 100% Organic From VietNamese</a>
                                    </h4>
                                    <div class="tpproduct__rating mb-5">
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                    </div>
                                    <div class="tpproduct__price">
                                        <span>$56.00</span>
                                        <del>$19.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="tpsidebar__product-item">
                                <div class="tpsidebar__product-thumb p-relative">
                                    <img src="shop/assets/img/product/sidebar-pro-2.jpg" alt="">
                                    <div class="tpsidebar__info bage">
                                        <span class="tpproduct__info-hot bage__hot">HOT</span>
                                    </div>
                                </div>
                                <div class="tpsidebar__product-content">
                                 <span class="tpproduct__product-category">
                                    <a href="shop-details-3.html">Fresh Fruits</a>
                                 </span>
                                    <h4 class="tpsidebar__product-title">
                                        <a href="shop-details-3.html">Fresh Mangosteen 100% Organic From VietNamese</a>
                                    </h4>
                                    <div class="tpproduct__rating mb-5">
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                    </div>
                                    <div class="tpproduct__price">
                                        <span>$56.00</span>
                                        <del>$19.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="tpsidebar__product-item">
                                <div class="tpsidebar__product-thumb p-relative">
                                    <img src="shop/assets/img/product/sidebar-pro-3.jpg" alt="">
                                    <div class="tpsidebar__info bage">
                                        <span class="tpproduct__info-hot bage__hot">HOT</span>
                                    </div>
                                </div>
                                <div class="tpsidebar__product-content">
                                 <span class="tpproduct__product-category">
                                    <a href="shop-details-3.html">Fresh Fruits</a>
                                 </span>
                                    <h4 class="tpsidebar__product-title">
                                        <a href="shop-details-4.html">Fresh Mangosteen 100% Organic From VietNamese</a>
                                    </h4>
                                    <div class="tpproduct__rating mb-5">
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                    </div>
                                    <div class="tpproduct__price">
                                        <span>$56.00</span>
                                        <del>$19.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- shop-details-area-end -->

    <!-- product-area-start -->
    <section class="product-area whight-product pt-75 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="tpdescription__product-title mb-20">Related Products</h5>
                </div>
            </div>
            <div class="tpproduct__arrow double-product p-relative">
                <div class="swiper-container tpproduct-active tpslider-bottom p-relative">
                    <div class="swiper-wrapper">
                        @foreach(\App\Models\Product::where('partner_id', $product->partner->id)->get() as $relatedProduct) @endforeach
                        <div class="swiper-slide">
                            <div class="tpproduct p-relative">
                                <div class="tpproduct__thumb p-relative text-center">
                                    <a href="#"><img src="{{ $product->product_image ? 'merchants/products/'.$product->product_image :  'shop/assets/img/product/products1-min.jpg' }}" alt=""></a>
                                    <a class="tpproduct__thumb-img" href="/shopping/product/{{ $product->id }}"><img src="{{ $product->product_image ? 'merchants/products/'.$product->product_image :  'shop/assets/img/product/products1-min.jpg' }}" alt=""></a>
                                    <div class="tpproduct__info bage">
                                        <span class="tpproduct__info-discount bage__discount">00</span>
                                        <span class="tpproduct__info-hot bage__hot">{{ $relatedProduct->partner->merchantname  }}</span>
                                    </div>
                                    <div class="tpproduct__shopping">
                                        <a class="tpproduct__shopping-wishlist" href="wishlist.html"><i class="icon-heart icons"></i></a>
                                        <a class="tpproduct__shopping-wishlist" href="#"><i class="icon-layers"></i></a>
                                        <a class="tpproduct__shopping-cart" href="#"><i class="icon-eye"></i></a>
                                    </div>
                                </div>
                                <div class="tpproduct__content">
                                 <span class="tpproduct__content-weight">
                                    <a href="shop-details.html">{{ $relatedProduct->model }}</a>
                                 </span>
                                    <h4 class="tpproduct__title">
                                        <a href="shop-details-top-.html">{{ $relatedProduct->pname }}</a>
                                    </h4>
                                    <div class="tpproduct__rating mb-5">
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                        <a href="#"><i class="icon-star_outline1"></i></a>
                                    </div>
                                    <div class="tpproduct__price">
                                        <span>{{ $relatedProduct->price_with_currency }}</span>
                                        <del>{{ $relatedProduct->price + 10 }}</del>
                                    </div>
                                </div>
                                <div class="tpproduct__hover-text">
                                    <div class="tpproduct__hover-btn d-flex justify-content-center mb-10">
                                        <button type="button" class="tp-btn-2 addToCart" data-product-id="{{ $product->id }}" href="cart.html">Add to cart</button>
                                    </div>
                                    <div class="tpproduct__descrip">
                                       <p>{{ $relatedProduct->descrip }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
    $(document).ready(function() {

        $('.addToCart').click(function () {
            var productId = $(this).data('product-id');
            var cart = getStorage();

            if(!cart.includes(productId)){
                cart.push(productId)
            }
            console.log(cart);
            localStorage.setItem('cart', cart.join(","))
        });

        function getStorage()
        {
            let cartItems = localStorage.getItem('cart');
            if(cartItems == undefined)
            {
                return [];
            }
            else {
                return cartItems.split(',');
            }
        }

    });
</script>

@endsection
