<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'code',
        'libelle',
        'description'
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(User::class, 'role_utilisateur', 'role_id', 'utilisateur_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
}