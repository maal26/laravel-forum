<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'favorited_type', 'favorited_id'];

    public function favorited()
    {
        return $this->morphTo();
    }
}
