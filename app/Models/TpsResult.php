<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpsResult extends Model
{
    protected $table = 'tps_results';

    protected $fillable = ['name', 'dpt', 'invalid', 'user_id'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
