<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ListOfItemsDlkModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'list_of_items_dlk';
    protected $fillable = [
        'name'
    ];
}
