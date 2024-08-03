<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id_jawaban' => $this->id,
            'id_pertanyaan' => $this->question_id,
            'teks' => $this->teks,
            'benar' => (bool) $this->benar,
        ];
    }
}
