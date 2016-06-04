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
use Woolf\Carter\Http\Middleware\RedirectIfAuthenticated;
use Woolf\Carter\Http\Middleware\Authenticate;
use Woolf\Carter\Http\Middleware\RequestHasShopDomain;
use Woolf\Carter\Http\Middleware\VerifyChargeAccepted;
use Woolf\Carter\Http\Middleware\VerifySignature;
use Woolf\Carter\Http\Middleware\VerifyState;
use Woolf\Carter\RegisterShop;
use Woolf\Shophpify\Endpoint;
use Woolf\Shophpify\Resource\OAuth;
use Woolf\Shophpify\Resource\RecurringApplicationCharge;

class ShopifyController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $installRules = ['shop' => 'required|unique:users,domain|max:255'];

    protected $installMessages = ['shop.unique' => 'Store has already been registered'];

    public function __construct()
    {
        $this->middleware([Authenticate::class, VerifyChargeAccepted::class], ['only' => ['dashboard']]);
    }

    public function install(Request $request)
    {
        $this->validate($request, $this->installRules, $this->installMessages);

        session(['state' => Str::random(40)]);

        return redirect(carter_auth_url(route('shopify.register')));
    }

    public function signupForm()
    {
        return view('carter::shopify.auth.register');
    }

    public function register(RegisterShop $registerShop)
    {
        auth()->login($registerShop->execute());

        $charge = app(RecurringApplicationCharge::class)->create(config('carter.shopify.plan'));

        return redirect($charge['confirmation_url']);
    }

    public function activate(Request $request, RecurringApplicationCharge $charge, Endpoint $endpoint)
    {
        $id = $request->get('charge_id');

        if ($charge->isAccepted($id)) {
            $charge->activate($id);
            auth()->user()->update(['charge_id' => $id]);
        }

        return redirect($endpoint->build('admin/apps'));
    }

    public function login(Request $request)
    {
        $user = app('carter_user')->whereDomain($request->get('shop'))->first();

        auth()->login($user);

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('carter::shopify.app.dashboard', ['user' => auth()->user()]);
    }
}
