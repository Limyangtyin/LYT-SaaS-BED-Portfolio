<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'advertising_start_date',
        'advertising_end_date',
        'position_title',
        'position_description',
        'position_keywords',
        'minimum_salary',
        'maximum_salary',
        'salary_currency',
        'company_id',
        'benefits',
        'requirements',
        'position_type'
    ];

}
