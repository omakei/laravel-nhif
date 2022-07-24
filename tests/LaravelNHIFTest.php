<?php

use Illuminate\Support\Facades\Http;
use Omakei\LaravelNhif\Exceptions\InvalidPayload;
use Omakei\LaravelNhif\LaravelNHIF;

it('can verify nhif member', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/MemberVerification/member_verification_card _exists.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.test').'*' => Http::response($stub, 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::verifyMember('12344554', 1, '', 'verification');

    $this->assertEquals($response, $stub);
});

it('can throw exception if referral number is null when visit type is referral during member verification', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/MemberVerification/member_verification_card _exists.json'),
        true
    );

    Http::fake([config('nhif.url.test') => Http::response($stub, 200)]);
    Http::fake([config('nhif.url.token') => Http::response($auth_stub, 200)]);

    $response = LaravelNHIF::verifyMember('12344554', 3, '', 'verification');

    $this->assertEquals($response, $stub);
})->throws(InvalidPayload::class);

it('can throw exception if authentication failed', function () {
    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/MemberVerification/member_verification_card _exists.json'),
        true
    );

    Http::fake([config('nhif.url.token') => Http::response([], 200)]);

    $response = LaravelNHIF::verifyMember('12344554', 3, '', 'verification');
})->throws(InvalidPayload::class);

it('can get card details', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/MemberVerification/member_verification_card _exists.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.test').'*' => Http::response($stub, 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::getCardDetails('12344554');

    $this->assertEquals($response, $stub);
});

it('can download tariffs without excluded services', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Tariffs/price_list_without_excluded_service.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.tariffs').'*' => Http::response($stub, 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::downloadTariffsWithoutExcludedService('12344554');

    $this->assertEquals($response, $stub);
});

it('can download tariffs with excluded services', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Tariffs/price_list_with_excluded_service.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.tariffs').'*' => Http::response($stub, 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::downloadTariffsWithExcludedService('12344554');

    $this->assertEquals($response, $stub);
});

it('can submit claim to NHIF', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Claims/claim_documents.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.claim').'*' => Http::response(['message' => 'submitted successful.'], 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::submitClaimToNHIF($stub);

    $this->assertEquals($response, ['message' => 'submitted successful.']);
});

it('can get submitted claims', function () {
    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.claim_submitted').'*' => Http::response(['message' => 'submitted claims data.'], 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::getSubmittedClaims('1232', 2017, 9);

    $this->assertEquals($response, ['message' => 'submitted claims data.']);
});

it('can submit referral to NHIF', function () {
    $stub_request = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Referral/referral_request.json'),
        true
    );

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Referral/referral_response.json'),
        true
    );

    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.referral').'*' => Http::response($stub, 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::submitReferralToNHIF($stub_request);

    $this->assertEquals($response, $stub);
});

it('can verify pre approved services', function () {
    $auth_stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Responses/Authentication/token.json'),
        true
    );
    Http::fake([config('nhif.url.pre_approved').'*' => Http::response(['status' => 'VALID'], 200),
        config('nhif.url.token') => Http::response($auth_stub, 200), ]);

    $response = LaravelNHIF::verifyPreApprovedService('12344', '12342', '5625426');

    $this->assertEquals($response, ['status' => 'VALID']);
});
