<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create(Request $request) {
        $phone_number = $request->input('phone_number');
        if (!$this->isPhoneNumberValid($phone_number)) {
            return $this::jsonResponse([], 400, "Некорректный номер телефона");
        }

        $user = User::where('phone_number', '=', $phone_number)->first();
        if (is_null($user)) {
            $user = new User();
        }
        $user->phone_number = $phone_number;
        $code = random_int(1111, 9999);
        $user->verification_code = $code;
        $user->save();
        $this->sendMessage("Код подтверждения для ".$phone_number.": ".$code);
        return $this::jsonResponse(['mess' => "Код подтверждения выслан в смс"]);
        // TODO send verification code via SMS
    }

    public function confirm(Request $request) {
        $phone_number = $request->input('phone_number');
        if (!$this->isPhoneNumberValid($phone_number)) {
            return $this::jsonResponse([], 400, "Некорректный номер телефона");
        }
        $code = $request->input('verification_code');
        if (!$this->isPhoneNumberValid($phone_number)) {
            return $this::jsonResponse([], 400, "Неверный формат кода подтверждения");
        }
        $user = User::where('phone_number', '=', $phone_number)->first();
        if (is_null($user)) {
            return $this::jsonResponse([], 404, "Нет пользователя с таким номером телефона");
        }

        if ($user->verification_code == $code) {
            $user->verification_code = null;
            if ($user->api_token) {
                return $this::jsonResponse(['token' => $user->api_token], 201);
            }
            $token = Str::random(60);
            $user->api_token = $token;
            $user->save();
            return $this::jsonResponse(['token' => $token], 201);
        }
        return $this::jsonResponse([], 403, "Неверный код подтверждения");
    }

    public function update(Request $request) {
        $user = User::find($request->user()->id);
        $user->phone_number = $request->input('phone_number', $user->phone_number);
        $user->name = $request->input('name', $user->name);
        $user->address = $request->input('address', $user->address);
        $user->contact_number = $request->input('contact_number', $user->contact_number);
        $user->avatar = $request->input('avatar', $user->avatar);
        $user->save();
        return $this::jsonResponse($user);
    }

    public function show(Request $request) {
        return $this::jsonResponse(User::find($request->user()->id));
    }
}
