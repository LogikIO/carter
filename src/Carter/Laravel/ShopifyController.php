<?php

namespace NickyWoolf\Carter\Laravel;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use NickyWoolf\Carter\Shopify\Api\RecurringApplicationCharge;
use NickyWoolf\Carter\Shopify\Oauth;

class ShopifyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function install(Request $request, Oauth $oauth)
    {
        $this->validate(
            $request,
            ['shop' => 'required|unique:users,domain|max:255'],
            ['shop.unique' => 'Store has already been registered']
        );

        session(['state' => Str::random(40)]);

        $clientId = config('carter.shopify.client_id');
        $scope = implode(',', config('carter.shopify.scopes'));
        $redirect = route('shopify.register');
        $state = session('state');

        return redirect($oauth->authorizationUrl($clientId, $scope, $redirect, $state));
    }

    public function signupForm()
    {
        return view('carter::shopify.auth.register');
    }

    public function register(RegisterShopifyUser $shopifyUser)
    {
        auth()->login($shopifyUser->register());

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

        return redirect($charge->endpoint('admin/apps'));
    }

    public function login(Request $request)
    {
        $user = app('carter.user')->whereDomain($request->get('shop'))->first();

        auth()->login($user);

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('carter::shopify.app.dashboard', ['user' => auth()->user()]);
    }
}