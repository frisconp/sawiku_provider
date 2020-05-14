<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Harap masukkan alamat email Anda.',
            'email.email' => 'Alamat Email tidak valid.',
            'password.required' => 'Harap masukkan kata sandi.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validasi gagal!', 401);
        } else {
            if (Auth::attempt($request->all(), true)) {
                $user = Auth::user();
                $accessToken = $user->createToken($user->email)->accessToken;

                $data = [
                    'user' => $user,
                    'access_token' => $accessToken,
                ];

                return $this->sendResponse($data, 'Login berhasil!');
            } else {
                return $this->sendError(null, 'Email dan Kata Sandi tidak cocok.', 401);
            }
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'same:password'],
        ], [
            'name.required' => 'Harap masukkan nama Anda.',
            'name.max' => 'Nama yang Anda masukkan terlalu panjang.',
            'email.required' => 'Harap masukkan alamat email Anda.',
            'email.email' => 'Alamat Email tidak valid.',
            'email.unique' => 'Alamat Email telah terdaftar.',
            'phone_number.required' => 'Harap masukkan nomor telepon Anda.',
            'phone_number.max' => 'Nomor telepon terlalu panjang.',
            'password.required' => 'Harap masukkan kata sandi.',
            'password.min' => 'Panjang kata sandi minimal 8 karakter.',
            'confirm_password.required' => 'Harap masukkan konfirmasi kata sandi.',
            'confirm_password.same' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validasi gagal.', 401);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->password);
            $user->role_id = 1;
            $user->save();

            return $this->sendResponse($user, 'Registrasi berhasil.', 201);
        }
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        return $this->sendResponse(null, 'Logout berhasil.');
    }
}
