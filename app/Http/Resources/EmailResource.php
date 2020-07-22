<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
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
            'email' => Str::contains($this->email, '@junk') ? Str::before($this->email, '@junk') : $this->email,
            'valid' => $this->is_valid ? true : false,
            'exist' => $this->status == 2
        ];
    }
}
