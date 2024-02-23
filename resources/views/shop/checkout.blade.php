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
    <section class="coupon-area pt-10 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="coupon-accordion">
                        <!-- ACCORDION START -->
                        <h3>Logged in as <span id="showlogin">Mpho Makoni</span></h3>
                        <div id="checkout-login" class="coupon-content">
                            <div class="coupon-info">
                                <p class="coupon-text">Quisque gravida turpis sit amet nulla posuere lacinia. Cras sed est
                                    sit amet ipsum luctus.</p>
                                <form action="#">
                                    <p class="form-row-first">
                                        <label>Username or email <span class="required">*</span></label>
                                        <input type="text" >
                                    </p>
                                    <p class="form-row-last">
                                        <label>Password <span class="required">*</span></label>
                                        <input type="text">
                                    </p>
                                    <p class="form-row">
                                        <button class="tp-btn tp-color-btn" type="submit">Login</button>
                                        <label>
                                            <input type="checkbox">
                                            Remember me
                                        </label>
                                    </p>
                                    <p class="lost-password">
                                        <a href="#">Lost your password?</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="coupon-accordion">
                        <!-- ACCORDION START -->
                        <h3>Have a coupon? <span id="showcoupon">Click here to enter your code</span></h3>
                        <div id="checkout_coupon" class="coupon-checkout-content">
                            <div class="coupon-info">
                                <form action="#">
                                    <p class="checkout-coupon">
                                        <input type="text" placeholder="Coupon Code">
                                        <button class="tp-btn tp-color-btn" type="submit">Apply Coupon</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                        <!-- ACCORDION END -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- coupon-area end -->

    <!-- checkout-area start -->
    <section class="checkout-area pb-50">
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                           <div class="col-md-6">
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
                                           <tr class="cart_item">
                                               <td class="product-name">
                                                   Vestibulum suscipit <strong class="product-quantity"> × 1</strong>
                                               </td>
                                               <td class="product-total">
                                                   <span class="amount">$165.00</span>
                                               </td>
                                           </tr>
                                           <tr class="cart_item">
                                               <td class="product-name">
                                                   Vestibulum dictum magna <strong class="product-quantity"> × 1</strong>
                                               </td>
                                               <td class="product-total">
                                                   <span class="amount">$50.00</span>
                                               </td>
                                           </tr>
                                           </tbody>
                                           <tfoot>
                                           <tr class="cart-subtotal">
                                               <th>Cart Subtotal</th>
                                               <td><span class="amount">$215.00</span></td>
                                           </tr>
                                           <tr class="shipping">
                                               <th>Delivery Address</th>
                                               <td>
                                                   <ul>
                                                       <li>
                                                            16676 Ganges Road Belvedere
                                                       </li>
                                                       <li>

                                                       </li>
                                                   </ul>
                                               </td>
                                           </tr>
                                           <tr class="order-total">
                                               <th>Order Total</th>
                                               <td><strong><span class="amount">$215.00</span></strong>
                                               </td>
                                           </tr>
                                           </tfoot>
                                       </table>
                                   </div>
                                   <div class="payment-method">
                                       <div class="order-button-payment mt-20">
                                           <button type="submit" class="tp-btn tp-color-btn w-100 banner-animation">Place Order</button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                            <div class="col-md-6">
                                    <div class="your-order mb-30 ">
                                        <h3>Your Creditors</h3>
                                        <div class="your-order-table table-responsive">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th class="product-name">Name</th>
                                                    <th class="product-total">Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="cart_item">
                                                    <td class="product-name">
                                                        Retail Flex <strong class="product-quantity"> × 1</strong>
                                                    </td>
                                                    <td class="product-total">
                                                        <span class="amount">$165.00</span>
                                                    </td>
                                                </tr>
                                                <tr class="cart_item">
                                                    <td class="product-name">
                                                        Capri <strong class="product-quantity"> × 1</strong>
                                                    </td>
                                                    <td class="product-total">
                                                        <span class="amount">$50.00</span>
                                                    </td>
                                                </tr>
                                                <tr class="cart_item">
                                                    <td class="product-name">
                                                        Commision (10% of Order Total) <strong class="product-quantity"></strong>
                                                    </td>
                                                    <td class="product-total">
                                                        <span class="amount">$50.00</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot>
                                                <tr class="cart-subtotal">
                                                    <th>Cart Subtotal</th>
                                                    <td><span class="amount">$215.00</span></td>
                                                </tr>
                                                <tr class="shipping">
                                                    <th>Shipping</th>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <input type="radio" name="shipping">
                                                                <label>
                                                                    Flat Rate: <span class="amount">$7.00</span>
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <input type="radio" name="shipping">
                                                                <label>Free Shipping:</label>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr class="order-total">
                                                    <th>Order Total</th>
                                                    <td><strong><span class="amount">$215.00</span></strong>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
