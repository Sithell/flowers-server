<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create(Request $request) {
        $phone_number = $request->input('phone_number');
        $user = User::where('phone_number', '=', $phone_number)->first();
        if (is_null($user)) {
            $user = new User();
        }
        $user->phone_number = $phone_number;
        $code = random_int(1111, 9999);
        $user->verification_code = $code;
        $user->save();
        return $code;
        // TODO send verification code via SMS
    }

    public function confirm(Request $request) {
        $phone_number = $request->input('phone_number');
        $code = $request->input('verification_code');
        $user = User::where('phone_number', '=', $phone_number)->first();
        if ($user->verification_code == $code) {
            $token = Str::random(60);
            $user->api_token = $token;
            $user->verification_code = null;
            $user->save();
            return $token;
        }
        return "Invalid verification code";
    }
}
