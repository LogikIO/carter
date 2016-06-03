<?php

if (! function_exists('carter_route')) {
    function carter_route($key)
    {
        return config("carter.shopify.routes.{$key}");
    }
}