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

    protected $installRules = ['shop' => 'required|unique:users,domain|max:255'];

    protected $installMessages = ['shop.unique' => 'Store has already been registered'];

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
        $this->validate($request, $this->installRules, $this->installMessages);

        session(['state' => Str::random(40)]);

        $url = Shopify::resource('oauth')->authorizeUrl(route('shopify.register'), session('state'));

        return redirect($url);
    }

    public function registerStore()
    {
        return view('shopify.auth.register');
    }

    public function register()
    {
        $user = app('carter.auth.model');

        auth()->login($user->create(Shopify::resource('shop')->mapToUser()));

        $charge = Shopify::resource('recurring_charges')->create(config('carter.shopify.plan'));

        return redirect($charge['confirmation_url']);
    }

    public function activate(Request $request)
    {
        $charge = Shopify::resource('recurring_charges')->setId($request->get('charge_id'));

        if ($charge->isAccepted()) {
            auth()->user()->update([
                'charge_id' => $charge->activate()['id']
            ]);
        }

        return redirect(Shopify::resource('shop')->endpoint('admin/apps'));
    }

    public function login(Request $request)
    {
        $user = app('carter.auth.model')->whereDomain($request->get('shop'))->first();

        auth()->login($user);

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        $products = Shopify::resource('product')->retrieve();

        return view('carter::shopify.app.dashboard', ['user' => auth()->user(), 'products' => $products]);
    }
}