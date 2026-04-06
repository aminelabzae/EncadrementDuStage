<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'uuid',
        'nom',
        'prenom',
        'email',
        'telephone',
        'mot_de_passe',
        'etat',
        'derniere_connexion_at',
        'photo_url',
        'password',
    ];

    /**
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mot_de_passe',
    ];

    /**
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'derniere_connexion_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function stagiaire()
    {
        return $this->hasOne(Stagiaire::class, 'utilisateur_id');
    }

    public function encadrant()
    {
        return $this->hasOne(Encadrant::class, 'utilisateur_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_utilisateur', 'utilisateur_id', 'role_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'utilisateur_id');
    }

    public function journalActivites()
    {
        return $this->hasMany(JournalActivite::class, 'utilisateur_id');
    }

    public function piecesJointes()
    {
        return $this->hasMany(PieceJointe::class, 'uploaded_by');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('code', $role)->exists();
    }

    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('code', $permission)->exists()) {
                return true;
            }
        }
        return false;
    }
}

