<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'user_id'; // Primary key Anda 'user_id'
    public $timestamps = false; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $casts = [
        'role' => 'string',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- TAMBAHAN PENTING (ACCESSOR) ---
    // Ini membuat $user->id otomatis mengambil nilai dari $user->user_id
    public function getIdAttribute()
    {
        return $this->user_id;
    }
    // ------------------------------------

    public function imports()
    {
        return $this->hasMany(ImportMahasiswa::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}