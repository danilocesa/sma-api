<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="author" content="Fountainhead">
    <meta name="description" content="Social Media Analytics - Dictionary">
    <meta name="keywords" content="SMA,Halo Halo, Dictionary,Social,Sentiment">
    <title>Halo Halo - Yohoo</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" / >
    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
    @stack('scripts') 
  </head>
  <body>
    <div id="main-content">
      <div id="logo"><img src="{{ asset('images/tropicalv2-min.png') }}" /></div>
      @yield('content') 
    </div>
    
  </body>
</html>