<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/jQuery-2.1.4.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- All the files that are required -->
    <!-- Font Awesome Icons -->
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <style>
    body{
        font-family: "Open Sans";
    }
    </style>
</head>
<body>
    <!-- Where all the magic happens -->
    <!-- LOGIN FORM -->
    <div class="text-center" style="padding:50px 0">
        <div class="logo"><img src="{{ asset('/img/hp.png') }}" class="center-block" style="width:100px"></div>
        @yield('content')
    </div>
    <!--<script src="{{ asset('/js/fonctions.js') }}"></script>-->
</body>
</html>