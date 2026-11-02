<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['tps_result_id', 'candidate_id', 'count'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function tpsResult()
    {
        return $this->belongsTo(TpsResult::class);
    }
}
