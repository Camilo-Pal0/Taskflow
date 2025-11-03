<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function members(){
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function isMember($userId){
        return $this->members()->where('user_id', $userId)->exists()
               || $this->user_id === $userId;
    }

    public function isOwner($userId)
    {
        // Es owner si es el creador O tiene rol 'owner' en la tabla pivot
        return $this->user_id === $userId || 
            $this->members()->where('user_id', $userId)->wherePivot('role', 'owner')->exists();
    }

    protected static function booted()
    {
        static::created(function ($project){
            $project->members()->attach($project->user_id, ['role' => 'owner']);
        });
    }
}
