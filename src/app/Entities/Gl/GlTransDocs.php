<?php
/**
 *Created by PhpStorm
 *Created at ২০/৬/২১ ১০:৫৩ AM
 */

namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class GlTransDocs   extends Model
{
    protected $table = "fas_gl_trans_docs";
    protected $primaryKey = "trans_doc_file_id";
}
