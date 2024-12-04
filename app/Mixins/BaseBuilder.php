<?php
namespace App\Mixins;

class BaseBuilder{
    public function hasRows(){
        return function (){
            return $this->clone()->count();
        };
    }

    public function pluckUniqueArray(){
        return function ($column){
            return array_unique($this->pluck($column)->toArray());
        };
    }

    public function WhereIsNotDeleted(){
        return function (){
            return $this->whereNull('deleted_at');
        };
    }
}