<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * @var array<int, string>
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

    public function user_office()
    {
        return $this->belongsTo(User_Offices_Model::class, 'id', 'user_id');
    }

    public function ref_office()
    {
        return $this->hasOneThrough(
            Ref_Offices_Model::class,
            User_Offices_Model::class,
            'user_id', // Foreign key on User_Offices_Model table...
            'id', // Foreign key on Ref_Offices_Model table...
            'id', // Local key on User table...
            'office_id' // Local key on User_Offices_Model table...
        )->withDefault([
            'office_name' => 'No Office Assigned', // Default value when no office is associated
        ]);
    }
}
