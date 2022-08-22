<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'rep_name',
        'url',
        'handle',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_medias';
}
