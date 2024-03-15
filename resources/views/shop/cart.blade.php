@extends('shop.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb__area pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tp-breadcrumb__content">
                        <div class="tp-breadcrumb__list">
                            <span class="tp-breadcrumb__active"><a href="index.html">Home</a></span>
                            <span class="dvdr">/</span>
                            <span>Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->
@php
    $subTotal = \App\Models\Product::getOrderTotal($products);
    $comission = \App\Models\Product::getCommission($products);
    $total = $subTotal + $comission;

@endphp
    <!-- cart area -->
    <section class="cart-area pb-80">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="/checkout" method="post" id="myForm">
                        @csrf
                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="product-thumbnail">Images</th>
                                        <th class="cart-product-name">Product</th>
                                        <th class="product-price">Unit Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-subtotal">Total</th>
                                        <th class="product-remove">Remove</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $index = 1; @endphp
                                    @foreach($products as $product)
                                       <tr class="_prod{{$product->id}}">
                                        <td class="product-thumbnail">
                                            <a href="shop-details.html">
                                                <img src="merchants/products/{{$product->product_image}}" alt="">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <a href="shop-details.html">{{ $product->pname }}</a>
                                        </td>
                                        <td class="product-price">
                                            <span class="amount">${{ $product->price }}</span>
                                        </td>
                                        <td class="product-quantity">
                                            <span class="cart-minus">-</span>
                                            <input class="cart-input" type="text" name="quantity_{{$index}}" value="1">
                                            <span class="cart-plus">+</span>
                                        </td>
                                        <td class="product-subtotal">
                                            <input name="subTotal_{{ $index  }}" type="hidden" value="{{ $product->price }}">
                                            <span class="amount" id="subTotal_{{ $index  }}">${{ $product->price }}</span>
                                        </td>
                                        <td class="product-remove">
                                            <button type="button" class="btn-remove" data-product-id="{{$product->id}}" href="#"><i class="fa fa-times"></i></button>
                                        </td>
                                           <input type="hidden" id="{{ $index }}" name="{{$product->id}}" value="1">
                                    </tr>
                                        @php $index++ @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="coupon-all">
                                        <div class="coupon2">
                                            <button  class="tp-btn tp-color-btn banner-animation" id="updateCart" name="update_cart" type="button">Update cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="row justify-content-end">
                            <div class="col-md-5 ">
                                <div class="cart-page-total">
                                    <h2>Cart totals</h2>
                                    <ul class="mb-20">
                                        <li>Total <span id="totalValue"> {{ $subTotal }}</span></li>
                                    </ul>
                                    <button class="tp-btn tp-color-btn banner-animation" type="submit">Proceed to Checkout</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- cart area end-->
    <script>
        $(document).ready(function() {
            $('#updateCart').click(function(event) {
                let cart = [];
                let index = {{ $index }}
                for ( let i = 1; i < index; i++) {
                    let quantity = 'quantity_'+i;
                    let subTotal = 'subTotal_'+i;
                    let value = $('input[name="' + quantity + '"]').val() * $('input[name="' + subTotal + '"]').val();
                    cart.push(value);
                    $('#'+subTotal).empty().append('$'+value)
                    $('#'+i).attr('value', $('input[name="' + quantity + '"]').val())
                }
                let sum = 0;
                for (let i = 0; i < cart.length; i++) {
                    if (!isNaN(cart[i])) {
                        sum += cart[i];
                    }
                }
                $('#subTotalAmount').empty().append(sum);
                $('#comissionTotal').empty().append((0.03 * sum).toFixed(2));
                $('#totalValue').empty().append(1.03 * sum);
            });

            $('.btn-remove').click(function () {
                var productId = $(this).data('product-id');
                let cartArray = localStorage.getItem('cart').split(',');
                const index = cartArray.indexOf(productId.toString())

                if(index !== -1){
                    cartArray.splice(index,1);
                    localStorage.setItem('cart', cartArray.join(','))
                }
                $('._prod'+ productId).empty();
            });
        });
    </script>
@endsection
