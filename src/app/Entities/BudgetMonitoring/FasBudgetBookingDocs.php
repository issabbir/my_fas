<?php


namespace App\Entities\BudgetMonitoring;


use Illuminate\Database\Eloquent\Model;

class FasBudgetBookingDocs extends Model
{
    protected $table = "fas_budget_booking_docs";
    protected $primaryKey = "doc_file_id";
}
