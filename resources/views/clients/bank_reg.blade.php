@extends('shop.layouts.app')
@section('content')
    <main class="mt-35 pb-155">
        <section class="checkout-area pb-50">
          <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="checkbox-form">
                            <h3>Bank Signup</h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="country-select">
                                        <label>Bank <span class="required">*</span></label>
                                        <select>
                                            @foreach(\App\Models\Bank::all() as $bank)
                                                <option>{{ $bank->bank }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>Account # <span class="required">*</span></label>
                                        <input type="text" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-12" id="submit">
                                    <div class="checkout-form-list">
                                        <button class="btn btn-success" id="placeOrder">Submit</button>
                                    </div>
                                </div>
                                <div id="otp">
                                    <div class="col-md-6" >
                                        <div class="checkout-form-list">
                                            <label style="color: green">Account found please check the OTP sent to verify your identity <span class="required">*</span></label>
                                            <input class="form-control" type="number" placeholder="112345">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <button class="btn btn-primary" id="verifyOtp">Verify OTP</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        </section>
    </main>
    <script>
        $(document).ready( function (){
            $('#otp').hide()
            $('#placeOrder').click(function(event) {
                var element = "Relax as we contact your bank ...  <img src='//s.svgbox.net/loaders.svg?fill=white&ic=tail-spin' style='width:30px'>";
                $('#placeOrder').empty().append(element)
                setTimeout(function() {
                    $('#submit').hide()
                    $('#otp').show()
                }, 4000)
            });

            $('#verifyOtp').click( function (){
                var element = "Verifying OTP ...<img src='//s.svgbox.net/loaders.svg?fill=white&ic=tail-spin' style='width:30px'>";
                $('#verifyOtp').empty().append(element)
                setTimeout(function() {
                   alert('Account Verified and Registered Successfully, you can now shop')
                }, 4000)

                setTimeout(function (){
                    window.location.href = "http://localhost:8000/shopping";
                }, 5000)

            });
        });

    </script>
@endsection
