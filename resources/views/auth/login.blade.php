<!DOCTYPE html>
<html class=" ">
    <head>
        <!-- 
         * @Package: Ultra Admin - Responsive Theme
         * @Subpackage: Bootstrap
         * @Version: 4.1
         * This file is part of Ultra Admin Theme.
        -->
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>BF Services : Login </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
            <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="" name="description" />
        <meta content="" name="author" />

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon" />    <!-- Favicon -->
        <link rel="apple-touch-icon-precomposed" href="{{ asset('images/apple-touch-icon-57-precomposed.png') }}">	<!-- For iPhone -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('images/apple-touch-icon-114-precomposed.png') }}">    <!-- For iPhone 4 Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('images/apple-touch-icon-72-precomposed.png') }}">    <!-- For iPad -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('images/apple-touch-icon-144-precomposed.png') }}">    <!-- For iPad Retina display -->




        <!-- CORE CSS FRAMEWORK - START -->
        <link href="{{ asset('plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" media="screen"/>
        <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('plugins/bootstrap/css/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('fonts/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS FRAMEWORK - END -->

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <link href="{{ asset('plugins/icheck/skins/square/orange.css') }}" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE CSS TEMPLATE - START -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS TEMPLATE - END -->

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    <body class=" login_page">


        <div class="login-wrapper">
            <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Login Page" tabindex="-1">BF Services</a></h1>

                @if (count($errors) > 0)
                <div class="alert alert-danger top15">
                        <p><strong>Credenciales incorrectas</strong>, intente nuevamente.</p>
                </div>
                @endif
                <form name="loginform" id="loginform" role="form" method="post" action="{{ url('/login') }}" > {{ csrf_field() }}
                    <p>
                        <label for="user_login">Correo<br />
                            <input type="text" name="email" id="email" class="input" placeholder="nombre@email.com" size="20" />
                        </label>
                    </p>
                    <p>
                        <label for="user_pass">Password<br />
                            <input type="password" name="password" id="password" class="input" placeholder="Password" size="20" />
                        </label>
                    </p>
                    <p class="forgetmenot">
                        <label class="icheck-label form-label" for="remember"><input name="remember" type="checkbox" id="remember" value="forever" class="skin-square-orange" checked> Recordar</label>
                    </p>



                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Entrar" />
                    </p>
                </form>

<!--                 <p id="nav">
                    <a class="pull-left" href="#" title="Password Lost and Found">Forgot password?</a>
                </p> -->


            </div>
        </div>





        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->


        <!-- CORE JS FRAMEWORK - START --> 
        <script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('js/jquery.easing.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('plugins/pace/pace.min.js') }}" type="text/javascript"></script>  
        <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('plugins/viewport/viewportchecker.js') }}" type="text/javascript"></script>  
        <!-- CORE JS FRAMEWORK - END --> 


        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <script src="{{ asset('plugins/icheck/icheck.min.js') }}" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE TEMPLATE JS - START --> 
        <script src="{{ asset('js/scripts.js') }}" type="text/javascript"></script> 
        <!-- END CORE TEMPLATE JS - END --> 
            <!-- Scripts -->
         <script src="/js/app.js"></script>
    </body>
</html>



