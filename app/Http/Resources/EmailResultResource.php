<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];

        foreach ($this->result as $key => $value) {
            array_push($data, [
                'email' => $key,
                'exist' => $value['exist'],
                'valid' => $value['valid']
            ]);
        }

        return [
            "device_id" => $this->device_id,
            "result" => $data
        ];
    }
}
