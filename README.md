# Carter
Shopify app jumpstart for Laravel 5.2

## Installation

```
$ composer require woolf/carter
```

### Autoload Carter Service Provider - config/app.php

```php
<?php

...

    'providers' => [
    
        ...
        Woolf\Carter\CarterServiceProvider::class,
    
    ];
    
```

### Register Shopify facade. - config/app.php

```php
<?php

...

    'aliases' => [

            ...
            'Shopify' => Woolf\Carter\ShopifyFacade::class,

        ],

```

### Create and run database migration.

```
$ php artisan carter:table && php artisan migrate
```

### Add ShopOwner trait to User model - app/User.php

```php
<?php

...

class User extends Authenticatable
{

    use \Woolf\Carter\ShopOwner;
    
    ...
    
}

```

### Update `$fillable` and `$hidden` fields in User model - app/User.php

```php
<?php

...

class User extends Authenticatable
{
    
    ...
    
    protected $fillable = [
        'name', 'email', 'password', 'domain', 'shopify_id', 'access_token', 'charge_id'
    ];

    protected $hidden = [
        'password', 'remember_token', 'access_token'
    ];
    
}

```

### Add your Shopify API key and secret to `.env`

https://docs.shopify.com/api/authentication/oauth#get-the-client-redentials

```
SHOPIFY_KEY=1234567890abcdefghijklmnopqrstuv
SHOPIFY_SECRET=1234567890abcdefghijklmnopqrstuv
```

### Publish carter configuration and views.

- config/carter.php
- resources/views/vendor/carter

```
$ php artisan vendor:publish
```

Carter comes configured with routes and views to quickly get users registered with your app.

Method | URI | Name | Description
--- | --- | --- | ---
GET | /activate | shopify.activate | Activate recurring application charge after user has accepted. User is redirected here as the step in the registration process.
GET | /dashboard | shopify.dashboard | The entry point view for your app.
GET/POST | /install | shopify.install | Using "shop" value from request, redirects to Shopify for authorization to access shop's data.
GET | /login | shopify.login | Will find and log in user. Requires valid Shopify request signature. 
GET | /register | shopify.register | Create user for shop and setup recurring application charge. Requires valid Shopify request signature.
GET | /signup | shopify.signup | Display sign up form view. You'll need to create this file `resources/views/shopify/auth/register.blade.php`. Submit POST request to `route(shopify.install)` with "shop" value.


