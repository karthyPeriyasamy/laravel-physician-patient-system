<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Specialists;

class Appointments extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'user_id', 'specialist_id', 'description', 'status'
    ];

    /**
     * Get the user record associated with the user.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
    /**
    * Get the specialist record associated with the user.
    */
    public function specialist()
    {
        return $this->hasOne(Specialists::class);
    }
}
