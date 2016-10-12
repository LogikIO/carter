@extends('carter::stand_alone')

@section('head')
    <link rel="stylesheet" media="screen"
          href="//cdn.shopify.com/s/assets/dialog-047a27dad0275f7f8b1dec198cce61c721ae435d9fc526f742cd9af5eedbc89f.css"
          crossorigin="anonymous"
          integrity="sha256-BHon2tAnX3+LHewZjM5hxyGuQ12fxSb3Qs2a9e7byJ8=" />
@stop

@section('content')
    <div id="container">
        <div class="login-form">
            <h1 class="dialog-heading">Sign up form</h1>
            <h2 class="dialog-subheading">Enter your shop domain to install app.</h2>

            <form action="{{ route('shopify.install') }}" method="post">

                {{ csrf_field() }}

                <div class="clearfix">
                    <div class="login-container">

                        @if (count($errors) > 0)
                            <div id="system_error" class="status system-error dialog-status">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div id="sign-in-form" class="lform dialog-form">
                            <div id="login">
                                <div class="dialog-input-container clearfix">
                                    <input type="text" name="shop" class="dialog-input" placeholder="your-shop-domain.myshopify.com" style="padding-left: 24px !important;">
                                </div>
                                <input type="submit" value="Sign Up" class="dialog-btn">
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

@stop