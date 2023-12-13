<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email'=>'required|email',
            'password'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal login',
                'data' => $validator->errors(),
            ],400);
        }
        // cek auth
        $dataUser = User::where('email', $request->email)->first();
        if (Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'berhasil login',
                'token' => $dataUser->createToken('user')->plainTextToken,
            ],200); 
        } else {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'Email dan password tidak valid',
            ],401); 
        }
    }

    public function register(Request $request)
    {
        $data = new User();
        $rules = [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'role'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal buat akun',
                'data' => $validator->errors(),
            ],400);
        }
        // cek email sudah ada atau belum
        if (User::where('email', $request->email)->first()) {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'Email sudah ada',
            ],401);
        }
        $data->name = $request->name;
        $data->email = $request->email;
        // hash password agar tidak diketahui
        $data->password = Hash::make($request->password);
        if ($request->role == '') {
            $data->role = 'murid';
        } else {
            $data->role = $request->role;
        }
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil buat akun',
            'data' => $data,
        ],200);
    }
}
