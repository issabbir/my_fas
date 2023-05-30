<fieldset class="border p-1 mt-2 col-md-12">
    <legend class="w-auto text-bold-600" style="font-size: 14px;">Workflow Status</legend>
    <div class="text-center center-block">
        <ul id="progressbar" class="text-center mt-1 mb-1">
            @php
                $i = 1;
            @endphp
            @foreach($workflows as $key=>$workflow)
                <li class="{{ isset($workflow->template_wise_map) && $workflow->template_wise_map->reference_status == 'P' ? 'active' : ''}} @if( $workflow->template_wise_map && $workflow->template_wise_map->reference_status == 'A') done
                    @elseif( $workflow->template_wise_map && $workflow->template_wise_map->reference_status == 'R') reject
                    @endif" id="step{{$i}}">
                    <div class="d-none d-md-block">
                        @if($workflow->template_wise_map && $workflow->template_wise_map->reference_status == 'P')
                            {{ucwords(strtolower($workflow->step_role_key_display))}}<br/>
                            <span class="badge badge-primary badge-pill">Pending</span>
                        @elseif($workflow->template_wise_map && $workflow->template_wise_map->reference_status == 'A')
                            {{isset($workflow->template_wise_map) ? (isset($workflow->template_wise_map->emp_id) ? $workflow->template_wise_map->emp_info->emp_name . ' ('.$workflow->template_wise_map->emp_info->emp_code.')' : '---') : ''}} @if (isset($workflow->template_wise_map)) <br/> @endif
                            {{isset($workflow->template_wise_map) ? date('d-m-Y', strtotime($workflow->template_wise_map->insert_date)): ''}} @if (isset($workflow->template_wise_map)) <br/> @endif
                            <span class="badge badge-success badge-pill">Approved</span>
                        @elseif($workflow->template_wise_map && $workflow->template_wise_map->reference_status =='R')
                            {{--<span class="badge badge-danger badge-pill">Rejected</span>--}}
                            <a class="cursor-pointer" data-toggle="modal" data-target="#myModal{{$workflow->template_wise_map->workflow_mapping_id}}" href="javascript:void(0)"><span class="badge badge-danger badge-pill font-small-4">Rejected || <i class="bx bx-show font-small-3 align-middle"></i> View</span></a>
                        @elseif ( empty ($workflow->template_wise_map) )
                            {{ucwords(strtolower($workflow->step_role_key_display))}}<br/>
                            {{--<span class="badge badge-dark badge-pill">Not Received</span>--}}
                        @endif
                        <br/>
                    </div>
                </li>
                @php $i++; @endphp

            <!-- Modal -->
                <div id="myModal{{isset($workflow->template_wise_map) ? $workflow->template_wise_map->workflow_mapping_id : ''}}" class="modal fade"
                     role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Note</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p class="font-weight-bolder">{{isset($workflow->template_wise_map) ? $workflow->template_wise_map->reference_comment : ''}}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <li class="{{(isset($approval_status->workflow_approval_status) && $approval_status->workflow_approval_status == 'A') ? 'active done' : ''}}" id="step{{$i}}"> <div class="d-none d-md-block">END</div></li>
        </ul>
    </div>
</fieldset>
