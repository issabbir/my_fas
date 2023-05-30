<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:30 PM
 */
namespace App\Managers\Common;
use App\Contracts\Common\CommonContract;
use App\Entities\Common\LApVendorType;
use App\Enums\WorkFlowMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CommonManager implements CommonContract
{
   // fetch data from raw query
    public function commonDropDownLookupsList($parameterArray = array(),$columnSelected = null,$condition=null,$oldVal=null,$returnFormat=null){
        //commonDropDownLookupsList(array('v_division','division_id','division_name'),2,'where division_id = 2')
        if(count($parameterArray)>2) {
            $tableName = $parameterArray[0];
            $pass_value = $parameterArray[1];
            $show_value = $parameterArray[2];
        }
        $entityOption = [];
        $query = '';

        if((isset($condition)== true)&& count($parameterArray)>2){
            $query = "Select ".$pass_value." as pass_value,".$show_value." as show_value from ".$tableName." ".$condition." ";
            $querySetFlag = true;
        }else if(count($parameterArray)>2){
            $query = "Select ".$pass_value." as pass_value,".$show_value." as show_value from ".$tableName." ";
            $querySetFlag = true;
        }else{
            $querySetFlag = false;
        }
        $entityOption[] = "<option value=''>Please select an option</option>";
        if($querySetFlag){
            $entityList = DB::select($query);
            foreach ($entityList as $item) {

                if ($oldVal){
                    $entityOption[] = "<option value='".$item->pass_value."' ".($oldVal == $item->pass_value ? 'selected ':'').">".$item->show_value."</option>";
                }else{
                    $entityOption[] = "<option value='".$item->pass_value."' ".($columnSelected == $item->pass_value ? 'selected ':'').">".$item->show_value."</option>";
                }
            }
        }

        if($returnFormat=='json'){
            return response()->json($entityOption);
        }
        return $entityOption; //default array return format;
    }
    public function commonRadioLookupsList($parameterArray = array(),$columnSelected = null,$condition=null,$returnFormat=null){
        //commonRadioLookupsList(array('v_division','division_id','division_name','division_id','&nbsp;&nbsp;'),5)
        if(count($parameterArray)>3) {
            $tableName      = $parameterArray[0];
            $pass_value     = $parameterArray[1];
            $show_value     = $parameterArray[2];
            $nameAttribute  = $parameterArray[3];
            $htmlRadioSeparator = isset($parameterArray[4])? " ".$parameterArray[4]." ":'';
            $classAttribute = isset($parameterArray[5])? " ".$parameterArray[5]." ":'';
        }
        $entityOption = [];
        $query = '';

        if((isset($condition)== true)&& count($parameterArray)>3){
            $query = "Select ".$pass_value." as pass_value,".$show_value." as show_value from ".$tableName." ".$condition." ";
            $querySetFlag = true;
        }else if(count($parameterArray)>3){
            $query = "Select ".$pass_value." as pass_value,".$show_value." as show_value from ".$tableName." ";
            $querySetFlag = true;
        }else{
            $querySetFlag = false;
        }

        if($querySetFlag){
            $entityList = DB::select($query);
            foreach ($entityList as $item) {
                $entityOption[] = "<input class='form-check-input".$classAttribute."' id='".$nameAttribute.$item->pass_value."' type='radio' name='".$nameAttribute."' value='".$item->pass_value."' ".($columnSelected == $item->pass_value ? 'checked ':'')." /> <label class='form-check-label' for='reporter_cpa_yes'>".$item->show_value."</label> ".$htmlRadioSeparator." ";
            }
        }

        if($returnFormat=='json'){
            return response()->json($entityOption);
        }
        return $entityOption; //default array return format;
    }
    public function loadStaticDecisionRadio($parameterArray = array(),$columnSelected = null,$condition=null,$returnFormat=null){

        $skip      = -1;
        $nameAttribute      = '';
        $staticData         = '';
        $htmlRadioSeparator = '&nbsp;&nbsp;';
        $classAttribute     = '';
        if(count($parameterArray)==0 || count($parameterArray)<0){
            $nameAttribute = 'default';
            $classAttribute= 'default';
            $parameterArray = array(
                array('Y','Active'),
                array('N','Inactive'),
            );
        }elseif(count($parameterArray)>0 && count($parameterArray)<2) {
            $nameAttribute = $parameterArray[0];
            $classAttribute = $parameterArray[0];
            $default = array(
                array('Y','Active'),
                array('N','Inactive'),
            );
            $parameterArray = $default; // $parameterArray = when only name defined as like name='subject'
            //$parameterArray = array_merge($nameAttribute,$default); // $parameterArray = when only name defined as like name='subject'
        }else{
            $nameAttribute      = isset($parameterArray[0][0])? $parameterArray[0][0]:'default';
            $classAttribute     = isset($parameterArray[0][0])? $parameterArray[0][0]:'default';
            $skip =0;
            /*$pass_value         = isset($parameterArray[1][0])? $parameterArray[1]:'';
            $show_value         = isset($parameterArray[2])? $parameterArray[2]:'';
            $staticData         = isset($parameterArray[3])? $parameterArray[3]:'';
            $htmlRadioSeparator = isset($parameterArray[4])? " ".$parameterArray[4]." ":'';
            $classAttribute     = isset($parameterArray[5])? " ".$parameterArray[5]." ":'';*/
        }
       /* echo '<pre>';
        print($nameAttribute);
        print_r($parameterArray); die();*/

            $entityOption = [];
            foreach ($parameterArray as $key => $item) {
                if($key == $skip){
                   continue;
                }else{
                    $pass_value     = $item[0];
                    $show_value     = $item[1];
                    $entityOption[] = "<input class='form-check-input ".$classAttribute." ' id='".$nameAttribute.$pass_value."' type='radio' name='".$nameAttribute."' value='".$pass_value."' ".($columnSelected == $pass_value ? 'checked ':'')." /> <label class='form-check-label' for='".$nameAttribute.$pass_value."'>".$show_value."</label> ".$htmlRadioSeparator." ";
                }

            }

        return $entityOption;
    }
    public function loadDecisionDropdown($parameterArray = array(),$column_selected = null){

        if(count($parameterArray)==0) {
            $parameterArray = array(
                array('Y','Yes'),
                array('N','No'),
            );
        }

        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        if(count($parameterArray)>1){
            foreach ($parameterArray as $item) {
              $pass_value = isset($item[0]) ? $item[0] : '';
              $show_value = isset($item[1]) ? $item[1] : '';
              $entityOption[] = "<option value='" . $pass_value . "'" . ($column_selected == $pass_value ? ' selected ' : '') . ">" . $show_value . "</option>";
            }
        }
        return $entityOption;
    }
    public function findInsertedData($parameterArray = array(),$multipleRow=null){
        if(count($parameterArray)>1) {
            $tableName   = $parameterArray[0];
            $columnsName = isset($parameterArray[1])?$parameterArray[1]:'*';
            $condition   = isset($parameterArray[2])?$parameterArray[2]:'';
        }

        $querys = '';
        if((isset($condition)== true)&& count($parameterArray)>2){
            $querys = "Select ".$columnsName." from ".$tableName." ".$condition." ";
            $querySetFlag = true;
        }else if(count($parameterArray)>=1 && count($parameterArray)<=2){
            $querys = "Select ".$columnsName." from ".$tableName." ";
            $querySetFlag = true;
        }else{
            $querySetFlag = false;
        }

		if($querySetFlag){
            $entityList = DB::select($querys);
            if(isset($parameterArray) && (!isset($multipleRow) || ($multipleRow =='N'))){
                $array1d = $entityList[0];
                return $array1d;
            }
        }
        return $entityList;
    }

    // fetch data from pkg function
    public function commonDropDownLookupsPkgList($look_up_name = null,$column_selected = null,$fetch_single_colid = null){
        if($fetch_single_colid){
            $query = "Select ".$look_up_name."('".$fetch_single_colid."') from dual" ;
        }else{
            $query = "Select ".$look_up_name."() from dual" ;
        }
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->pass_value."'".($column_selected == $item->pass_value ? 'selected':'').">".$item->show_value."</option>";
        }
        return $entityOption;
        //return response()->json($entityOption);
    }
    public function findInsertedPkgData($pkgFunction,$primaryId = null,$multipleRow=null){
        if($primaryId){
            $querys = "SELECT ".$pkgFunction."('".$primaryId."') from dual" ;
        }else{
            $querys = "Select ".$pkgFunction."() from dual" ;
        }
        $entityList = DB::select($querys);
        if(isset($primaryId) && !isset($multipleRow)){
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

    //TODO:status dropdown
    public function loadStatusDropdown($parameterArray = array(),$column_selected = null){

        if(count($parameterArray)==0) {
            $parameterArray = array(
                array('Y','Active'),
                array('N','In-active'),
            );
        }

        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        if(count($parameterArray)>1){
            foreach ($parameterArray as $item) {
                $pass_value = isset($item[0]) ? $item[0] : '';
                $show_value = isset($item[1]) ? $item[1] : '';
                $entityOption[] = "<option value='" . $pass_value . "'" . ($column_selected == $pass_value ? ' selected ' : '') . ">" . $show_value . "</option>";
            }
        }
        return $entityOption;
    }

    public function base64Download($fileName,$fileContent)
    {
        $decoded = base64_decode($fileContent);
        $file = $fileName;
        file_put_contents($file, $decoded);

        if (file_exists($file)) {
            header('Content-Description: File Download');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    /*** Add this one method start -Pavel: 07-12-22 **
     * @param $moduleID
     * @return mixed
     */
    public function findWorkFlowUser( $moduleID=null )
    {
        $workFlowUser = '';
        $query = <<<QUERY
SELECT DISTINCT SU.USER_NAME, SU.USER_ID, EMP.EMP_NAME
    FROM CPAACC.WORKFLOW_TEMPLATE   WT,
         CPA_SECURITY.SEC_ROLE      SECR,
         CPA_SECURITY.SEC_USER_ROLES SECUR,
         CPA_SECURITY.SEC_USERS     SU,
         PMIS.EMPLOYEE              EMP
   WHERE     WT.STEP_ROLE_KEY = SECR.ROLE_KEY
         AND SECUR.ROLE_ID = SECR.ROLE_ID
         AND SU.USER_ID = SECUR.USER_ID
         AND EMP.EMP_ID (+) = SU.EMP_ID
         AND (WT.INTEGRATION_MODULE_ID = :p_module_id OR :p_module_id IS NULL)
ORDER BY EMP.EMP_NAME ASC
QUERY;

        $workFlowUser = DB::select($query, ['p_module_id' => $moduleID]);
        return $workFlowUser;
    }
    /*** Add this two method end -Pavel: 07-12-22 ***/

}
