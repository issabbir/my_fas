<style rel="stylesheet">
    .child-table >td{
       border: none !important;
    }
</style>
<section id="modal-sizes">
    <div class="row">
        <div class="col-12">
            <div class="mr-1 mb-1 d-inline-block">
                <div class="modal fade text-left w-100" id="chalanModal"  role="dialog"
                     aria-labelledby="chalanModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="chalanModalLabel">FDR Chalan Preview</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x font-size-small"></i></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">By Whom Brought</th>
                                            <th class="text-center">By Whom Paid In</th>
                                            <th>On What Account</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><span class="c_who_brought"></span></td>
                                            <td class="text-center"><span class="c_whom_paid"></span></td>
                                            <td>
                                                <span>FDR NO : <span class="c_fdr_no"></span></span>
                                                <br>
                                                <span>Date : <span class="c_fdr_date"></span></span>
                                                <hr>
                                                <span>Pay Order NO : <span class="c_pay_order_no"></span></span>
                                                <br>
                                                <span>Date : <span class="c_pay_order_date"></span></span>
                                            </td>
                                            <td class="text-right"><span class="c_amount"></span>/=</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-light-secondary" data-dismiss="modal"><i
                                        class="bx bx-x d-block d-sm-none font-size-small"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
