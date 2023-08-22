<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Registro extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registros';

    protected $fillable = [
      'id',
      'path',
      'method',
      'data',
      'status',
      'user_id'
    ];

    protected $casts = [
      'data' => 'array'
    ];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function userAndRole() {
      return $this->user()->with('roles');
    }

    public function scopeUser(Builder $query, $id) {
      $query->where('user_id', $id);
    }

    public function scopeAction(Builder $query, $action) {
      $query->where('method', $action);
    }
}
