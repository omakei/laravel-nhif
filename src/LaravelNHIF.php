<?php

namespace Omakei\LaravelNhif;

use Illuminate\Support\Facades\Http;
use Omakei\LaravelNhif\Exceptions\InvalidPayload;

class LaravelNHIF
{
    /**
     * @throws \Throwable
     */
    public static function verifyMember(string $card_number, int $visit_type_id, ?string $referral_number, ?string $remarks)
    {
        throw_if(($visit_type_id == 3) && empty($referral_number), InvalidPayload::invalidReferralNumberPayload());

        $response = Http::withHeaders(self::getHeaders())
            ->get(self::getBaseUrl().'verification/AuthorizeCard',
                [
                    'CardNo' => $card_number,
                    'VisitTypeID' => $visit_type_id,
                    'ReferralNo' => $referral_number,
                    'Remarks' => $remarks,
                ]);

        return $response->json();
    }

    public static function getCardDetails(string $card_number)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->get(self::getBaseUrl().'verification/GetCardDetails', ['CardNo' => $card_number]);

        return $response->json();
    }

    public static function downloadTariffsWithoutExcludedService(string $facility_code)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->get(config('nhif.url.tariffs').'GetPricePackage', ['FacilityCode' => $facility_code]);

        return $response->json();
    }

    /**
     * @throws \Throwable
     */
    public static function downloadTariffsWithExcludedService(string $facility_code)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->get(config('nhif.url.tariffs').'GetPricePackageWithExcludedServices', ['FacilityCode' => $facility_code]);

        return $response->json();
    }

    /**
     * @throws \Throwable
     */
    public static function submitClaimToNHIF(array $claim_data)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->post(config('nhif.url.claim'), $claim_data);

        return $response->json();
    }

    /**
     * @throws \Throwable
     */
    public static function submitReferralToNHIF(array $referral_data)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->post(config('nhif.url.referral'), $referral_data);

        return $response->json();
    }

    /**
     * @throws \Throwable
     */
    public static function getSubmittedClaims(string $facility_code, int $claim_year, int $claim_month)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->get(config('nhif.url.claim_submitted'), ['FacilityCode' => $facility_code, 'ClaimYear' => $claim_year, 'ClaimMonth' => $claim_month]);

        return $response->json();
    }

    public static function verifyPreApprovedService(string $card_number, string $reference_number, string $item_code)
    {
        $response = Http::withHeaders(self::getHeaders())
            ->get(config('nhif.url.pre_approved'),
            [
                'CardNo' => $card_number,
                'ReferenceNo' => $reference_number,
                'ItemCode' => $item_code,
            ]);

        return $response->json();
    }

    public static function validateClaimPayload(array $payload)
    {
        $requiredFolioField = [
            'FolioID' => 'uuid',
            'FacilityCode' => 'string',
            'ClaimYear' => 'int',
            'SerialNo' => 'string',
            'CardNo' => 'string',
            'FirstName' => 'string',
            'LastName' => 'string',
            'Gender' => 'string:in,Male|Female',
            'DateOfBirth' => 'Date',
            'TelephoneNo' => 'string',
            'PatientFileNo' => 'string',
            'PatientFile' => 'string',
            'AuthorizationNo' => 'string',
            'AttendanceDate' => 'Date',
            'PatientTypeCode' => 'string',
            'DateAdmitted' => 'Date|nullable',
            'DateDischarged' => 'Date|nullable',
            'PractitionerNo' => 'string',
            'CreatedBy' => 'string',
            'DateCreated' => 'Date',
        ];

        $requiredFolioDiseaseField = [
            'FolioDiseaseID' => 'uuid',
            'FolioID' => 'uuid',
            'DiseaseCode' => 'string',
            'CreatedBy' => 'string',
            'DateCreated' => 'Date',
        ];

        $requiredFolioItemField = [
            'FolioItemID' => 'uuid',
            'FolioID' => 'uuid',
            'ItemCode' => 'string',
            'ItemQuantity' => 'int',
            'UnitPrice' => 'float',
            'AmountClaimed' => 'float',
            'ApprovalRefNo' => 'string',
            'CreatedBy' => 'string',
            'DateCreated' => 'date',
        ];
    }

    public static function authenticate()
    {
        $response = Http::post(config('nhif.url.token'),
                ['username' => config('nhif.credentials.username'),
                    'password' => config('nhif.credentials.password'),
                    'grant_type' => 'password',
                ]);

        return $response->json();
    }

    /**
     * @throws \Throwable
     */
    protected static function getHeaders(): array
    {
        $token = self::authenticate();

        throw_if(empty($token['token_type']), InvalidPayload::unauthenticated());

        return [
            'Authorization' => $token['token_type'].' '.$token['access_token'],
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected static function getBaseUrl()
    {
        return config('nhif.mode') === 'test' ? config('nhif.url.test') : config('nhif.url.production');
    }
}
