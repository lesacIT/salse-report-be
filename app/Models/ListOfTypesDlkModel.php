<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ListOfTypesDlkModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'list_of_types_dlk';
    protected $fillable = [
        'name'
    ];
}
