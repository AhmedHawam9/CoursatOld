<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'android_version' =>  1,//$this->android_version,
            'ios_version' =>  1,//$this->ios_version,
            "ios_status" =>  false,//$this->ios_status,
            "android_status" =>  false,//$this->android_status,
            "old_app_status" => 0,
            "new_app_status" => 1,

        ];
    }
}
