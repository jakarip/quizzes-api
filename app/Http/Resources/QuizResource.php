<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QuizResource extends JsonResource
{
  
    public function toArray($request): array
    {
        return [
            'id_kuis' => $this->id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'tanggal_upload' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'tanggal_update' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'pertanyaan' => QuestionResource::collection($this->whenLoaded('questions'))
        ];
    }
}
