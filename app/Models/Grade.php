<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'final_score',
        'feedback',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
    ];

    /**
     * Relasi nilai ke pengguna pemilik data.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
