<?php

namespace NickyWoolf\Carter\Laravel;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class ShopifyController extends Controller
{
    use AuthorizeRequests, DispatchesJobs, ValidatesRequests;

    protected $installRules = [
        'shop' => 'required|unique:users,domain|max:255'
    ];

    protected $installMessages = [
        'shop.unique' => 'Store has already been registered'
    ];

    public function install()
    {
        //
    }

    public function signupForm()
    {
        //
    }

    public function register()
    {
        //
    }

    public function activate()
    {
        //
    }

    public function login()
    {
        //
    }

    public function dashboard()
    {
        //
    }
}