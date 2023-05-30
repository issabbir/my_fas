<?php
/**
 *Created by PhpStorm
 *Created at ২১/১১/২১ ১২:৪৭ PM
 */

namespace App\Entities\BudgetManagement;


use Illuminate\Database\Eloquent\Model;

class FasBudgetMgtDocs extends Model
{
    //protected $table = "cpaacc.fas_budget_mgt_docs";
    protected $table = "cpaacc.fas_budget_est_docs";
    protected $primaryKey = "doc_file_id";
}
