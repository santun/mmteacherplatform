<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
		
		return [
            'id' => $this->id,
            'category_name' => $this->category->title,
            'question' => $this->question,
			'answer' => $this->answer,
			'published' => $this->published,
			'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
		];	
    }
}
