<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'division_id',
        'level',
        'rep_name',
        'incumbent',
        'party',
        'phone',
        'address',
        'email',
        'image',
    ];
}
