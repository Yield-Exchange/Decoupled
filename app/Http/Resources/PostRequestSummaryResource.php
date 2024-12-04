<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\CustomEncoder;

class PostRequestSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this;
        // dd($this);
    //    return [
    //         'id'=>CustomEncoder::urlValueEncrypt($this['id'])
    //     ];
    }
}
