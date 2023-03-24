<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['modelCar_id', 'plate', 'available', 'km'];

    public function rules()
    {
        return [
            'modelCar_id' => 'exists:model_cars,id',
            'plate' => 'required',
            'available' => 'required',
            'km' => 'required'
        ];
    }
    public function modelCar()
    {
        // um modelo pertence a uma marca 
        return $this->belongsTo('App\Models\ModelCar');
    }
}
