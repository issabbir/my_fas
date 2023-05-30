<?php

namespace App\Http\Controllers\BudgetMonitoring;

use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Entities\BudgetMonitoring\FasBudgetBookingDocs;
use App\Enums\FileTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Security\HasPermission;

class DownloaderController extends Controller
{
    use HasPermission;

    public function downloadBudgetMonDocs(Request $request, $docFileId)
    {
        $budgetMonDocs = FasBudgetBookingDocs::where('doc_file_id', $docFileId)->first();

        if ($budgetMonDocs) {
            if ($budgetMonDocs->doc_file_content && $budgetMonDocs->doc_file_type && $budgetMonDocs->doc_file_name) {

                /* switch ($param){
                     case 1:
                         //TODO::GL, $content, $fileType, $fileName
                         break;
                     case
                 }*/


                $content = base64_decode($budgetMonDocs->doc_file_content);

                return response()->make($content, 200, [
                    'Content-Type' => $budgetMonDocs->doc_file_type,
                    'Content-Disposition' => 'attachment; filename="' . $budgetMonDocs->doc_file_name . '"'
                ]);
            }
        }
    }
}
