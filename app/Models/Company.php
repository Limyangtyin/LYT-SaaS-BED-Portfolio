<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'state',
        'country',
        'logo',
        'user_id',
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
