<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DailyTargetsModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //database
    use HasFactory;
    use SoftDeletes;
    protected $table = 'daily_targets';
    protected $fillable = [
     'date',
     'user_id',
     'crc_app',
     'crc_loan',
     'plxs_app',
     'plxs_loan',
     'amount_plxs',
     'amount_banca',
     'loan_ctbs',
     'convert_banca',
     'convert_ctbs',
    ];
    public function belong()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}
}
