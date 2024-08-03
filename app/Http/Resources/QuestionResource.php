<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QuestionResource extends JsonResource
{
   
    public function toArray($request): array
    {
        return [
            'id_pertanyaan' => $this->id,
            'id_kuis' => $this->quiz_id,
            'teks' => $this->teks,
            'tanggal_upload' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'tanggal_update' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'jawaban' => AnswerResource::collection($this->whenLoaded('answers'))
        ];
    }
}
