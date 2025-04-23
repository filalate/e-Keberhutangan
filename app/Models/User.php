<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Commented out as it's not needed for superadmin
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'negeri',
        'role',
        'verified',
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
     * Check if the user is a superadmin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin'; // Check if the user has a 'superadmin' role
    }

    /**
     * Skip email verification for superadmins.
     *
     * @return string|null
     */
    public function getEmailForVerification()
    {
        // Skip verification for superadmin
        if ($this->isSuperAdmin()) {
            return null; // Prevent email verification for superadmin
        }

        return $this->email; // Proceed with the default email verification for others
    }

    /**
     * Relationship with Penyata Gaji.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function penyataGaji()
    {
        return $this->hasMany(PenyataGaji::class, 'user_id');
    }
}
