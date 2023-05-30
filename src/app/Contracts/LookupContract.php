<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 1/28/20
 * Time: 3:46 PM
 */

namespace App\Contracts;


interface LookupContract
{
    /**
     * @return LGeoDivision[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findDivisions();

    /**
     * @param null $divisionId
     * @return LGeoDistrict[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findDistrictsByDivision($divisionId = null);

    /**
     * @param $districtId
     * @return LGeoThana[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findThanasByDistrict($districtId);

    /**
     * @param $bankId
     * @return LBranch[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findBranchesByBank($bankId);

    public function getDeptCostCenter();

    public function getSpecifiedDeptCostCenter($deptArray);

    public function getBillSections($funcId);

    public function getBillSectionOnInvType($typeId);

    public function getBillRegisterOnInvType($typeId,$join);

    public function getBillRegistersOnSection($secId, $searchTerm);

    public function getOldPeriods($yearId, $sortOrder);

    public function getBillRegisterOnFunction($functionId);

    public function getCurrentFinancialYear();

    public function getPmisBills();

    public function getBudgetTypes();

    public function getCategoriesOnBudgetType($id);

    public function getSubCategoriesOnCategory($id);

    public function getDeptClusters();

    public function getInvestmentPeriodTypes();

    public function getFdrInvestmentStatus();

    public function getLFdrInvestmentType();

    public function getMaturityTransTypes();
}
