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
use Woolf\Carter\Shopify\Resource\Product;
use Woolf\Carter\Shopify\Resource\RecurringApplicationCharge;
use Woolf\Shophpify\Client;

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

        return redirect(Shopify::authorizationUrl(route('shopify.register')));
    }

    public function registerStore()
    {
        return view('shopify.auth.register');
    }

    public function register(Request $request)
    {
        $shopify = app(\Woolf\Carter\Shopify\Shopify::class, [
            'client' => new Client(Shopify::requestAccessToken($request->code))]
        );

        auth()->login(app('carter_user')->create($shopify->mapToUser()));

        $charge = app(RecurringApplicationCharge::class)->create(config('carter.shopify.plan'));

        return redirect($charge['confirmation_url']);
    }

    public function activate(Request $request, RecurringApplicationCharge $charge)
    {
        $id = $request->get('charge_id');

        if ($charge->isAccepted($id)) {
            $charge->activate($id);
            auth()->user()->update(['charge_id' => $id]);
        }

        return redirect(Shopify::appsUrl());
    }

    public function login(Request $request)
    {
        $user = app('carter_user')->whereDomain($request->get('shop'))->first();

        auth()->login($user);

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('shopify.app.dashboard', ['user' => auth()->user()]);
    }
}
