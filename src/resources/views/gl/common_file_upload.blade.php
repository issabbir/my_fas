<?php
/**
 *Created by PhpStorm
 *Created at ১৭/৬/২১ ৫:২৩ PM
 */
?>
{{--<div class="row mt-1">--}}
    <fieldset class="{{--col-md-12--}} border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">Attachments</legend>
        <div class="row">
            <div class="col-md-12">
                @isset($data['insertedData'])
                    @forelse($data['insertedData']->attachments as $index=>$attachment)
                        @if ($index == 0)
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-dark"
                                        data-initialrow="{{count($data['insertedData']->attachments)}}"
                                        style="margin-right: 2.5rem!important;" onclick="addAttachmentRow()"
                                        id="addAttachmentLine" data-row="1"><i
                                        class="bx bx-add-to-queue">Add File</i></button>
                            </div>

                        @endif
                        <div class="row rowCounter dynamicRows mt-1">
                            <div class="col-md-5">
                                <div class="custom-file b-form-file form-group">
                                    <input type="hidden" name="attachment[{{$index}}][actionType]"
                                           id="actionType{{$index}}" value="U">
                                    <input type="hidden" name="attachment[{{$index}}][docFileId]"
                                           id="docFileId{{$index}}" value="{{$attachment->doc_file_id}}">
                                    <input type="text" readonly placeholder="" id="attachment{{$index}}" value=""
                                           name="attachment[{{$index}}][file]"
                                           class="custom-file-input file-validation-rules"/>
                                    <label for="attachment{{$index}}" data-browse="Attach File"
                                           class="custom-file-label">{{$attachment->doc_file_name}}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input readonly maxlength="100" class="form-control attachmentDescription" type="text"
                                               name="attachment[{{$index}}][description]"
                                               value="{{$attachment->doc_file_desc}}"
                                               placeholder="Attachment Description"/>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{route("budget-mgt-download.download-budget-mgt-attachment",["id"=>$attachment->doc_file_id])}}"
                                           type="button" class="btn btn-info btn-sm mr-1 mb-1"
                                           id="downloadAttachment" data-row="1"><i
                                                class="bx bx-download"></i></a>

                                        <button type="button" class="btn btn-danger btn-sm mr-1 mb-1"
                                                onclick="removeAttachmentRow(this,{{$index}})"
                                                id="removeAttachmentBtn"
                                                data-row="1"><i
                                                class="bx bx-trash"></i></button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="row rowCounter">
                            <div class="col-md-5">
                                <div class="custom-file b-form-file form-group">
                                    <input type="hidden" name="attachment[0][actionType]" id="actionType0" value="I">
                                    <input type="hidden" name="attachment[0][docFileId]" id="docFileId0" value="">
                                    <input type="file" placeholder="" id="attachment0"
                                           accept="image/*,.pdf,.doc,.docx,application/msword,"
                                           name="attachment[0][file]"
                                           class="custom-file-input file-validation-rules"/>
                                    <label for="attachment0" data-browse="Attach File"
                                           class="custom-file-label">Upload Attachment</label>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-11">
                                        <input maxlength="100" class="form-control attachmentDescription" type="text"
                                               name="attachment[0][description]"
                                               placeholder="Attachment Description"/>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-dark btn-sm mr-1 mb-1"
                                                data-initialrow="0"
                                                onclick="addAttachmentRow()"
                                                id="addAttachmentLine" data-row="1"><i
                                                class="bx bx-add-to-queue"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                @else
                    <div class="row rowCounter">
                        <div class="col-md-5">
                            <div class="custom-file b-form-file form-group">
                                <input type="hidden" name="attachment[0][actionType]" id="actionType0" value="I">
                                <input type="hidden" name="attachment[0][docFileId]" id="docFileId0" value="">
                                <input type="file" placeholder="" id="attachment0"
                                       accept="image/*,.pdf,.doc,.docx,application/msword,"
                                       name="attachment[0][file]"
                                       class="custom-file-input file-validation-rules"/>
                                <label for="attachment0" data-browse="Attach File"
                                       class="custom-file-label">Upload Attachment</label>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-11">
                                    <input maxlength="100" class="form-control attachmentDescription" type="text"
                                           name="attachment[0][description]"
                                           placeholder="Attachment Description"/>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-dark btn-sm mr-1 mb-1"
                                            data-initialrow="0"
                                            onclick="addAttachmentRow()"
                                            id="addAttachmentLine" data-row="1"><i
                                            class="bx bx-add-to-queue"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
                <div id="newAttachmentLine" class="mt-1 pt-0"></div>
            </div>
        </div>
    </fieldset>
{{--</div>--}}

<script type="text/javascript">
    var newAttachmentCounter = 0;
    function addAttachmentRow() {
        let count = ($(".rowCounter").length);
        var line = '<div class="row rowCounter dynamicRows mt-1">\n' +
            '                                <div class="col-md-5 pt-0">\n' +
            '                                    <div class="custom-file b-form-file form-group">\n' +
            '                                        <input type="hidden" name="attachment[' + count + '][actionType]" id="actionType' + count + '" value="I">\n' +
            '                                        <input type="hidden" name="attachment[' + count + '][docFileId]" id="docFileId' + count + '" value="">\n' +
            '                                        <input type="file" id="attachment' + count + '" \n' +
            '                                               name="attachment[' + count + '][file]"\n' +
            '                                               class="custom-file-input file-validation-rules"/>\n' +
            '                                        <label for="attachment' + count + '" data-browse="Attach File"\n' +
            '                                               class="custom-file-label">Upload Attachment</label>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="col-md-6 mt-0 pt-0">\n' +
            '<div class="row">' +
            '<div class="col-md-11">' +
            '                                    <input maxlength="100" class="form-control attachmentDescription" type="text" name="attachment[' + count + '][description]"\n' +
            '                                           placeholder="Attachment Description"/>\n' +
            '                                </div>' +
            '                               <div class="col-md-1">\n' +
            '                                   <button type="button" id="removeAttachmentBtn" class="btn btn-sm btn-danger" onclick="removeAttachmentRow(this,' + count + ')"><i\n' +
            '                                    class="bx bx-trash"></i></button>\n' +
            '                        </div>\n' +
            '                        </div>\n' +
            '</div>' +


            '<script>setFileName();<\/script>' +
            '                            </div>';
        $("#newAttachmentLine").append(line);
        ++newAttachmentCounter;
    }

    function setFileName() {
        $('input[type="file"]').on('change', function (e) {
            var fieldVal = $(this).val();

            fieldVal = fieldVal.replace("C:\\fakepath\\", "");

            if (fieldVal != undefined || fieldVal != "") {
                $(this).siblings(".custom-file-label").attr('data-content', fieldVal);
                $(this).siblings(".custom-file-label").text(fieldVal);
            }
        });
    }

    function removeAttachmentRow(selector, counter) {
        $("#actionType" + counter).val("D");
        $(selector).closest('div[class="row rowCounter dynamicRows mt-1"]').hide();
        --newAttachmentCounter;
    }

    function removeAllAttachments() {
        $("#attachment0").val('');
        $("#attachment0").next('label').text('Upload Attachment');
        $(".dynamicRows").remove();
        newAttachmentCounter = 0;
    }

    function newAttachmentDifference() {
        let initialFiles = parseInt($("#addAttachmentLine").data('initialrow'));
        let totalFiles = initialFiles + parseInt(newAttachmentCounter);
        let difference = totalFiles - initialFiles;

        if (initialFiles !== difference){
            return difference;
        }else{
            return 0;
        }
    }

    function downloadAttachment(selector, $doc_id) {
        let request = $.ajax({
            url: APP_URL + "/budget-management/ajax/download-budget-mgt-attachment/" + $doc_id,
        });

        request.done(function (e) {
            console.log("e");
        })

        request.fail(function (jqXHR, textStatus) {
            swal.fire({
                text: jqXHR.responseJSON['message'],
                type: 'warning',
            })
        })
    }

</script>
