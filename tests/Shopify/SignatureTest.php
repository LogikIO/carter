<?php


use NickyWoolf\Carter\Shopify\Signature;

class SignatureTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_verify_signature_is_from_shopify()
    {
        $hmac = hash_hmac('sha256', 'timestamp=1234567890&shop=foo.bar.myshopify.com', 'client_secret');

        $request = [
            'signature' => 'will-be-removed-during-verification',
            'hmac'      => $hmac,
            'timestamp' => '1234567890',
            'shop'      => 'foo.bar.myshopify.com'
        ];

        $signature = new Signature($request);

        $this->assertTrue($signature->hasValidHmac('client_secret'));
    }

    /** @test */
    function it_can_detect_an_invalid_signature()
    {
        $hmac = hash_hmac('sha256', 'timestamp=1234567890&shop=foo.bar.myshopify.com', 'some_different_secret');

        $request = [
            'signature' => 'will-be-removed-during-verification',
            'hmac'      => 'will-also-be-removed',
            'timestamp' => '1234567890',
            'shop'      => 'foo.bar.myshopify.com'
        ];

        $signature = new Signature($request);

        $this->assertFalse($signature->hasValidHmac($hmac, 'client_secret'));
    }

    /** @test */
    function it_can_verify_shop_domain_pattern()
    {
        $request = ['shop' => 'foobar.myshopify.com'];

        $signature = new Signature($request);

        $this->assertTrue($signature->hasValidHostname());
    }

    /** @test */
    function it_can_detect_an_invalid_shop_domain_pattern()
    {
        $request = ['shop' => 'foobar.example.com'];

        $signature = new Signature($request);

        $this->assertFalse($signature->hasValidHostname());
    }

    /** @test */
    function it_can_verify_nonce()
    {
        $request = ['state' => '12345'];

        $signature = new Signature($request);

        $this->assertTrue($signature->hasValidNonce('12345'));
    }

    /** @test */
    function it_can_detect_invalid_nonce()
    {
        $request = ['state' => '12345'];

        $signature = new Signature($request);

        $this->assertFalse($signature->hasValidNonce('abcde'));
    }

    /** @test */
    function it_does_not_allow_empty_string_for_nonce_check()
    {
        $request = ['state' => ''];

        $signature = new Signature($request);

        $this->assertFalse($signature->hasValidNonce(''));
    }

    /** @test */
    function it_can_generate_a_valid_hmac()
    {
        parse_str((new Signature([]))->sign('client_secret'), $request);
        $check= new Signature($request);

        $this->assertTrue($check->hasValidHmac('client_secret'));
    }

    /** @test */
    function it_can_validate_webhook_signature()
    {
        $header = 'y3AoiwEr8uvBsis6NSVbs4DJ9XSa/s4q88Tdk4QVF90=';
        $data = '{"id":15416853,"name":"Carter Development","email":"woolfnicky@gmail.com","domain":"carter-development.myshopify.com","created_at":"2016-10-12T07:36:20-07:00","province":"California","country":"US","address1":"3126 Barbados PL","zip":"92626","city":"Costa Mesa","source":"nicky-woolf","phone":"8157624236","updated_at":"2016-10-12T12:25:07-07:00","customer_email":null,"latitude":33.684308,"longitude":-117.928099,"primary_location_id":null,"primary_locale":"en","address2":null,"country_code":"US","country_name":"United States","currency":"USD","timezone":"(GMT-08:00) America\/Los_Angeles","iana_timezone":"America\/Los_Angeles","shop_owner":"Nicky Woolf","money_format":"${{amount}}","money_with_currency_format":"${{amount}} USD","province_code":"CA","taxes_included":false,"tax_shipping":null,"county_taxes":true,"plan_display_name":"affiliate","plan_name":"affiliate","has_discounts":false,"has_gift_cards":false,"myshopify_domain":"carter-development.myshopify.com","google_apps_domain":null,"google_apps_login_enabled":null,"money_in_emails_format":"${{amount}}","money_with_currency_in_emails_format":"${{amount}} USD","eligible_for_payments":true,"requires_extra_payments_agreement":false,"password_enabled":true,"has_storefront":true,"eligible_for_card_reader_giveaway":null,"finances":true,"setup_required":false,"force_ssl":true}';
        $signature = new Signature([]);

        $this->assertTrue($signature->hasValidWebhookHmac($header, $data, '5ef7dd3db605eea3bc04950ec74d68b6'));
    }
}