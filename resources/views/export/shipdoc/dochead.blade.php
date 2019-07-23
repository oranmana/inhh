<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shipping Document</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/doc.css') }}" rel="stylesheet">
    <style>
        .tiny { font-size: 12px;}
    </style>
</head>
<body>
  <div class="container" id="dochead">
    @php( $nego = in_array($doctype, array(211,221)) )
    @if ( $nego )
      <div>{!! $inv->credit->beneficiary !!}</div>
    @else    
      <div><b>HANWHA CHEMICAL (THAILAND) CO.,LTD.</b></div>
      <div class="border-bottom">377 Moo 17, Bangsaothong, Bangsaothong, Samutprakarn 10570 THAILAND<br />Tel +66-2-315-3204, E-mail:asrm@hanwhath.com</div>
    @endif
</div>
<div  class="container" id="docboby">
  @yield('content')
</div>
<div  class="container" id="docfoot">
  @include('export.shipdoc.docfoot')
</div>
@yield('script')
</body>
</html>
