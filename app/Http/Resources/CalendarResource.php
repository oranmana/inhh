<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CalendarResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->cldate,
            
        ];
    }
}
