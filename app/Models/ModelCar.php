<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCar extends Model
{
    use HasFactory;
    protected $fillable = ['brand_id', 'name', 'image', 'number_of_doors', 'places', 'air_bag', 'abs'];

    public function rules()
    {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => 'required|unique:model_cars,name, ' . $this->id . '|min:3',
            'image' => 'required|file|mimes:png,jpeg,jpg',
            'number_of_doors' => 'required|integer|digits_between:1,5',
            'places' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }
    public function brand()
    {
        // um modelo pertence a uma marca 
        return $this->belongsTo('App\Models\Brand');
    }
}
