<?php

namespace Woolf\Carter\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Woolf\Carter\RegistersStore;
use Woolf\Carter\ShopifyProvider;

class ShopifyController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(
            'request.has-shop',
            ['only' => ['install']]
        );

        $this->middleware(
            'request.valid-state',
            ['only' => ['register']]
        );

        $this->middleware(
            'request.valid-signature',
            ['only' => ['register', 'login']]
        );

        $this->middleware(
            'redirect.if-logged-in',
            ['only' => ['install', 'registerStore', 'register', 'login']]
        );

        $this->middleware(
            'redirect.to-login',
            ['only' => ['dashboard']]
        );

        $this->middleware(
            'verify.charge-accepted',
            ['only' => ['dashboard']]
        );
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

    public function register(RegistersStore $store)
    {
        return $store->register()->charge();
    }

    public function activate(RegistersStore $store, ShopifyProvider $shopify, Request $request)
    {
        $charge = $request->get('charge_id');

        if ($store->hasAcceptedCharge($charge)) {
            $store->activate($charge);
        }

        return $shopify->apps();
    }

    public function login(Request $request, Guard $auth)
    {
        $user = app(app('config')->get('auth.model'))->whereDomain($request->get('shop'))->first();

        $auth->login($user->fresh());

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('shopify.app.dashboard', ['user' => app('auth')->user()]);
    }
}