<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 5/31/2020
 * Time: 2:30 PM
 */

namespace App\Managers\Gl;

use App\Contracts\Gl\GlContract;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Gl\GlTransMaster;
use App\Enums\WorkFlowMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class GlManager implements GlContract
{

    public function base64Download($fileName, $fileContent)
    {
        $decoded = base64_decode($fileContent);
        $file = $fileName;
        file_put_contents($file, $decoded);

        if (file_exists($file)) {
            header('Content-Description: File Download');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public function findLastGlTranMst($parentId, $userID)
    {
        $lastGlTranMst = '';
        $query = <<<QUERY
SELECT TRANS_BATCH_ID
  FROM CPAACC.FAS_GL_TRANS_MASTER
 WHERE TRANS_MASTER_ID = (SELECT MAX (FGTM.TRANS_MASTER_ID) FROM CPAACC.L_GL_INTEGRATION_FUNCTIONS LGIF, CPAACC.FAS_GL_TRANS_MASTER FGTM
                         WHERE LGIF.FUNCTION_PARENT_ID = :parent_id AND LGIF.FUNCTION_ID = FGTM.FUNCTION_ID AND FGTM.INSERT_BY = :user_id)
QUERY;

        $lastGlTranMst = DB::selectOne($query, ['parent_id' => $parentId, 'user_id' => $userID]);
        //dd($lastGlTranMst);
        return $lastGlTranMst;
    }
}
