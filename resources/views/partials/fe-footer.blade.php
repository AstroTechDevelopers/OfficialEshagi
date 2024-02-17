<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 15/2/2021
 * Time: 14:44
 */
?>
<footer class="footer ">
    <div class="container-page ">
        <div class="footer-warpper ">
            <div class="footer-top">
                <div class="container ">
                    <div class="footer-top  clearfix">
                        <div class="footer-bottom-content clearfix">
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <div class="content-footer">
                                        <div class="logo-footer ">
                                            <img src="{{asset('images/logo_official.png')}}" alt="" width="200px" height="200px">
                                        </div>
                                        <div class="text-footer ">
                                            <p>
                                                eShagi Financial Services (Pvt) Ltd is registered in Zambia, Company No: 9002/2019 </p> <hr>
                                            <p>Copyright &copy; @php echo date('Y'); @endphp</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="content-footer">
                                        <h5>Menu</h5>
                                        <ul class="list-menu ">
                                            <li><a href="{{url('/#about')}}">About </a></li>
                                            <li><a href="{{url('/#features')}}">Features</a></li>
                                            <li><a href="{{url('/#video-features')}}">Video</a></li>
                                            <li><a href="{{url('/#testimonials')}}">Testmonials</a></li>
                                            <li><a href="{{url('/#contact')}}">Contact</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="content-footer">
                                        <h5>Useful Links</h5>
                                        <ul class="list-menu ">
                                            <li><a href=" ">Terms of use </a></li>
                                            <li><a href=" ">Privacy Policy</a></li>
                                            <li><a href=" ">Help</a></li>
                                            <li><a href=" ">Blog</a></li>
                                            <li><a href=" ">Careers</a></li>
                                            <li><a href=" ">Faq</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="newsletter-block ">
                                        <img src="{{asset('images/icons/email-1.svg')}}" alt="">

                                        <h5>Subscribe to Newsletter</h5>
                                        <div class="subscribe-form ">
                                            <form action="#" method="post" class="subscribe-mail">
                                                <div class="form-group">
                                                    <input type="email" class="form-control email-input" placeholder="Enter your email">
                                                    <input type="submit" class="btn btn-green" value="Subscribe">
                                                </div>
                                                <p class="error-message"></p>
                                                <p class="sucess-message"></p>
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
</footer>
