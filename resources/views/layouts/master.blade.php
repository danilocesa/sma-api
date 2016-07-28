<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="author" content="Fountainhead">
    <meta name="description" content="Social Media Analytics - Dictionary">
    <meta name="keywords" content="SMA,Halo Halo, Dictionary,Social,Sentiment">
    <title>Halo Halo - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('scripts') 
  </head>
  <body>
    <div id="main-content">
      <div id="logo"><img src="{{ asset('images/tropicalv2-min.png') }}" /></div>
      @yield('content')
      <div id="black-screen"></div> 

      <canvas id="main-canvas" class="main-with-bg" width="550" height="650"></canvas>
    </div>
    
    <!-- <audio  autoplay="" controls="" loop="" preload="" style="display:none;">
      <source src="{{ asset('audio/bg_sound.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
    </audio> -->
    
  </body>
</html>