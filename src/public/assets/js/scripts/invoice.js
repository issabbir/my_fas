//Moved to index page for csrf token issue
/*
function selectAccounts(idSelector, accountsFilterUrl, selectedAccountUrl, lineNumber) {
    $(idSelector).select2({
        dropdownParent: $('#distributionModal' + lineNumber),
        placeholder: "Select an account",
        allowClear: false,
        //minimumInputLength: 1,
        ajax: {
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            delay: 250,
            url: APP_URL + accountsFilterUrl,
            dataType: 'json',
            data: function (params) {
                var query = {
                    q: params.term,
                    exclude: function () {
                        var selectedAccounts = [];
                        $(idSelector).closest("table >.accounts").each(function (elem) {
                            var value = $(this).val();
                            if ((value !== null) || (value !== '') || (value !== undefined)) {
                                selectedAccounts.push(value);
                            }
                        });

                        return JSON.stringify(selectedAccounts);
                    }
                }

                return query;
            },
            processResults: function (data) {
                //Used in some form
                var formattedResults = $.map(data, function (obj, idx) {
                    obj.id = obj.account_id;
                    obj.text = obj.legacy_code + ' (' + obj.description + ')';
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            },
            cache: true
        }
    });

    if ($(idSelector).attr('data-predefined') !== "") {
        //selectDefaultAccount(idSelector, selectedAccountUrl, $(idSelector).data('predefined'));
    }

    $(idSelector).on('select2:select', function (e) {
        var selectedAccount = $(this).find(':selected').val();
        var that = this;

        let request = $.ajax({
            url: APP_URL + selectedAccountUrl,
            data: {id: selectedAccount},
            dataType: "JSON",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            }
        });

        request.done(function (res) {
            var option = new Option(res.legacy_code + ' (' + res.description + ')', res.account_id, true, true);
            $(idSelector).append(option).trigger('change');

            $(".distributionAccountDescription" + lineNumber).val(res.description);
            // let data = {"id":res.account_id, "text":res.legacy_code + ' (' + res.description + ')',"element": HTMLOptionElement}
            /!*$(idSelector).trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });*!/
        });

        request.fail(function (jqXHR, textStatus) {
            console.log(jqXHR, textStatus);
        });
    });

    /!*function selectDefaultAccount(idSelector, selectedAccountUrl, accountId) {
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: APP_URL + selectedAccountUrl,
            data: {id: accountId},
        }).then(function (data) {
            // create the option and append to Select2
            var option = new Option(data.legacy_code + ' (' + data.description + ')', data.account_id, true, true);
            $(idSelector).append(option).trigger('change');

            // manually trigger the `select2:select` event
            $(idSelector).trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        });
    }*!/
}
*/
function getAccountsList(selector, lineNumber) {
    let accountId = $(selector).attr('id');
    selectAccounts("#" + accountId, '/ajax/accounts', '/ajax/accounts-detail', lineNumber)
}
function totalDistributionLineAmount(selector, lineCount) {
    //let count = $(selector).closest("table tbody").children("tr").length;
    //let count = $(".lineDistributionDataTable"+lineCount+" >tbody >tr:visible").length;
    let count = $(".lineDistributionDataTable" + lineCount + " >tbody >tr").length;

    let lineAmount = 0;
    for (let i = 0; i < count; i++) {
        /*if ($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + i + "\\]\\[amount\\]").is(":hidden") == false) {
            if ($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + i + "\\]\\[amount\\]").val() != "") {
                lineAmount += parseFloat($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + i + "\\]\\[amount\\]").val());
            }
        }*/
        if ($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + i + "\\]\\[actionType\\]").val() != "D") {

            if ($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + i + "\\]\\[amount\\]").val() != "") {
                lineAmount += parseFloat($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + i + "\\]\\[amount\\]").val());
            }
        }
    }
    return lineAmount;
}
function validateDistributionTotalAmount(selector, lineCount, rowCount) {
    $("#distributionTotal\\[" + lineCount + "\\]").val(totalDistributionLineAmount(selector, lineCount));
    if ($("#distributionTotal\\[" + lineCount + "\\]").val() != $("#lineTotal\\[" + lineCount + "\\]").val()) {
        $("#distributionTotal\\[" + lineCount + "\\]").css("border", "1px solid red");
        $("#lineTotal\\[" + lineCount + "\\]").css("border", "1px solid red");
    } else {
        $("#distributionTotal\\[" + lineCount + "\\]").css("border", "1px solid gray");
        $("#lineTotal\\[" + lineCount + "\\]").css("border", "1px solid gray");
    }
}
function hideDistribution(selector, lineNumber) {
    let requiredFields = $('.lineDistributionDataTable' + lineNumber + ' tbody > tr:visible').find("input[type=number], select").filter('[required]:visible').prevObject;
    let errorCount = 0;
    requiredFields.each(function () {
        let value = $(this).val();

        if (value == null || value == undefined || value == "") {
            if ($(this).nextAll('.text-danger').length != 0) {
                $(this).nextAll('.text-danger').text("Field can not be empty");
                $(this).focus(function () {
                    $(this).nextAll('.custom-select').select().css('border-color', 'red');
                    //$('#'+this.id).css('border-color', 'red');
                });
            } else {
                $(this).parent().next('.text-danger').text("Field can not be empty");
                $(this).parent().focus(function () {
                    $(this).parent().nextAll('.custom-select').select().css('border-color', 'red');
                    //$('#'+this.id).css('border-color', 'red');
                });
            }
            errorCount++;
            return false;
        } else {
            $(this).nextAll('.text-danger').text("");
            $(this).parent().next('.text-danger').text("");
            $(this).focus(function () {
                $(this).nextAll('.custom-select').select().css('border-color', '#475F7B');
            });

            if (errorCount > 0) {
                errorCount--;
            }
        }
    });
    /*let totalDistroRowAmount = $("#distributionTotal\\[" + lineNumber + "\\]").val();
    let totalLineAmount = $("#lineTotal\\[" + lineNumber + "\\]").val();*/

    let totalLineAmount = $("#lineTotal\\[" + lineNumber + "\\]").val();
    let totalDistroRowAmount = totalDistributionLineAmount(selector, lineNumber);

    if (errorCount == 0) {
        if (totalDistroRowAmount != totalLineAmount) {
            errorCount++;
            Swal.fire({text: 'Distribution amount doesn\'t match with line amount.', type: 'error'});
        } else {
            $("#distributionModal" + lineNumber).modal('hide');
        }
    }
}
function showDistribution(selector, lineNumber) {
    if ($("#line\\[" + lineNumber.toString() + "\\]\\[amount\\]").val() != "" && $("#line\\[" + lineNumber.toString() + "\\]\\[amount\\]").val() != 0) {
        $("#lineNumber\\[" + lineNumber + "\\]").val(lineNumber + 1);
        $("#lineTotal\\[" + lineNumber + "\\]").val($("#line\\[" + lineNumber.toString() + "\\]\\[amount\\]").val());
        $("#lineDesc\\[" + lineNumber + "\\]").val($("#line\\[" + lineNumber.toString() + "\\]\\[desc\\]").val());
        $("#line\\[" + lineNumber + "\\]\\[distribution\\]\\[0\\]\\[type\\]").val($("#line\\[" + lineNumber + "\\]\\[type\\]").val());

        //For update process checking line_number
        if ($("#line\\[" + lineNumber + "\\]\\[line_number\\]").val() == "") {
            if ($("#line\\[" + lineNumber + "\\]\\[distribution\\]\\[0\\]\\[amount\\]").val() == "") {
                $("#line\\[" + lineNumber + "\\]\\[distribution\\]\\[0\\]\\[amount\\]").val($("#line\\[" + lineNumber.toString() + "\\]\\[amount\\]").val());
            }
        }


        $(".invoiceMasterStatus").val($("#invoice_status :selected").text());

        //$("#distributionTotal\\[" + lineNumber + "\\]").val($("#line\\[" + lineNumber.toString() + "\\]\\[amount\\]").val());
        $("#distributionTotal\\[" + lineNumber + "\\]").val(totalDistributionLineAmount(selector, lineNumber));

        $("#distributionModal" + lineNumber).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        //Loading accounts list
        $("#distribution" + lineNumber + "0").trigger("click");
        //Stop blink
        $("#line\\[" + lineNumber + "\\]\\[line_number\\]").closest("tr").removeClass("invalid");

    } else {
        Swal.fire({text: 'Amount not set', type: 'info'});
    }
}
function removeDistributionLineRow(select, lineCount, rowCount) {
    //let count = $(".lineDataTable tbody tr").length + 1;
    //let td = '<td><input type="hidden" name="line[' + count + '][actionType]" value="D"></td>';
    //$(select).closest("tr").append(td);
    //$(select).closest("tr").hide();

    let newVal = parseFloat($("#distributionTotal\\[" + lineCount + "\\]").val()) - parseFloat($("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + rowCount + "\\]\\[amount\\]").val());
    if (newVal != "") {
        $("#distributionTotal\\[" + lineCount + "\\]").val(newVal);
    } else {
        $("#distributionTotal\\[" + lineCount + "\\]").val(0);
    }

    //let count = $(".lineDataTable tbody tr").length + 1;
    //Marking as deleted and removing required either main form submission wont submit
    $("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + rowCount + "\\]\\[amount\\]").removeAttr("required");
    $("#distribution" + lineCount+""+rowCount).removeAttr("required");
    //$("#distribution" + lineCount+""+rowCount).closest("span[class=text-danger]").remove();
    $(select).closest("tr").find(".distributionActionType").val("D");
    $(select).closest("tr").hide();

    validateDistributionTotalAmount(select, lineCount, rowCount);
}
function addDistributionLineRow(selector, lineCount) {
    let rowCount = $(selector).closest("table tbody").children("tr").length;
    let html = '<tr>\n' +
        '      <td>' + (rowCount + 1) + '<input type="hidden" name="line[' + lineCount + '][distribution][' + rowCount + '][invoice_distribution_id]" id="line[' + lineCount + '][distribution][' + rowCount + '][invoice_distribution_id]"/><input type="hidden"\n' +
        '                                                                                                    name="line[' + lineCount + '][distribution][' + rowCount + '][actionType]" id="line[' + lineCount + '][distribution][' + rowCount + '][actionType]" class="distributionActionType" value="I"/></td>\n' +
        '      <td style="padding:4px;"><input style="width: 200px" type="text"  id="line[' + lineCount + '][distribution][' + rowCount + '][type]" class="form-control" readonly /> <input name="line[' + lineCount + '][distribution][' + rowCount + '][type]" type="hidden"/></td>\n' +
        '      <td style="padding:4px;"><input style="width: 200px; text-align:right;" required type="number" step="0.02" name="line[' + lineCount + '][distribution][' + rowCount + '][amount]" id="line[' + lineCount + '][distribution][' + rowCount + '][amount]" class="line[' + lineCount + '][distribution][' + rowCount + '][amount] distributionAmount form-control" \n' +
        '                 placeholder="Amount" onkeyup="validateDistributionTotalAmount(this,' + lineCount + ',' + rowCount + ')" ><span class="text-danger"></span></td>\n' +
        '      <td style="padding:4px;" >\n' +
        '          <select style="width: 200px" required name="line[' + lineCount + '][distribution][' + rowCount + '][account]"  id="distribution' + lineCount + rowCount + '" class="accounts form-control" data-account-id="" onclick="getAccountsList(this,' + lineCount + ')">\n' +
        '              <option value="">&lt;Select&gt;</option>\n' +
        '          </select>\n<span class="text-danger"></span>' +
        '      </td>\n' +
        '      <td style="padding:4px;"><input style="width: 200px" type="text" class="form-control" name="line[' + lineCount + '][distribution][' + rowCount + '][description]"  placeholder="Description"></td>\n' +
        '      <td style="padding:4px;"><input style="width: 200px" type="text" class="form-control" name="line[' + lineCount + '][distribution][' + rowCount + '][assetbook]"  placeholder="Asset Book"></td>\n' +
        '      <td>\n' +
        // '          <span onclick="addDistributionLineRow(this,' + lineCount + ')"\n' +
        // '                  class="cursor-pointer success"><i\n' +
        // '                  class="bx bx-plus-circle"></i></span>|\n' +
        '<span onclick="removeDistributionLineRow(this,' + lineCount + ',' + rowCount + ')" class="cursor-pointer danger"><i class="bx bx-trash"></i></span></td>\n' +
        '      </td>\n' +
        '  </tr>';
    $(selector).closest("table >tbody").append(html);

    $("#line\\[" + lineCount + "\\]\\[distribution\\]\\[" + rowCount + "\\]\\[type\\]").val($("#line\\[" + lineCount + "\\]\\[type\\]").val());
    $("#distribution" + lineCount + rowCount).trigger("click");
}
function editLineRow(selector, counter) {
    $(".type").val($(selector).parent().parent().find('input[name="line[' + counter + '][type]"]').val());
    $(".amount").val($(selector).parent().parent().find('input[name="line[' + counter + '][amount]"]').val());
    $(".desc").val($(selector).parent().parent().find('input[name="line[' + counter + '][desc]"]').val());
    $(".remarks").val($(selector).parent().parent().find('input[name="line[' + counter + '][remark]"]').val());

    $(".addLineModal").trigger("click");

    $(".addLine").data("process", "U");
    $(".addLine").data("rowcount", counter);
    $(".addLine").text("Update line");
}

