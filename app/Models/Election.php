<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'election_id',
        'name',
        'date',
        'ocd_id',
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
        'election_info_url',
        'election_registration_confirmation_url',
        'absentee_voting_info_url',
        'voting_location_finder_url',
    ];

    /**
     * Get the candidates for the election.
     */
    public function contests()
    {
        return $this->hasMany(Contest::class);
    }

    /**
     * Get the polling locations for the election.
     */
    public function pollingLocations()
    {
        return $this->hasMany(PollingLocation::class);
    }
}
