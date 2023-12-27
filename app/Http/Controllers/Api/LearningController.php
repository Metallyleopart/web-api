<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Learning;
use App\Models\Task;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getLearning()
    {
        $data = Learning::orderBy('materi', 'asc')->get();
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
    public function addLearning(Request $request)
    {
        $data = new Learning();
        $rules = [
            'name'=>'required',
            'materi'=>'required',
            'teacher_id'=>'required',
            'task_id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal buat materi',
                'data' => $validator->errors(),
            ],400);
        }
        
        // cek ketersediaan materri
        if (Learning::where('name', $request->name)->first()) {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'materi sudah ada',
            ],401);
        }
        
        $data->name = $request->name;
        $data->materi = $request->materi;
        $findTeacher = Teacher::where('user_id',$request->teacher_id)->first();
            // $find = Teacher::find($request->teacher_id);
            if ($findTeacher) {
                $data->teacher_id = $request->teacher_id;
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'id guru tidak ditemukan',
                ],404);
            }
        $findTask = Task::where('id',$request->task_id)->first();
            if ($findTask) {
                $data->task_id = $request->task_id;
            } else {
                if ($request->task_id == '') {
                    $data->task_id = $request->task_id;
                } else {
                    return response()->json([
                        'code' => 404,
                        'status' => false,
                        'message' => 'id tugas tidak ditemukan',
                    ],404);
                }
            }
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil buat materi',
            'data' => $data,
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function findlearning(string $id)
    {
        $data = Learning::find($id);
        if ($data) {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'datanya ketemu nih',
                'data' => $data,
            ],200);
        } else {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'datanya nggak ketemu',
            ],404);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function updateLearning(Request $request, string $id)
    {
        $data = Learning::find($id);
        $rules = [
            'name',
            'materi',
            'teacher_id',
            'task_id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal update materi',
                'data' => $validator->errors(),
            ],400);
        }
        
        // Update yang hanya diisi user dengan menggunakan filled
        if ($request->filled('name')) {
            $data->name = $request->name;
        }

        if ($request->filled('materi')) {
            $data->materi = $request->materi;
        }
        
        $findTeacher = Teacher::where('user_id',$request->teacher_id)->first();
        // $find = Teacher::find($request->teacher_id);
        if ($findTeacher) {
                if ($request->filled('teacher_id')) {
                    $data->teacher_id = $request->teacher_id;
                }
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'id guru tidak ditemukan',
                ],404);
            }
            
            $findTask = Task::where('id',$request->task_id)->first();
            if ($findTask) {
                if ($request->filled('task_id')) {
                    $data->task_id = $request->task_id;
                }
            } else {
                if ($request->task_id == '') {
                    if ($request->filled('task_id')) {
                        $data->task_id = $request->task_id;
                    }
                } else {
                    return response()->json([
                        'code' => 404,
                        'status' => false,
                        'message' => 'id tugas tidak ditemukan',
                    ],404);
                }
            }

        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil update materi',
            'data' => $data,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteLearning(string $id)
    {
        $data = Learning::find($id);
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
            'message' => 'hapus materi berhasil',
        ],200);
        
    }
}
