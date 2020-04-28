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
                'exist' => $value['status'] == 2,
                'valid' => $value['valid']
            ]);
        }

        // $data = [
        //     [
        //         'email' => 'mgmg@gmail.com',
        //         'exist' => true,
        //         'valid' => true
        //     ],
        //     [
        //         'email' => 'mgmg2@gmail.com',
        //         'exist' => false,
        //         'valid' => true
        //     ],
        //     [
        //         'email' => 'kyawkyaw@gmail.com',
        //         'exist' => true,
        //         'valid' => true
        //     ],
        //     [
        //         'email' => 'kyawkyaw2@gmail.com',
        //         'exist' => true,
        //         'valid' => true
        //     ]
        // ];

        return [
            'device_id' => $this->device_id,
            'result' => $data
        ];
    }
}
