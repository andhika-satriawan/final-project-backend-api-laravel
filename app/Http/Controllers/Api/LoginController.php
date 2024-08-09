<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // buat validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        // jika validasi gagal, maka kembalikan response error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // get crendentials dari request
        $credentials = $request->only('email', 'password');

        // jika authentication gagal
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        // if auth success
        return response()->json([
            'success' => true,
            'user' => Auth::guard('api')->user(),
            'token' => $token
        ], 200);
    }
}
