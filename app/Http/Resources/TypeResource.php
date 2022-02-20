<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SubtypeResource;
use App\Type_Rate;
use App\Http\Resources\TagResource;
class TypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       
       
         $rates = Type_Rate::where('type_id',$this->id)->get()->pluck('rate')->toArray();
         if(count($rates) > 0){
         $rate = array_sum($rates) / count($rates);}
         else{
             $rate = 0;
         } if(auth()->user()->stutypes()->pluck('type_id')->contains($this->id)){
           $is_book = 1;
           $allow = 1;
       }else{
          $is_book = 0;
           $allow = 0; 
       }
       if($this->center){
           $center_image = asset('uploads/'.$this->center['image']);
       }else{
           $center_image = asset('uploads/'.$this->user['image']);
       }if($this->center){
           $center_name = $this->center['name'];
       }else{
          $center_name = $this->user['name']; 
       }
       $status = 0;
         $duration = array_sum($this->videos->pluck('seconds')->toArray());
           return [
           'id'  => $this->id,
           'image' => $this->image ? asset('uploads/'.$this->image) : null,
           'intro' => $this->intro ? asset('uploads/'.$this->intro) : null,
           'lecturer' => $this->user['name'],
           'lecturer_id' => $this->user_id,
           'points' => $this->points,
           'description' => $this->description ? $this->description : '',
           'posted_by' => $center_name,
           'posted_by_image' =>$center_image, 
              'mintues' => $duration > 0 ? intval($duration / 60) : 0,
           'name' => $this->name_ar,
           'subject' => $this->subject['name_ar'],
             'lecturer_image' => $this->user ? asset('uploads/'.$this->user['image']) : null,
      //     'classes' => SubtypeResource::collection($this->subtypes),
            'is_book' => $is_book,
           'allow' => $allow,
           'rate'=> $rate,
           'category_id' => 1,
           "status" => $status,
          'tags' => TagResource::collection($this->tags)
            
        ];
    }
}
