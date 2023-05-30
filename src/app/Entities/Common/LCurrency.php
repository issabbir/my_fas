<?php


namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class LCurrency extends Model
{
    protected $table = 'l_currency';
    protected $primaryKey = 'currency_code';
    protected $keyType = 'string';
}
