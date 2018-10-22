<!DOCTYPE html>
<html class=" ">
    @include('layout.partials.head')
    <!-- BEGIN BODY -->
    <body class=" ">
        <!-- START CONTAINER -->
        <div class="page-container row-fluid">
            @include('layout.partials.topbar')
            @include('layout.partials.sidebar')
            @include('layout.partials.main-content')
        </div>
        <!-- END CONTAINER -->
        @include('layout.partials.plugins')
    </body>
</html>
