<?php

namespace App\Http\Controllers\BudgetManagement;

use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Enums\FileTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Security\HasPermission;

class DownloaderController extends Controller
{
    use HasPermission;

    public function downloadBudgetMgtDocs(Request $request, $docFileId)
    {
        //dd($docFileId);
        $budgetMgtDocs = FasBudgetMgtDocs::where('doc_file_id', $docFileId)->first();

        if($budgetMgtDocs) {
            if($budgetMgtDocs->doc_file_content && $budgetMgtDocs->doc_file_type && $budgetMgtDocs->doc_file_name) {

               /* switch ($param){
                    case 1:
                        //TODO::GL, $content, $fileType, $fileName
                        break;
                    case
                }*/


                $content = base64_decode($budgetMgtDocs->doc_file_content);

                return response()->make($content, 200, [
                    'Content-Type' => $budgetMgtDocs->doc_file_type,
                    'Content-Disposition' => 'attachment; filename="'.$budgetMgtDocs->doc_file_name.'"'
                ]);
            }
        }
    }
}
