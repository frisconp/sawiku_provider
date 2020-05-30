<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loggedInUser(Request $request)
    {
        return $this->sendResponse($request->user(), 'Berhasil mendapatkan data Login User.');
    }
}
