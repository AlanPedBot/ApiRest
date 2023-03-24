<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';
    protected $fillable = [
        'client_id',
        'car_id',
        'period_start_date',
        'end_date_expected_period',
        'end_date_performed_period',
        'daily_value',
        'km_initial',
        'km_final'
    ];

    public function rules()
    {
        return [];
    }
}
