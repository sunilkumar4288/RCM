<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use \Auth;
class Scholar extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sid = IdGenerator::generate(['table' => 'scholars', 'field' => 'sid', 'length' => 10, 'prefix' => 'S' . date('ym') . '-']);
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->support_id =$user->id;
            }
        });
    }
    
}
