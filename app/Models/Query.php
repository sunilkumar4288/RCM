<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Query extends Model
{
    use HasFactory;

    protected $casts = [
        'tags' => 'array',
        'quotation_sent' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->support_id =$user->id;
            }
        });
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function scholar(): BelongsTo
    {
        return $this->belongsTo(Scholar::class,'scholar_id');
    }

    public function support(): BelongsTo
    {
        return $this->belongsTo(User::class,'support_id');
    }
}
