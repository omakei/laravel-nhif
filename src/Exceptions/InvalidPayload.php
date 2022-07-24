<?php

namespace Omakei\LaravelNhif\Exceptions;

use Exception;

final class InvalidPayload extends Exception
{
    public static function invalidReferralNumberPayload(): InvalidPayload
    {
        return new InvalidPayload('For Referral visit, referral number must be provided.');
    }

    public static function unauthenticated(): InvalidPayload
    {
        return new InvalidPayload('Authorization has been denied for this request. Unable to get access token from NHIF.');
    }
}
