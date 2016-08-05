<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>"/>

    <link rel="shortcut icon" type="image/png" href="{{asset('img/favicon.png')}}"/>

    <title>Matriz de Seguimiento</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('bower_components/bootstrap/dist/css/bootstrap.css')}}" rel="stylesheet">
    
    {{-- <link rel="stylesheet" type="text/css" href="https://bootswatch.com/lumen/bootstrap.min.css"> --}}
    <!-- MetisMenu CSS -->
    <link href="{{asset('bower_components/metisMenu/dist/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('dist/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">


    <link href="{{ asset('/css/style.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- jQuery -->
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>


    <!-- Chosen -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>

    <!-- Ckeditor -->
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/t/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1/datatables.min.js"></script>

    <script src="{{asset('js/qrcode.min.js')}}"></script>

    @include('layouts.partials.initialscript')
    
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput_locale_es.min.js"></script>

    <link href="{{asset('css/dependent-dropdown.min.css')}}" media="all" rel="stylesheet" type="text/css" />
    <script src="{{asset('js/dependent-dropdown.min.js')}}" type="text/javascript"></script>
</head>
