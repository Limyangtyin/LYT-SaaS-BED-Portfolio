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
        'benefits',
        'requirements',
        'position_type',
        'user_id',
        'company_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

}
