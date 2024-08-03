<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Http\Resources\QuizResource;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{

    public function index()
    {
        // $quizzes = Quiz::all();
        $quizzes = Quiz::with('questions.answers')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar kuis berhasil diambil',
            'data' => QuizResource::collection($quizzes)
        ]);
    }

    public function show($id)
    {

        $quiz = Quiz::with('questions.answers')->find($id);

        if ($quiz) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kuis berhasil diambil',
                'data' => new QuizResource($quiz)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kuis tidak ditemukan'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = Quiz::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Kuis berhasil dibuat',
            'data' => new QuizResource($quiz)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kuis tidak ditemukan'
            ], 404);
        }

        $quiz->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Kuis berhasil diperbarui',
            'data' => new QuizResource($quiz)
        ]);
    }

    public function destroy($id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kuis tidak ditemukan'
            ], 404);
        }

        $quiz->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kuis berhasil dihapus'
        ]);
    }
}
