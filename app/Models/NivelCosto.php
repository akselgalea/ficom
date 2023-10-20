<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NivelCosto extends Model
{
    use HasFactory;

    protected $table = 'nivel_costo';

    protected $fillable = [
        'matricula',
        'arancel',
        'periodo',
        'nivel_id'
    ];
}
