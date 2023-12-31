<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Remidial;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RemidialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getRemidial()
    {
        $data = Remidial::orderBy('id', 'asc')->get();
        return response()->json([
            'code' => 200,
            'status' =>true,
            'message' => 'datanya ada nih',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addRemidial(Request $request)
    {
        $data = new Remidial();
        $rules = [
            'name'=>'required',
            'task_id'=>'required',
            'nilai_awal',
            'nilai_akhir',
            'nilai_gabungan',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal buat remidial',
                'data' => $validator->errors(),
            ],400);
        }
        
        // cek nama sudah ada atau belum
        if (Remidial::where('name', $request->name)->first()) {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'remidial sudah ada',
            ],401);
        }
        
        $data->name = $request->name;
        $findTask = Task::where('id', $request->task_id)->first();
        if ($findTask) {
            $data->task_id = $request->task_id;
        } else {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'ID tugas tidak ditemukan',
                $findTask
            ],401);
        }

        // isi nilai berdasarkan id yang telah dicari
        $data->nilai_awal = $findTask->nilai;
        $data->nilai_remidial = $request->nilai_remidial;
        $data->nilai_akhir = ($data->nilai_awal + $request->nilai_remidial) / 2;
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil buat remidial',
            'data' => $data,
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function findRemidial(string $id)
    {
        $data = Remidial::find($id);
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
    public function updateRemidial(Request $request, string $id)
    {
        $data = Remidial::find($id);
        // cek user
        if (!$data) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'remidial tidak tersedia',
            ],404);
        }
        $rules = [
            'name',
            'task_id',
            'nilai_awal',
            'nilai_remidial',
            'nilai_akhir',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal update remidial',
                'data' => $validator->errors(),
            ],400);
        }
        
        // cek nama sudah ada atau belum
        if (Remidial::where('name', $request->name)->first()) {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'remidial sudah ada',
            ],401);
        }

        // Update yang hanya diisi user dengan menggunakan filled
        if ($request->filled('name')) {
            $data->name = $request->name;
        }

        $findTask = Task::where('id', $request->task_id)->first();
        if ($request->filled('task_id')) {
            if ($findTask) {
                $data->task_id = $request->task_id;
            } else {
                return response()->json([
                    'code' => 401,
                    'status' => false,
                    'message' => 'ID tugas tidak ditemukan',
                    $findTask
                ],401);
            }
        }
        
        if ($request->filled('nilai_awal')) {
            $data->nilai_awal = $findTask->nilai;
        }
        
        if ($request->filled('nilai_remidial')) {
            $data->nilai_remidial = $request->nilai_remidial;
        }
        
        $data->nilai_akhir = ($data->nilai_awal + $request->nilai_remidial) / 2;
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil update remidial',
            'data' => $data,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteRemidial(string $id)
    {
        $data = Remidial::find($id);
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
            'message' => 'berhasil hapus data',
        ],200);
    }
}
