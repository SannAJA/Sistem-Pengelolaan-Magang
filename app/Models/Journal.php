<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'work_summary',
        'task_photo_path',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relasi jurnal ke pengguna pemilik data.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
