<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUser()
    {
        $data = User::with('murid', 'guru', 'admin')->get();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'datanya ketemu nih',
            'data' => $data,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addUser(Request $request)
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
            // cegah agar role tidak melebihi yang tersedia
            if ($request->role == 'murid' ||
                $request->role == 'guru' ||
                $request->role == 'admin') {
                    $data->role = $request->role;
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'Role tidak tersedia',
                ],404);
            }
        }
        $post = $data->save();
        
        // tambahkan user_id ke database yang bersangkutan
        if ($request->role == 'murid') {
            $murid = new Student();
            $murid->user_id = $data->id;
            $murid->save();
        } elseif ($request->role == 'guru') {
            $guru = new Teacher();
            $guru->user_id = $data->id;
            $guru->save();
        } elseif ($request->role == 'admin') {
            $admin = new Admin();
            $admin->user_id = $data->id;
            $admin->save();
        } else {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'Role tidak tersedia',
            ],404);
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil buat akun',
            'data' => $data,
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function findUser(string $id)
    {
        $data = User::find($id);
        if ($data) {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'data ketemu nih',
                'data' => $data,
            ],200);
        } else {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'data nggak ketemu',
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUSer(Request $request, string $id)
    {
        $data = User::find($id);
        $rules = [
            'name',
            'email',
            'password',
            'role'
        ];
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
        // cegah agar role tidak melebihi yang tersedia
        if ($request->role == 'murid' ||
            $request->role == 'guru' ||
            $request->role == 'admin') {
                $data->role = $request->role;
        } else {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'Role tidak tersedia',
            ],404);
        }
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil update akun',
            'data' => $data,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteUSer(string $id)
    {
        $data = User::find($id);
        if (empty($data)) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'id tidak ditemukan',
            ],404);
        }
        
        $post = $data->delete();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'hapus user berhasil',
        ],200);
    }
}
