<?php

namespace Omakei\LaravelNhif\Traits;

use Illuminate\Support\Facades\Validator;
use Omakei\LaravelNhif\Exceptions\InvalidClaimException;

/**
 *  Validating Provided Inputs
 */
trait InputValidation
{
    private static function validateClaimData(array $payload)
    {
        $validator = Validator::make($payload, [
            'entities.*.FolioID' => ['required', 'string'],
            'entities.*.FacilityCode' => ['required', 'string'],
            'entities.*.ClaimYear' => ['required', 'numeric'],
            'entities.*.SerialNo' => ['required', 'string'],
            'entities.*.CardNo' => ['required', 'string'],
            'entities.*.FirstName' => ['required', 'string'],
            'entities.*.LastName' => ['required', 'string'],
            'entities.*.Gender' => ['required', 'string'],
            'entities.*.DateOfBirth' => ['required', 'date'],
            'entities.*.TelephoneNo' => ['required', 'string'],
            'entities.*.PatientFileNo' => ['required', 'string'],
            'entities.*.PatientFile' => ['required', 'string'],
            'entities.*.AuthorizationNo' => ['required', 'string'],
            'entities.*.AttendanceDate' => ['required', 'date'],
            'entities.*.PatientTypeCode' => ['required', 'string'],
            'entities.*.DateAdmitted' => ['required', 'date'],
            'entities.*.DateDischarged' => ['required', 'date'],
            'entities.*.PractitionerNo' => ['required', 'string'],
            'entities.*.CreatedBy' => ['required', 'string'],
            'entities.*.DateCreated' => ['required', 'date'],
            'entities.*.FolioDiseases.*.FolioDiseaseID' => ['required', 'string'],
            'entities.*.FolioDiseases.*.FolioID' => ['required', 'string'],
            'entities.*.FolioDiseases.*.DiseaseCode' => ['required', 'string'],
            'entities.*.FolioDiseases.*.CreatedBy' => ['required', 'string'],
            'entities.*.FolioDiseases.*.DateCreated' => ['required', 'date'],
            'entities.*.FolioItems.*.FolioItemID' => ['required', 'string'],
            'entities.*.FolioItems.*.FolioID' => ['required', 'string'],
            'entities.*.FolioItems.*.ItemCode' => ['required', 'string'],
            'entities.*.FolioItems.*.ItemQuantity' => ['required', 'numeric'],
            'entities.*.FolioItems.*.UnitPrice' => ['required', 'numeric'],
            'entities.*.FolioItems.*.AmountClaimed' => ['required', 'numeric'],
            'entities.*.FolioItems.*.ApprovalRefNo' => ['required', 'string'],
            'entities.*.FolioItems.*.CreatedBy' => ['required', 'string'],
            'entities.*.FolioItems.*.DateCreated' => ['required', 'date'],
            'entities.*.FolioItems.*.LastModifiedBy' => ['required', 'string'],
            'entities.*.FolioItems.*.LastModified' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new InvalidClaimException($errors);
        }

    }
}
