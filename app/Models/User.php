<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'plain_password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendefinisikan cast bawaan untuk atribut pengguna.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mengambil seluruh data absensi milik pengguna.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mengambil seluruh jurnal milik pengguna.
     */
    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    /**
     * Mengambil data nilai milik pengguna.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Mengambil data nilai akhir milik pengguna (1-to-1).
     */
    public function grade()
    {
        return $this->hasOne(Grade::class);
    }
}
