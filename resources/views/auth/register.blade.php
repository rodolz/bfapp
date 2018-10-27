
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
        <title>BF SERVICES : Registro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon" />    <!-- Favicon -->
        <link rel="apple-touch-icon-precomposed" href="{{ asset('images/apple-touch-icon-57-precomposed.png') }}">   <!-- For iPhone -->
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
        <link href="{{ asset('plugins/icheck/skins/square/orange.css') }}" rel="stylesheet" type="text/css" media="screen"/>        
        <link href="{{ asset('plugins/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css" media="screen"/>   
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE CSS TEMPLATE - START -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS TEMPLATE - END -->

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    <body class="login_page">


        <div class="register-wrapper">
            <div id="register" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Registro" tabindex="-1">BF SERVICES</a></h1>

                @if (count($errors) > 0)
                <div>
                        <p><strong>Whoops! </strong><span class="badge badge-md badge-danger">{{ count($errors) }}</span> errores en el formulario</p>
                </div>
                @endif
                <form name="loginform" id="loginform" action="{{ url('/register') }}" role="form" method="POST">{{ csrf_field() }}


                    <p>
                        <label for="user_login">Nombre Completo<br />
                            <input type="text" name="nombre" id="nombre" class="input" value="{{ old('nombre') }}" size="20" /></label>
                            @if ($errors->first('nombre'))
                                <span class="label label-danger">
                                    {{ $errors->first('nombre') }}
                                </span>
                            @endif
                    </p>
                    <div id="datos_usuarios">
                    <p>
                        <label for="user_login">Email - <i>necesario para acceder al sistema</i><br />
                            <input type="email" name="email" id="email" class="input" value="{{ old('email') }}" size="20" /></label>
                            @if ($errors->first('email'))
                                <span class="label label-danger">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                    </p>
                    <p>
                        <label for="user_pass">Password - <i>necesario para acceder al sistema</i><br />
                            <input type="password" name="password" id="password" class="input" value="" size="20" /></label>
                            @if ($errors->first('password'))
                                <span class="label label-danger">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                    </p>
                    <p>
                        <label for="user_pass">Confirmar Password<br />
                            <input type="password" name="password_confirmation" id="confirm" class="input" value="" size="20" /></label>
                            @if ($errors->first('password_confirmation'))
                                <span class="label label-danger">
                                    {{ $errors->first('password_confirmation') }}
                                </span>
                            @endif
                    </p>
                    </div>

                    <div class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-success btn-block" value="Registrar" />
                        <a type="button" class="btn btn-default btn-block" href="{{ URL::route('index') }}">Cancelar</a>
                    </div>
                </form>

<!--                 <p id="nav">
                    <a class="pull-left" href="#" title="Password Lost and Found">Forgot password?</a>
                    <a class="pull-right" href="ui-login.html" title="Sign Up">Sign In</a>
                </p> -->
                <div class="clearfix"></div>
                <!-- <div class="col-md-12 text-center register-social">

                    <a href="#" class="btn btn-primary btn-lg facebook"><i class="fa fa-facebook icon-sm"></i></a>
                    <a href="#" class="btn btn-primary btn-lg twitter"><i class="fa fa-twitter icon-sm"></i></a>
                    <a href="#" class="btn btn-primary btn-lg google-plus"><i class="fa fa-google-plus icon-sm"></i></a>
                    <a href="#" class="btn btn-primary btn-lg dribbble"><i class="fa fa-dribbble icon-sm"></i></a>

                </div> -->

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
        <script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}" type="text/javascript"></script>
        <!-- END CORE TEMPLATE JS - END --> 

        <!-- <script type="text/javascript">
           $('#idRol').change(function(){
                if($(this).val() === '3'){
                    $('#datos_usuarios').hide();
                }
                else{
                    $('#datos_usuarios').show();
                }

           }); 
        </script> -->
    </body>
</html>



