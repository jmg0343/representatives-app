<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contest_id',
        'party',
        'phone',
        'email',
        'candidate_url',
    ];

    /**
     * Get the election that owns the candidate.
     */
    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

}
