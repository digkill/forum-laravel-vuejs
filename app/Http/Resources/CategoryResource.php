<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'id'   => $this->id,
            'path' => $this->path,
            //'questions_count'=>$this->questions->count(),
            //'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
