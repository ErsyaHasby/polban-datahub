<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    // --- TAMBAHKAN BARIS INI ---
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
    // ---------------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the imports for this user.
     */
    public function imports()
    {
        return $this->hasMany(ImportMahasiswa::class, 'user_id');
    }

    /**
     * Get the mahasiswa that this user imported.
     */
    public function importedMahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'user_id_importer');
    }

    /**
     * Get the mahasiswa that this user approved.
     */
    public function approvedMahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'user_id_approver');
    }

    /**
     * Get the activity logs for this user.
     */
    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}