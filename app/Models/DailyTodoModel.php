<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DailyTodoModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
    //database
    protected $table = 'daily_todo';
    protected $fillable = ['user_id','date'];
    public function have()
    {
        return $this->hasMany(DailyTodoDetailsModel::class, 'daily_todo_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
