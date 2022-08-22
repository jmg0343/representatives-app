<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingLocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'election_id',
        'type',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'notes',
        'hours',
        'services',
        'start_date',
        'end_date',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'polling_locations';

    /**
     * Get the election that owns the polling location.
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}
