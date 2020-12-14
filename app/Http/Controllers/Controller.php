<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function jsonResponse($data, $code=200, $mess=null)
    {
        if (is_null($mess)) {
            return response()->json(
                $data, $code,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return response()->json(
            ['error' => $mess] + $data, $code,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function isPhoneNumberValid($phone_number) {
        return is_numeric($phone_number) && strlen($phone_number) == 11;
    }

    protected function isVerificationCodeValid($verification_code) {
        return is_numeric($verification_code) && strlen($verification_code) == 4;
    }
}
