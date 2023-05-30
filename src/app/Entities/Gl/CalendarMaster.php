<?php


namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class CalendarMaster extends Model
{
    protected $table = 'fas_calendar_master';
    protected $primaryKey = 'calendar_id';

    public function fiscal_period()
    {
        return $this->belongsTo(LFiscalPeriod::class, 'fiscal_period_id','fiscal_period_id');
    }

    public function period_type()
    {
        return $this->belongsTo(LPeriodType::class,'posting_period_code','period_type_code');
    }
}
