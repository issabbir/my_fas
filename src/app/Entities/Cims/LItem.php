<?php


namespace App\Entities\Cims;


use Illuminate\Database\Eloquent\Model;

class LItem extends Model
{
    protected $table = "CIMS.L_ITEM";
    protected $primaryKey = "item_id";
}
