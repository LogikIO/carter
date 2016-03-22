# Carter
Shopify app jumpstart for Laravel

## Installation

```
$ composer require woolf/carter
```

Autoload Carter Service Provider - config/app.php

```php
<?php

...

    'providers' => [
        ...
        Woolf\Carter\CarterServiceProvider::class,
    
    ];
    
```

Add StoreOwner trait to User model - app/User.php

```php
<?php

...

class User extends Authenticatable
{

    use \Woolf\Carter\StoreOwner;
    
    ...
    
}

```

Update `$fillable` and `$hidden` fields in User model - app/User.php

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

Add your Shopify app API key and secret to `.env`

```
SHOPIFY_KEY=1234567890abcdefghijklmnopqrstuv
SHOPIFY_SECRET=1234567890abcdefghijklmnopqrstuv
```

Publish carter configuration and views.

- config/carter.php
- resources/views/vendor/carter

```
$ php artisan vendor:publish
```


Visit `http://your-site.com/install?shop=your-test-store.myshopify.com` to install app in Shopify store.

Can also create a view `shopify/auth/register.blade.php` with a form containing a text field with name `shop` to submit a post request to `route('shopify.install')`. `http://your-site.com/register` view the form.

More to come.

