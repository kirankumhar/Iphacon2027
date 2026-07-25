<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!--favicon icon-->
    <link rel="icon" href="public/frontend/assets/img/ced.png" type="image/png" sizes="16x16" />

    <!--title-->
    <title><?php echo $title?></title>

    <!--build:css-->
    <link id="theme-style" href="public/frontend/assets/css/main.css" as="style" rel="stylesheet" />
    <link href="public/frontend/assets/js/sweetalert2/sweetalert2.min.css" rel="stylesheet" />

    <!-- endbuild -->

    <style>
        .company-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            color: #1a1a1a;
            letter-spacing: -0.5px;
            text-align: center;
        }

        .accent {
            color: #D80A6F; /* Your brand color */
        }
    </style>

</head>

<body class=" ">
    <!--preloader start-->
    <div id="preloader">
        <div class="preloader-wrap">
            <h2>Compyter Ed.</h2>
            <div class="preloader">
                <i>.</i>
                <i>.</i>
                <i>.</i>
            </div>
        </div>
    </div>
    <!--preloader end-->
    <section class="page-header-section ptb-100 bg-image full-height" >

        <div class="container">
            <div class="row align-items-center justify-content-center">
                
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="login-signup-wrap ">
                        <div class="login-signup-header text-center">
                            <a href="<?php echo base_url();?>"><h2 class="my_color_1">
                                <img src="public/backend/assets/images/logo.png" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px; width: 50%;">
                            </a>
                            <!--<h5 class="mb-5">Login Your Account</h5>-->
                        </div>
                        <form class="login-signup-form">
                            <div class="form-group mb-4 my-display-none">
                                <input type="hidden" class="form-control" name="<?= csrf_token() ?>" id="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>

                            <div class="form-group mb-4">
                                <!-- Label -->
                                <label class="pb-2 text-dark"> User Id </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                    <input type="email" class="form-control" id="username" name="username" placeholder="name@address.com" />
                                    <span class="text-danger" id="err_email"></span>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-4">
                                <!-- Label -->
                                <label class="pb-2 text-dark"> Password </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-6">
                                        <label class="text-dark "><strong>Captcha:-  &nbsp;</strong></label>
                                        <label class="captcha-label text-dark" id="no_one"><?php echo $no_one;?></label>
                                        <label class="captcha-label text-dark">&nbsp;+&nbsp;</label>
                                        <label class="captcha-label text-dark" id="no_two"><?php echo $no_two;?></label>
                                        <label class="captcha-label text-dark">&nbsp;=&nbsp;</label>

                                        <input type="text" class="form-control" id="captcha_ans"  name="captcha_ans" placeholder="Answer">

                                        <span class="text-danger" id="err_captcha"></span>
                                    </div>

                                    <div class="col-sm-6 text-center">
                                        <label class="mb-2 my-boder-bottom text-dark">
                                            <input type="checkbox" id="remember" name="remember"> Remember Me
                                        </label>
                                        <br>
                                        <a href="<?php echo base_url('forgotpassword');?>" class="my-boder-bottom" style="color: #D80A6F;">Forgot Password?</a>
                                    </div>

                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary mt-4 mb-3 btn-border" id="login_btn" style="background: #D80A6F;">
                                    Sign In
                                </button>
                            </div>
                        </form>
                        <!--<div class="other-login-signup my-3">
                            <div class="or-login-signup text-center">
                                <strong class="white-color" >Or Login With</strong>
                            </div>
                        </div>
                        <ul class="list-inline social-login-signup text-center">
                            <li class="list-inline-item my-1">
                                <a href="javascript:void(0);" class="btn btn-facebook"><i class="fab fa-facebook-f pr-1"></i> Facebook</a>
                            </li>
                            <li class="list-inline-item my-1">
                                <a href="javascript:void(0);" class="btn btn-google"><i class="fab fa-google pr-1"></i> Google</a>
                            </li>
                            <li class="list-inline-item my-1">
                                <a href="javascript:void(0);" class="btn btn-twitter"><i class="fab fa-twitter pr-1"></i> Twitter</a>
                            </li>
                        </ul>-->
                        <p class="text-center mt-2 ">
                            Don't have an account? <a href="javascript:void(0);" class=" ">Register</a>
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 position-relative text-center">
                    <div class="company-name">
                        ISMM <span class="accent">Conference</span>
                    </div>

                    <img src="<?php echo base_url('public/frontend/assets/img/login.png');?>"
                         style="width: 60%; height: auto;" class="img-fluid mx-auto d-block">
                </div>


            </div>


            <!--<div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="copyright-wrap small-text text-center mt-5 text-white">
                        <p class="mb-0">
                            &copy; ThemeTags Design Agency, All rights reserved
                        </p>
                    </div>
                </div>
            </div>-->
        </div>
    </section>

    <!--scroll bottom to top button start-->
    <div class="scroll-top scroll-to-target primary-bg text-white" data-target="html">
        <span class="fas fa-hand-point-up"></span>
    </div>
    <!--scroll bottom to top button end-->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0">
                            <span style="color:#000000; margin-left: 65px;"> <b>Powered by: <img src="public/frontend/assets/img/ced.png" style=" height:35px;"> <a target="_blank" style="color:#ff0bfc;  " href="http://www.computered.in">COMPUTER Ed. </a></b></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--build:js-->
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/jquery-3.6.0.min.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/bootstrap.bundle.min.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/bootstrap-slider.min.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/owl.carousel.min.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/magnific-popup.min.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/validator.min.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/vendors/hs.megamenu.js"></script>
    <script {csp-script-nonce} src="public/frontend/assets/js/app.js"></script>
    <!--endbuild-->

    <script {csp-script-nonce} src="public/frontend/assets/js/sweetalert2/sweetalert2.min.js"></script>
    <script {csp-script-nonce} src="public/module_js/validation.js"></script>
    <script {csp-script-nonce} src="public/module_js/common.js"></script>
    <script {csp-script-nonce} src="public/module_js/authentication/login.js"></script>


</body>

</html>