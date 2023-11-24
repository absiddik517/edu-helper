<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = null;
        if($this->image){
          $image = asset($this->image);
        }
        $date = $this->year;
        if($this->day && $this->month && $this->year){
          $date = date('d M, Y', strtotime($this->day.' '. $this->month.' '.$this->year));
        }
        return [
          'id' => $this->id,
          'year' => $this->year,
          'date' => $date,
          'event' => $this->highlightMatches($this->event),
          'detail' => Str::limit(html_entity_decode($this->detail), 100),
          'full_detail' =>$this->highlightMatches($this->detail),
          'catagory' => $this->name,
          'image' => $image,
          'user_name' => $this->user_name,
          'date' => $date,
        ];
    }
    
    private function highlightMatches($str){
      if(request()->year) {
        $converted = $this->toBn(request()->year);
        $str = str_replace(request()->year, "<span class='highlight'>".request()->year."</span>", $str);
        $str = str_replace($converted, "<span class='highlight'>".$converted."</span>", $str);
      }
      if(request()->search) {
        $str = str_replace(request()->search, "<span class='highlight'>".request()->search."</span>", $str);
      }
      return $str;
    }
    
    private function isBanglaNumber($number) {
        // Regular expression to match Bangla numbers
        $pattern = '/[০-৯]/';
        return preg_match($pattern, $number);
    }
    
    private function toBn($number) {
        $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        $englishNumbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    
        if ($this->isBanglaNumber($number)) {
            return str_replace($banglaNumbers, $englishNumbers, $number);
        } else {
            return str_replace($englishNumbers, $banglaNumbers, $number);
        }
    }



}
