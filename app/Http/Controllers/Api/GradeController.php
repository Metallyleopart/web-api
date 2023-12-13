<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getGrade()
    {
        $data = Grade::orderBy('name', 'asc')->get();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'datanya ada nih',
            'data' => $data,
        ],200); 
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function addGrade(Request $request)
    {
        $data = new Grade();
        $rules = [
            'id',
            'name' => 'required',
        ];

        $Validator = Validator::make($request->all(), $rules);
        if ($Validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal nambah kelas',
                'error' => $Validator->errors(),
            ],400); 
        }
        
        // cek ketersediaan kelas
        if (Grade::where('name', $request->name)->first()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'kelas sudah ada',
            ],400); 
        }
        
        $data->id = $request->id;
        $data->name = $request->name;
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil buat kelas',
            'data' => $data,
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function findGrade(string $id)
    {
        $data = Grade::find($id);
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
    public function updateGrade(Request $request, string $id)
    {
        $data = Grade::find($id);
        $rules = [
            'id',
            'name' => 'required',
        ];

        $Validator = Validator::make($request->all(), $rules);
        if ($Validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal nambah kelas',
                'error' => $Validator->errors(),
            ],400); 
        }
        
        // cek ketersediaan kelas
        if (Grade::where('name', $request->name)->first()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'kelas sudah ada',
            ],400); 
        }
        
        $data->id = $request->id;
        $data->name = $request->name;
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil update kelas',
            'data' => $data,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteGrade(string $id)
    {
        $data = Grade::find($id);
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
            'message' => 'data berhasil dihapus',
        ],200);
    }
}
