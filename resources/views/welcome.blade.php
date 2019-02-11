<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Authentication</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- csrf attack protection -->
        <meta name="csrf-token" content="{{ csrf_token() }}"> 
    </head>
    <body>  
        <div id="app"><!-- our app geteway --></div> 
    </body>
    <script src=" {{ asset('js/app.js') }} "></script>
    
</html>
