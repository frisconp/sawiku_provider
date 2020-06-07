<?php

namespace App\Http\Controllers\API\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
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
            'email.required' => 'Alamat email tidak boleh kosong.',
            'email.email' => 'Alamat email tidak valid.',
            'password.required' => 'Kata sandi tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validasi gagal.', 401);
        } else {
            $admin = Admin::where('email', $request->email)->first();

            if ($admin) {
                if (Hash::check($request->password, $admin->password)) {
                    $accessToken = $admin->createToken($admin->id)->accessToken;

                    $data = [
                        'admin' => $admin,
                        'accessToken' => $accessToken,
                    ];

                    return $this->sendResponse($data, 'Login Admin Berhasil.');
                } else {
                    return $this->sendError(null, 'Password Admin Salah.', 401);
                }
            } else {
                return $this->sendError(null, 'Data Admin Tidak Valid.');
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user('api-admin')->token()->revoke();

        return $this->sendResponse(null, 'Logout berhasil.');
    }

    public function detail(Request $request)
    {
        return $this->sendResponse($request->user('api-admin'), 'Berhasil mendapatkan data Admin Login.');
    }
}
