<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image'];

    public function rules()
    {
        return [
            'name' => 'required|unique:brands,name, ' . $this->id . '|min:3',
            'image' => 'required|file|mimes:png'
        ];
    }
    public function feedback()
    {
        return  [
            'required' => 'O campo :attribute é obrigatório',
            'name.unique' => 'O nome da marca já existe',
            'name.min' => 'O nome deve ter no minimo 3 caracteres',
            'image.mimes' => 'A imagem deve ser um arquivo do tipo .png'
        ];
    }
    public function modelCar()
    {
        //Uma marca possui muitos modelos
        return $this->hasMany('App\Models\ModelCar');
    }
}
