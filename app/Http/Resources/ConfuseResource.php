<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfuseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      return [
        'id' => $this->id,
        'name' => $this->highlightMatches($this->name),
        'catagory' => $this->catagory,
        'detail' => $this->highlightMatches($this->detail),
      ];
    }
    
    private function highlightMatches($str){
      if(request()->search) {
        $str = str_replace(request()->search, "<span class='highlight'>".request()->search."</span>", $str);
      }
      return $str;
    }
}
