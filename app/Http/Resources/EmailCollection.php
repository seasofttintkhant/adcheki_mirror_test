<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmailCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'device_id' => $request->device_id,
            'created_at' => $this->collection[0]->device->updated_at->format('Y-m-d H:i:s'),
            'in_process' => !!$this->collection[0]->device->is_checked,
            'result' => EmailResource::collection($this->collection)
        ];
    }
}
