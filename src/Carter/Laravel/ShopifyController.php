<?php

namespace NickyWoolf\Carter\Laravel;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use NickyWoolf\Carter\Shopify\Api\RecurringApplicationCharge;
use NickyWoolf\Carter\Shopify\Client;

class ShopifyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function signupForm()
    {
        return view('carter::auth.register');
    }

    public function install(Request $request)
    {
        $this->validate(
            $request,
            ['shop' => 'required|unique:users,domain|max:255'],
            ['shop.unique' => 'Store has already been registered']
        );

        session(['state' => Str::random(40)]);

        return redirect(shopify_auth_url(route('shopify.register')));
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

        return redirect(app(Client::class)->endpoint('apps'));
    }

    public function loginRedirect()
    {
        return view('carter::redirect_escape_iframe', [
            'redirect' => shopify_auth_url(route('shopify.login'))
        ]);
    }

    public function login(Request $request)
    {
        $user = app('carter.user')->shopOwner($request->shop);

        auth()->login($user);

        return redirect()->route('shopify.dashboard');
    }

    public function dashboard()
    {
        return view('carter::app.dashboard', ['user' => auth()->user()]);
    }

    public function uninstall(Request $request)
    {
        app('carter.user')->shopOwner($request->shop)->uninstall();
    }
}