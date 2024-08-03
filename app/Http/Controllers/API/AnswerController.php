<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Http\Resources\AnswerResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar jawaban berhasil diambil',
            'data' => AnswerResource::collection($answers)
        ]);
    }


    public function show($id)
    {
        $answer = Answer::find($id);

        if ($answer) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jawaban berhasil diambil',
                'data' => new AnswerResource($answer)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Jawaban tidak ditemukan'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'teks' => 'required|string|max:255',
            'benar' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $questionId = $request->input('question_id');
        $benar = $request->input('benar');

        if ($benar && Answer::where('question_id', $questionId)->where('benar', true)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya boleh ada satu jawaban benar untuk setiap pertanyaan'
            ], 400);
        }

        $answer = Answer::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jawaban berhasil dibuat',
            'data' => new AnswerResource($answer)
        ], 201);
    }


    public function update(Request $request, $id)
    {
        // Log data untuk check jawaban
        // Log::info('Updating answer', [
        //     'request_data' => $request->all(),
        //     'current_answer' => Answer::find($id)
        // ]);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'teks' => 'sometimes|required|string|max:255',
            'benar' => 'sometimes|required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $answer = Answer::find($id);

        if (!$answer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jawaban tidak ditemukan'
            ], 404);
        }

        $questionId = $answer->question_id;
        $benar = $request->input('benar', $answer->benar);

        // Memastikan hanya 1 jawban benar per pertanyaan
        if ($benar && Answer::where('question_id', $questionId)->where('benar', true)->where('id', '<>', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya boleh ada satu jawaban benar untuk setiap pertanyaan'
            ], 400);
        }

        // Update jawaban
        $answer->teks = $request->input('teks', $answer->teks);
        $answer->benar = $benar;
        $answer->save();

        // Log jawaban yang berhasil diperbarui
        // Log::info('Answer updated successfully', [
        //     'updated_answer' => $answer
        // ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jawaban berhasil diperbarui',
            'data' => new AnswerResource($answer)
        ]);
    }

    public function destroy($id)
    {
        $answer = Answer::find($id);

        if (!$answer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jawaban tidak ditemukan'
            ], 404);
        }

        $answer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jawaban berhasil dihapus'
        ]);
    }
}
