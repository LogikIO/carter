<?php

namespace Woolf\Carter\Http\Controllers;

use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Woolf\Carter\Http\Middleware\RedirectIfLoggedIn;
use Woolf\Carter\Http\Middleware\RedirectToLogin;
use Woolf\Carter\Http\Middleware\RequestHasShopDomain;
use Woolf\Carter\Http\Middleware\VerifyChargeAccepted;
use Woolf\Carter\Http\Middleware\VerifySignature;
use Woolf\Carter\Http\Middleware\VerifyState;
use Woolf\Carter\RegisterStore;
use Woolf\Carter\ShopifyProvider;

class ShopifyController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(RequestHasShopDomain::class, [
            'only' => ['install']
        ]);

        $this->middleware(VerifyState::class, [
            'only' => ['register']
        ]);

        $this->middleware(VerifySignature::class, [
            'only' => ['register', 'login']
        ]);

        $this->middleware(RedirectIfLoggedIn::class, [
            'only' => ['install', 'registerStore', 'register', 'login']
        ]);

        $this->middleware(RedirectToLogin::class, [
            'only' => ['dashboard']
        ]);

        $this->middleware(VerifyChargeAccepted::class, [
            'only' => ['dashboard']
        ]);
    }

    public function install(Request $request, ShopifyProvider $shopify)
    {
        $this->validate(
            $request,
            ['shop' => 'required|unique:users,domain|max:255'],
            ['shop.unique' => 'Store has already been registered']
        );

        return $shopify->authorize(route('shopify.register'));
    }

    public function registerStore()
    {
        return view('shopify.auth.register');
    }

    public function register(RegisterStore $store)
    {
        return $store->register()->charge();
    }

    public function activate(RegisterStore $store, ShopifyProvider $shopify, Request $request) {
        $charge = $request->get('charge_id');

        if ($store->hasAcceptedCharge($charge)) {
            $store->activate($charge);
        }

        return $shopify->apps();
    }

    public function login(Request $request)
    {
        Auth::login(
            app('carter.auth.model')->whereDomain($request->get('shop'))->first()
        );

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('shopify.app.dashboard', ['user' => Auth::user()]);
    }
}