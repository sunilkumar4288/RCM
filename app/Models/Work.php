<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\MoneyCast;

class Work extends Model
{
    use HasFactory;

    protected $casts = [
        'total_amount' => MoneyCast::class,
    ];
}
