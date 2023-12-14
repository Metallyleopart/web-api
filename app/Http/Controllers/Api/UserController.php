<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            'role',
            'image'=>'mimes:jpg,jpeg,png'
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
        // image
        // ambil image
        $fileImage = $request->file('image');
        // ambil ekstensi Image
        $imageExtension = $fileImage->extension();
        // ganti nama Image
        $imageName = date('ymdhis').".".$imageExtension;
        // pindahkan Image ke folder public
        $fileImage->move(public_path('image'), $imageName);

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
        $data->image = $imageName;
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
    public function updateUser(Request $request, string $id)
    {
        $data = User::find($id);
        // cek user
        if (!$data) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'user tidak tersedia',
            ],404);
        }
        $rules = [
            'name',
            'email',
            'password',
            'role',
            'image'=>'nullable|mimes:jpg,jpeg,png'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal update akun',
                'data' => $validator->errors(),
            ],400);
        }

        // Update yang hanya diisi user dengan menggunakan filled
        if ($request->filled('name')) {
            $data->name = $request->name;
        }

        if ($request->filled('email')) {
            $data->email = $request->email;
        }

        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);
        }

        if ($request->filled('role')) {
            if (in_array($request->role, ['murid', 'guru', 'admin'])) {
                $data->role = $request->role;
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'Role tidak tersedia',
                ],404);
            }
        }
    
        // image
        if ($request->hasFile('image')) {
            // ambil image
            $fileImage = $request->file('image');
            // ambil ekstensi Image
            $imageExtension = $fileImage->extension();
            // ganti nama Image
            $imageName = date('ymdhis').".".$imageExtension;
            // pindahkan Image ke folder public
            $fileImage->move(public_path('image'), $imageName);

            // cari image berdasarkan id untuk dihapus jika sudah akan digantikan oleh foto yang baru
            $dataImage = User::where('id', $id)->first();
            File::delete(public_path('image').'/'.$dataImage->image);

            $data->image = $imageName;
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
        // hapus image
        File::delete(public_path('image').'/'.$data->image);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'hapus user berhasil',
        ],200);
    }
}
