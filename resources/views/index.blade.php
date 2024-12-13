<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Credito786</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="/images/credito786-icon.png">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">

    <!-- Jquery Toast css -->
    <link href="/libs/jquery-toast-plugin/jquery.toast.min.css" rel="stylesheet" type="text/css" />

    <!--Material Icon -->
    <link rel="stylesheet" type="text/css" href="/css/materialdesignicons.min.css" />

    <!--pe-icon-7 Icon -->
    <link rel="stylesheet" type="text/css" href="/css/pe-icon-7-stroke.css" />

    <!-- Custom  css -->
    <link rel="stylesheet" type="text/css" href="/css/style.css" />

    <link href="/css/icons.min.css" rel="stylesheet" type="text/css" />

    <style>
        .animation_boton {
            animation: pulse-boton  1s infinite;
        }

        @keyframes pulse-boton {
            0% {
                left: 0px;
                position: relative;
            }

            100% {
                left: 10px;
                position: relative;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".sticky" data-bs-offset="70">
    <header class="sticky" id="navbar-sticky">
        <div class="tagline d-none d-lg-block bg-dark" style="margin: -25px 0 0 0;">
            <div class="container">
                <div class="float-start info-link">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="tel:866-716-4225">
                                <i class="mdi mdi-phone-classic me-1"></i> <span class="font-size-13">+1 866-716-4225</span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="mailto:info@credito786.com">
                                <i class="mdi mdi-email me-1"></i> <span class="font-size-13">info@credito786.com</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="float-end">
                    <ul class="list-inline social-links mb-0">
                        <li class="list-inline-item">
                            <a href="https://www.facebook.com/credito786/">
                                <i class="mdi mdi-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.linkedin.com/company/credito786/">
                                <i class="mdi mdi-linkedin"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.instagram.com/credito786/">
                                <i class="mdi mdi-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <!--Navbar Start-->
        <nav class="navbar navbar-expand-lg fixed-top navbar-custom navbar-light" id="navbar" style="box-shadow: 0px 1px 14px #0003;">
            <div class="container">
                <!-- LOGO -->
               <div class="row" style="margin: 12px auto 12px auto;">
                    <div class="col-md-12">
                        <a class="logo text-uppercase" href="index.html">
                            <img src="/images/credito786-logo.png" alt="" class="logo-light" height="35" />
                            <img src="/images/credito786-logo.png" alt="" class="logo-dark" height="35" />
                        </a>
                    </div>
                </div>
        </nav>
        <!-- Navbar End -->
    </header>
<section class="section mt-3" id="form-seccion" style="padding-bottom: 172px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-3">
                <img src="/images/logo.svg" alt="" class="img-fluid" style="max-width: 750px;">
                <p class="text-center">Credito786 in partnership with SmartCredit®
                    sponsor your monthly subscription.</p>
            </div>
        </div>
        @include('uppy')
    <!-- end container -->
    </div>
</section>
<!-- footer start -->
<footer class="bg-dark footer text-white" style="padding: 0 0 25px 0 !important;">
    <div class="container">
        <!-- end row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="pt-4">
                    <div class="text-center">
                        <p class="text-white-50 mb-0">
                            Credito786 © <script>document.write(new Date().getFullYear())</script> • La vida será más fácil • Powered by <a class="footer-link" href="https://qualgrow.com/" target="blank" style="pointer-events: none; cursor: default;">Qualgrow Corp</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- container end -->
</footer>
<!-- footer end -->
<div id="login-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="index.html" class="text-success">
                        <span><img src="/images/credito786-logo.png" alt="" style="width: 222px;"></span>
                    </a>
                </div>
                <div class="row" id="form_token">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                @if ($message = Session::get('error'))
                                    <div class="row" style="margin: 0 0 0 9px;">
                                        <div class="col-md-12">
                                            <p><strong style="color: #c43333;"> {{ $message }} </strong></p>
                                        </div>
                                    </div>
                                @endif
                                <form action="{{ route('create-token') }}" id="form_sign" name="form_sign" method="get" class="px-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="emailaddress1" class="form-label">Email address</label>
                                        <input class="form-control" type="email" name="email_token" placeholder="Eg: john@deo.com">
                                        <span class="invalid-feedback" id="email_token" role="alert">
                                        </span>
                                    </div>
                                    <div class="mb-3 text-center">
                                        <button class="btn btn-rounded btn-primary" id="sign_in" style="width: 100%" type="button">Sign In</button>
                                    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
                <div class="row d-none" id="success_token">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <h2 class="mt-0" style="color: #1abc9c;"><i class="mdi mdi-check-all"></i></h2>
                                            <h1 class="mt-0">Update Token successfully!!</h1>
                                            <p>An email has been sent to your mailbox with updated information to continue your registration in the form.<br> Thank you for choosing us</p>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- javascript -->
<!-- Uppy js-->
<script src="/js/vendor.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>

<!-- counter js -->
<script src="/js/counter.init.js"></script>

<!-- Plugin js-->
<script src="/libs/parsleyjs/parsley.min.js"></script>

<!-- Validation init js-->
<script src="/js/form-validation.init.js"></script>


<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="/libs/jquery-toast-plugin/jquery.toast.min.js"></script>

<!-- toastr init js-->
<script src="{{asset('/js/toastr.init.js')}}"></script>

<script src="/js/jquery.cookie.js"></script>

<!-- custom js -->
<script src="/js/app.js"></script>

<script>
    $( document ).ready(function() {
        let customer_email = "{{ $type ?? '' }}";
        $('#data_email_document').val(customer_email);

        $(".uppy-StatusBar-actionBtn").on("click", function() {
            $("#document-modal").modal('hide');
        });

    });
</script>
@if(session("error"))
    <script>
        $(function(){
            $.NotificationApp.send("Answer Incorrect!", 'The selected answers are incorrect ', 'top-right', '#bf441d', 'error');
        });
    </script>
@endif
</body>

</html>
