
<p align="center">
    <img src="/art/nhif-logo.png" width="300" title="NHIF Logo" alt="NextSMS Logo">
</p>

# Laravel NHIF

A Laravel package to integrate NHIF Tanzania with hospital management systems.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/omakei/laravel-nhif.svg?style=flat-square)](https://packagist.org/packages/omakei/laravel-nhif)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/omakei/laravel-nhif/run-tests?label=tests)](https://github.com/omakei/laravel-nhif/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/omakei/laravel-nhif/Check%20&%20fix%20styling?label=code%20style)](https://github.com/omakei/laravel-nhif/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/omakei/laravel-nhif.svg?style=flat-square)](https://packagist.org/packages/omakei/laravel-nhif)


## Installation

You can install the package via composer:

```bash
composer require omakei/laravel-nhif
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-nhif-config"
```
The following keys must be available in your `.env` file:

```bash
NHIF_USERNAME=
NHIF_PASSWORD=
```

This is the contents of the published config file:

```php
return [

    'credentials' => [
        'username' => env('NHIF_USERNAME', ''),
        'password' => env('NHIF_PASSWORD', ''),
    ],
    'mode' => 'test', //it maybe test or production
    'url' => [
        'test' => 'http://196.13.105.15/nhifservice/breeze/',
        'production' => 'https://verification.nhif.or.tz/nhifservice/breeze/',
        'token' => 'https://verification.nhif.or.tz/nhifservice/Token/',
        'tariffs' => 'https://verification.nhif.or.tz/claimsserver/api/v1/Packages/',
        'claim' => 'https://verification.nhif.or.tz/claimsserver/api/v1/claims/SubmitFolios',
        'claim_submitted' => 'https://verification.nhif.or.tz/claimsServer/api/v1/claims/getSubmittedClaims',
        'referral' => 'https://verification.nhif.or.tz/nhifservice/breeze/verification/AddReferral',
        'pre_approved' => 'https://verification.nhif.or.tz/nhifservice/breeze/verification/GetReferenceNoStatus',
    ],
];
```


## Usage

### verify member to NHIF 
```php
$response = LaravelNHIF::verifyMember(card_number:'12344554', visit_type_id:1, referral_number:'', remarks:'verification');
// response payload structure
{
    "CardNo": "01-nhif241",
    "CardStatus": "Active",
    "FirstName": "Amour",
    "MiddleName": "R",
    "LastName": "Hamad",
    "FullName": "Amour R Hamad",
    "Gender": "Male",
    "DateOfBirth": "1974- 03 - 18",
    "ExpiryDate": "",
    "AuthorizationStatus": "ACCEPTED",
    "AuthorizationNo": "720002",
    "EmployerNo": "7000885",
    "SchemeID": " 1001 ",
    "ProductCode": "NH001",
    "Remarks": "Verified OK"
}

```

### get card details
```php
$response = LaravelNHIF::getCardDetails(card_number:'12344554');
// response payload structure
{
    "CardNo": "01-nhif241",
    "CardStatus": "Active",
    "FirstName": "Amour",
    "MiddleName": "R",
    "LastName": "Hamad",
    "FullName": "Amour R Hamad",
    "Gender": "Male",
    "DateOfBirth": "1974- 03 - 18",
    "ExpiryDate": "",
    "AuthorizationStatus": "ACCEPTED",
    "AuthorizationNo": "720002",
    "EmployerNo": "7000885",
    "SchemeID": " 1001 ",
    "ProductCode": "NH001",
    "Remarks": "Verified OK"
}
```

### download tariffs without excluded services
```php
$response = LaravelNHIF::downloadTariffsWithoutExcludedService(facility_code:'12344554');
// response payload structure
{
    "FacilityCode": " 01099 ",
    "PricePackage": [
        {
            "ItemCode": "10001",
            "ItemName": "General Practitioner Consultation",
            "PackageID": 102,
            "SchemeID": 1001,
            "UnitPrice": 10000.0,
            "IsRestricted": false
        },
        {
            "ItemCode": "10001",
            "ItemName": "General Practitioner Consultation",
            " PackageID": 201,
            "SchemeID": 1002,
            "UnitPrice": 25000.0,
            "IsRestricted": false
        }
    ],
    "ExcludedServices": [
        {
            "ItemCode": "10001",
            "SchemeID": 1001,
            "ExcludedForProducts": "NH003,NH004"
        },
        {
            "ItemCode": "10002",
            "SchemeID": 1003,
            "ExcludedForProducts": "NH001,NH002"
        }
    ]
}


```

### download tariffs with excluded services
```php
$response = LaravelNHIF::downloadTariffsWithExcludedService(facility_code:'12344554');
// response payload structure

{
    [
        {
            "ItemCode": "10001",
            "ItemName": "General Practitioner Consultation",
            "PackageID": 102,
            "UnitPrice": 10000.0,
            "IsRestricted": false
        },
        {
            "ItemCode": "10001",
            "ItemName": "General Practitioner Consultation",
            "PackageID": 201,
            "UnitPrice": 25000.0,
            "IsRestricted": false
        }
    ]
}
```

### submit claim to NHIF
```php
$data = {
    "entities": [
        {
            "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
            "ClaimYear": 2016,
            "ClaimMonth": 7,
            "FolioNo": 1,
            "SerialNo": "SN00 099",
            "CardNo": "308900035308",
            "FirstName": "Flora",
            "LastName": "Mataba",
            "Gender": "Female",
            "DateOfBirth": "1974- 01 -23T16:56:20.287",
            "Age": 30.0,
            "TelephoneNo": "0686155255",
            "PatientFileNo": null,
            "PatientFile": "GQ8XQAYFAiEMfN0qD0COTgMX......",
            "AuthorizationNo": null,
            "AttendanceDate": "2014- 09 -01T00:00:00",
            "PatientTypeCode": "OUT",
            "DateAdmitted": null,
            "DateDischarged": null,
            "PractitionerNo": "12345",
            "CreatedBy": "Administrator",
            "DateCreated": "2015- 01 -23T16:56:20.223",
            "LastModifiedBy": "Administrator",
            "LastModified": "2015-01 - 2 6T12:31:25.097",
            "FolioDiseases": [
                {
                    "FolioDiseaseID": "e9429e1c-f892-40ae-8c0a-a3bc0095681f",
                    "DiseaseCode": "084",
                    "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
                    "Remarks": null,
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015 - 01 -23T16:56:20.287",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.287"
                }
            ],
            "FolioItems": [
                {
                    "FolioItemID": "e0d30408- 1863 - 4eb4-8cce-a3bc00957501",
                    "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
                    "ItemCode": "11",
                    "OtherDetails": null,
                    "ItemQuantity": 1,
                    "UnitPrice": 2000.0,
                    "AmountClaimed": 2000.0,
                    "ApprovalRefNo": "NHIF/REF/201000024",
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015- 01 -23T16:56:20.35",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.35"
                },
                {
                    "FolioItemID": "84ae41a0-514c-489d-8e6f-a3bc00958289",
                    "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
                    "ItemCode": "11533",
                    "OtherDetails": null,
                    "ItemQuantity": 1,
                    "UnitPrice": 1950.0,
                    "AmountClaimed": 1950.0,
                    "ApprovalRefNo": "null",
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015- 01 -23T16:56:20.37",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.37"
                },
                {
                    "FolioItemID": "3c5b814c-7fbf-454b-9c9e-a3bc00985ba5",
                    "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
                    "ItemCode": "11004",
                    "OtherDetails": null,
                    "ItemQuantity": 3,
                    "UnitPrice": 100.0,
                    "AmountClaimed": 300.0,
                    "ApprovalRefNo": "null",
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015- 01 -23T16:56:20.39",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.39"
                },
                {
                    "FolioItemID": "338ce17c- 1655 - 417f-80e3-a3ca01019326",
                    "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
                    "ItemCode": "5039",
                    "OtherDetails": null,
                    "ItemQuantity": 2,
                    "UnitPrice": 3000.0,
                    "AmountClaimed": 6000.0,
                    "ApprovalRefNo": "null",
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015- 01 -23T16:56:20.41",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2 015 -01 - 23T16:56:20.41"
                },
                {
                    "FolioItemID": "ae3e912a-137b-48e7- 9853 -a3ca01058118",
                    "FolioID": "817d6e75-3bef-4d25-a2e1-a3bc009530ab",
                    "ItemCode": "7402",
                    "OtherDetails": null,
                    "ItemQuantity": 1,
                    "UnitPrice": 20000.0,
                    "AmountClaimed": 20000.0,
                    "ApprovalRefNo": "null",
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015- 01 -23T16:56:20.47",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.47"
                }
            ]
        },
        {
            "FolioID": "d3d7656b-7c3b- 4392 - b75f-a3ca01048e2b",
            "ClaimYear": 2016,
            "ClaimMonth": 7,
            "FolioNo": 2,
            "SerialNo": "13/109998777",
            "CardNo": "109900035308",
            "FirstName": "Amour",
            "LastName": "Rashid",
            "Gender": "Male",
            "DateOfBirth": "1974- 01 -23T16:56:20.287",
            "Age": 40.0,
            "TelephoneNo": "0686155255",
            "PatientFileNo": null,
            "AuthorizationNo": null,
            "AttendanceDate": "2014- 09 -01T00:00:00",
            "PatientTypeCode": "OUT",
            "DateAdmitted": null,
            "DateDischarged": null,
            "PractitionerNo": null,
            "CreatedBy": "Administrator",
            "DateCreated": "2015- 01 -23T16:56:20.247",
            "LastModifiedBy": "Administrator",
            "LastModified": "2015-01 - 28T13:31:09.297",
            "FolioDiseases": [
                {
                    "FolioDiseaseID": "c4f56a4c- 6217 - 475d-b3de-a3ca0104c9dc",
                    "DiseaseCode": "084",
                    "FolioID": "d3d7656b-7c3b- 4392 - b75f-a3ca01048e2b",
                    "Remarks": null,
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015 - 01 -23T16:56:20.307",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.307"
                }
            ],
            "FolioItems": [
                {
                    "FolioItemID": "248fa65b-dab8-4ca2- 9905 -a3ca0104cfc7",
                    "FolioID": "d3d7656b-7c3b- 4392 - b75f-a3ca01048e2b",
                    "ItemCode": "5036",
                    "OtherDetails": null,
                    "ItemQuantity": 1,
                    "UnitPrice": 3200.0,
                    "AmountClaimed": 3200.0,
                    "ApprovalRefNo": "null",
                    "CreatedBy": "Administrator",
                    "DateCreated": "2015- 01 -23T16:56:20.43",
                    "LastModifiedBy": "Administrator",
                    "LastModified": "2015-01 - 23T16:56:20.43"
                }
            ]
        }
    ]
}

$response = LaravelNHIF::submitClaimToNHIF(claim_data:$data);
// response payload structure



```

### get submitted claims
```php
$response = LaravelNHIF::getSubmittedClaims(facility_code:'1232',claim_year: 2017,claim_month: 9);
// response payload structure


```


### submit referral
```php
$data = {
    "CardNo": "01-NHIF45",
    "AuthorizationNo": "623456789",
    "PatientFullName": "Said Juma",
    "PhysicianMobileNo": "0655232365",
    "Gender": "Male",
    "PhysicianName": "Amour Rashid",
    "PhysicianQualificationID": 2,
    "ServiceIssuingFacilityCode": "01099",
    "ReferringDiagnosis": "084,085",
    "ReasonsForReferral": "Fractured hand needs attention from Orthopaedician at MOI"
}

$response = LaravelNHIF::submitReferralToNHIF(referral_data:$data);
// response payload structure

{
    "$id": "1",
    "$type": "NHIFService.Models.PatientReferral, NHIFService",
    "ReferralID": "838ba4f0-8a80- 4658 - bba5-e61ff602ca1d",
    "CardNo": "01-NHIF45",
    "AuthorizationNo": "623456789",
    "PatientFullName": "Said Juma",
    "Gender": "Male",
    "ReferringDate": "2018- 01 - 29T10:27:31.637",
    "PhysicianName": "Amour Rashid",
    "PhysicianMobileNo": null,
    "ReferralNo": "21081000019",
    "ServiceIssuingFacilityCode": "01099",
    "SourceFacilityCode": "06697",
    "PhysicianQualificationID": 2,
    "ReferringDiagnosis": "084,085",
    "ReasonsForReferral": "Fractured hand needs attention from Orthopaedician at MOI",
    "CreatedBy": "arashid",
    "DateCreated": "2018- 01 - 29T10:27:31.637",
    "LastModifiedBy": "arashid",
    "LastModified": "2018- 01 - 29T10:27:31.637"
}
```

### verify pre approved services
```php
$response = LaravelNHIF::verifyPreApprovedService(card_number:'12344', reference_number:'12342', item_code:'5625426');
// response payload structure

VALID or INVALID
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/omakei/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [omakei](https://github.com/omakei)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
