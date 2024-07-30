<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DailyReportsModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'daily_reports';

    protected $fillable = [
        'date',
        'user_id',
        'period_time',
        'app_crc',
        'loan_crc',
        'app_plxs',
        'loan_plxs',
        'amount_plxs',
        'amount_banca',
        'loan_ctbs',
        'conver_banca',
        'conver_ctbs',
    ];
    public function belong()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
