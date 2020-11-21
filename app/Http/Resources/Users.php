<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class Users extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $date_format = Config::get('constants.api.date_format');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'specialist' => $this->specialist,
            'approved' => $this->approved,
            'created_at' => $this->created_at->format($date_format),
            'updated_at' => $this->updated_at->format($date_format)
        ];
    }
}
