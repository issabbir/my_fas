<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:27 PM
 */
namespace App\Contracts\Common;


interface CommonContract
{
    public function commonRadioLookupsList($parameterArray = array(),$columnSelected = null,$condition=null,$returnFormat=null);
    public function commonDropDownLookupsList($parameterArray = array(),$columnSelected = null,$condition=null,$returnFormat=null);
    public function commonDropDownLookupsPkgList($look_up_name = null,$column_selected = null,$fetch_single_colid = null);
    public function loadStaticDecisionRadio($parameterArray = array(),$columnSelected = null,$condition=null,$returnFormat=null);
    public function loadDecisionDropdown($column_selected = null);
    public function findInsertedData($parameterArray,$multipleRow=null);
    public function findInsertedPkgData($pkgFunction,$primaryId = null,$multipleRow=null);
}
