<?php

namespace Woolf\Carter\Http\Controllers;

use Auth;
use Config;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Shopify;
use Woolf\Carter\Http\Middleware\RedirectIfLoggedIn;
use Woolf\Carter\Http\Middleware\RedirectToLogin;
use Woolf\Carter\Http\Middleware\RequestHasShopDomain;
use Woolf\Carter\Http\Middleware\VerifyChargeAccepted;
use Woolf\Carter\Http\Middleware\VerifySignature;
use Woolf\Carter\Http\Middleware\VerifyState;

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

    public function install(Request $request)
    {
        $rules = ['shop' => 'required|unique:users,domain|max:255'];

        $messages = ['shop.unique' => 'Store has already been registered'];

        $this->validate($request, $rules, $messages);

        return Shopify::oauth()->authorize(route('shopify.register'));
    }

    public function registerStore()
    {
        return view('shopify.auth.register');
    }

    public function register()
    {
        $shop = Shopify::shop()->get();

        auth()->login(app('carter.auth.model')->create([
            'name'         => $shop['name'],
            'email'        => $shop['email'],
            'password'     => bcrypt(Str::random(10)),
            'domain'       => $shop['domain'],
            'shopify_id'   => $shop['id'],
            'access_token' => $shop['access_token']
        ]));

        $charge = Shopify::recurringCharges()->create(config('carter.shopify.plan'));

        return Shopify::recurringCharges()->confirm($charge);
    }

    public function activate(Request $request)
    {
        $charge = Shopify::recurringCharges($request->get('charge_id'));

        if ($charge->isAccepted()) {
            Shopify::recurringCharges($charge->getId())->activate();

            auth()->user()->update(['charge_id' => $charge->getId()]);
        }

        return Shopify::shop()->apps();
    }

    public function login(Request $request)
    {
        $user = app('carter.auth.model')->whereDomain($request->get('shop'))->first();

        Auth::login($user);

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('shopify.app.dashboard', ['user' => auth()->user()]);
    }
}