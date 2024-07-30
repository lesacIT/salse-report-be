<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LinkPointListModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'link_point_list';
    protected $fillable = [
      'date',
      'user_id',
      'name_dlk',
      'local_province_id',
      'local_ward_id',
      'local_district_id',
      'address_dlk',
      'list_of_types_dlk_id',
      'full_name_of_representative',
       'list_of_items_dlk_id',
      'image',
      'locate',
      'status_dlk',
      'advise_crc',
      'eligible_crc',
      'go_to_app_crc',
      'loan_crc',
    ];
    public function belong()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function belongward(){
      return $this->belongsTo(LocalWardModel::class, 'local_ward_id', 'id');
    }

}
