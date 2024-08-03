<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Resources\QuestionResource;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('answers')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar pertanyaan berhasil diambil',
            'data' => QuestionResource::collection($questions)
        ]);
    }

    public function show($id)
    {
        $question = Question::with('answers')->find($id);

        if ($question) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pertanyaan berhasil diambil',
                'data' => new QuestionResource($question)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Pertanyaan tidak ditemukan'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:quizzes,id',
            'teks' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $question = Question::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil dibuat',
            'data' => new QuestionResource($question)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'teks' => 'sometimes|required|string',
            'quiz_id' => 'sometimes|required|exists:quizzes,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pertanyaan tidak ditemukan'
            ], 404);
        }

        $question->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil diperbarui',
            'data' => new QuestionResource($question)
        ]);
    }

    public function destroy($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pertanyaan tidak ditemukan'
            ], 404);
        }

        $question->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil dihapus'
        ]);
    }
}
