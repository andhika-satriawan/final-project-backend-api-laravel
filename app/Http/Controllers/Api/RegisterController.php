<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // buat validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        // jika validasi gagal, kirim response error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // proses input
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // kirim response success
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user,
            ], 200);
        }

        // return response json gagal ditambahkan
        return response()->json([
            'success' => false,

        ], 400);
    }
}
