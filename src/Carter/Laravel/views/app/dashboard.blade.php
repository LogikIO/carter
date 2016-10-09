@extends('carter::embedded')

@section('content')
    <link href='https://fonts.googleapis.com/css?family=Roboto:100' rel='stylesheet' type='text/css'>
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
            font-family: 'Roboto';
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
            color: #f2f5f7;
            text-shadow: 2px 2px 4px #2f92cc;
        }
    </style>
    <div class="container">
        <div class="content">
            <div class="title">Carter</div>
        </div>
    </div>
@stop