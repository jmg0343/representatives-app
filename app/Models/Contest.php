<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'election_id',
        'data_available',
        'type',
        'primary_party',
        'office',
        'level',
        'roles',
        'district_name',
        'district_scope',
        'district_id',
        'number_elected',
        'number_voting_for',
        'ballot_placement',
    ];

    /**
     * Get the election that owns the contest.
     */
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    /**
     * Get the candidates for the contest.
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
