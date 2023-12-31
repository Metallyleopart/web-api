<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getTask()
    {
        $data = Task::orderBy('name', 'asc')->get();
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
    public function addTask(Request $request)
    {
        $data = new Task();
        $rules = [
            'name'=>'required',
            'tugas'=>'required',
            'nilai',
            'status_nilai',
            'student_id'=>'required',
            'teacher_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal tambah tugas',
                'data' => $validator->errors(),
            ],400);
        }
        
        if (Task::where('name', $request->name)->first()) {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'tugas sudah ada',
            ],401);
        }
        
        $data->name = $request->name;
        $data->tugas = $request->tugas;
        $data->nilai = $request->nilai;
        if ($request->nilai > 76) {
            $data->status_nilai = 'lulus';
        }else {
            $data->status_nilai = 'remidial';
        }
        $findStudent = Student::where('user_id', $request->student_id)->first();
        if ($findStudent) {
            $data->student_id = $request->student_id;
        } else {
            if ($request->student_id == '') {
                $data->student_id = $request->student_id;
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'id murid tidak ditemukan',
                ],404);
            }
        }
        $findTeacher = Teacher::where('user_id', $request->teacher_id)->first();
        if ($findTeacher) {
            $data->teacher_id = $request->teacher_id;
        } else {
            if ($request->teacher_id == '') {
                $data->teacher_id = $request->teacher_id;
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'id guru tidak ditemukan',
                ],404);
            }
        }
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil buat tugas',
            'data' => $data,
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function findTask(string $id)
    {
        $data = Task::find($id);
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
    public function updateTask(Request $request, string $id)
    {
        $data = Task::find($id);
        // cek user
        if (!$data) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'tugas tidak tersedia',
            ],404);
        }

        $rules = [
            'name',
            'tugas',
            'student_id',
            'teacher_id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'gagal update tugas',
                'data' => $validator->errors(),
            ],400);
        }
        
        // Update yang hanya diisi user dengan menggunakan filled
        if ($request->filled('name')) {
            $data->name = $request->name;
        }

        if ($request->filled('tugas')) {
            $data->tugas = $request->tugas;
        }

        $findStudent = Student::where('user_id', $request->student_id)->first();
        if ($findStudent) {
            if ($request->filled('student_id')) {
                $data->student_id = $request->student_id;
            }
        } else {
            if ($request->student_id == '') {
                if ($request->filled('student_id')) {
                    $data->student_id = $request->student_id;
                }
            } else {
                return response()->json([
                    'code' => 404,
                    'status' => false,
                    'message' => 'id murid tidak ditemukan',
                ],404);
            }
        }

        $findTeacher = Teacher::where('user_id', $request->teacher_id)->first();
        if ($findTeacher) {
            if ($request->filled('teacher_id')) {
                $data->teacher_id = $request->teacher_id;
            }
        } else {
            if ($request->teacher_id == '') {
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
        }
        
        $post = $data->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'berhasil update tugas',
            'data' => $data,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteTask(string $id)
    {
        $data = Task::find($id);
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
