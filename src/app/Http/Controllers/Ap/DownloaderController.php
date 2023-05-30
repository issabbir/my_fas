<?php

namespace App\Http\Controllers\Ap;

use App\Entities\Ap\FasApPaymentDocs;
use App\Enums\FileTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Security\HasPermission;

class DownloaderController extends Controller
{
    use HasPermission;

    public function invoiceBillPayAttachment(Request $request, $docFileId)
    {
        $invoiceBillPayAttachment = FasApPaymentDocs::where('doc_file_id', $docFileId)->first();

        if($invoiceBillPayAttachment) {
            if($invoiceBillPayAttachment->doc_file_content && $invoiceBillPayAttachment->doc_file_type && $invoiceBillPayAttachment->doc_file_name) {
                $content = base64_decode($invoiceBillPayAttachment->doc_file_content);

                return response()->make($content, 200, [
                    'Content-Type' => $invoiceBillPayAttachment->doc_file_type,
                    'Content-Disposition' => 'attachment; filename="'.$invoiceBillPayAttachment->doc_file_name.'"'
                ]);
            }
        }
    }

}
