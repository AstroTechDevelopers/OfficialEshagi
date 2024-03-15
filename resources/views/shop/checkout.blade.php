@extends('shop.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb__area pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tp-breadcrumb__content">
                        <div class="tp-breadcrumb__list">
                            <span class="tp-breadcrumb__active"><a href="/">Home</a></span>
                            <span class="dvdr">/</span>
                            <span><a href="/shop">Shop</a></span>
                            <span class="dvdr">/</span>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->

    <!-- coupon-area start -->
    <section class="coupon-area pt-10 pb-30"></section>
    <!-- coupon-area end -->
@php
  $cartTotal =  \App\Models\Product::getOrderTotal($products);
  $adminCommision = \App\Models\Product::getCommission($products);
@endphp
    <!-- checkout-area start -->
    <section class="checkout-area pb-50">
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="your-order mb-30 ">
                            <h3>Your order</h3>
                            <div class="your-order-table table-responsive">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="product-name">Product</th>
                                        <th class="product-total">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                    <tr class="cart_item">
                                        <td class="product-name">
                                            {{ $order->product->pname }} <strong class="product-quantity"> * {{ $order->quantity }}</strong>
                                        </td>
                                        <td class="product-total">
                                            <span class="amount">{{ $order->product->price * $order->quantity }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="cart-subtotal">
                                        <th>Cart Subtotal</th>
                                        <td><span class="amount">{{ $subTotal }}</span></td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Order Total</th>
                                        <td><strong><span class="amount">{{ $subTotal }}</span></strong>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="payment-method">
                                <h3>Merchants and Totals</h3>
                                <div class="accordion" id="checkoutAccordion">
                                    @foreach($creditors as $creditor)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="checkoutOne">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bankOne" aria-expanded="false" aria-controls="bankOne">
                                                {{ \App\Models\Partner::find($creditor->partner_id)->partner_name }}
                                            </button>
                                        </h2>
                                        <div id="bankOne" class="accordion-collapse collapse" aria-labelledby="checkoutOne" data-bs-parent="#checkoutAccordion" style="">
                                            <div class="accordion-body">
                                                Total Amount : {{ $creditor->total_amount }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <br>
                                <hr>
                                <div class="order-button-payment mt-20">
                                    <button type="button" id="placeOrder" class="tp-btn tp-color-btn w-100 banner-animation">Place order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $('#placeOrder').click(function(event) {
            var element = "Relax as we redirect you to application page ...  <img src='//s.svgbox.net/loaders.svg?fill=white&ic=tail-spin' style='width:30px'>";
            $('#placeOrder').empty().append(element)

            setTimeout(function() {
                window.location.href = "http://localhost:8000/loans/create?id={{$orderId}}";
            }, 2000)
        });
    </script>
@endsection
