<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commentaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'commentaires';

    protected $fillable = [
        'utilisateur_id',
        'objet_type',
        'objet_id',
        'contenu'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function objet()
    {
        return $this->morphTo();
    }
}