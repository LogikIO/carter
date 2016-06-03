@extends('carter::shopify.embedded')

@section('content')
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,500" rel="stylesheet" type="text/css">
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Raleway';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
            font-weight: 100;
            text-transform: uppercase;
            letter-spacing: .25em;
            margin-right: -.25em;
            color: #d8f0e9;
            text-shadow: 2px 2px 4px #29bc94;
        }
    </style>
    <div class="container">
        <div class="content">
            <div class="title">Carter</div>
        </div>
    </div>
@stop