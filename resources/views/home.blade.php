@extends('layouts.master')
@section('title', 'Home')

@section('content')

@endsection


@push('scripts')

<script src="{{ asset('js/createjs-2015.11.26.min.js') }}"></script>
<script src="{{ asset('js/pixi.min.js') }}"></script>
<script>PIXI.utils._saidHello = true;</script>
<script>
 window.onload = function() {
    // init(); //Initialization of CreateJS
    document.getElementById('black-screen').style.display = 'none';
    document.getElementById('main-canvas').style.backgroundImage = "url('{{ asset('images/main_bg.png') }}')"; 
    document.getElementById('logo').style.display = 'none';

 }


var canvasID = document.getElementById('main-canvas');
var renderer = PIXI.autoDetectRenderer(256, 256,canvasID);

//Create a container object called the `stage`
var stage = new PIXI.Container();
var publicPath = "{{ str_replace('\\','/',public_path()) }}"; 
var logo;
PIXI.loader.add({name:'logo',url:"{{ asset('/images/tropicalv2-min.png') }}",crossOrigin:true})
  .load(function(loader,resources){
    logo = new PIXI.Sprite(resources.logo.texture);
    stage.addChild(logo);
    renderer.render(stage);
  });



</script>

@endpush