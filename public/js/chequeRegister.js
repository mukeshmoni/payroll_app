$(document).ready(()=>{
    $loader = '<svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve"><path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" /></path></svg>';
    $fetchBtn = '<span style="height:40px;display:flex;align-items:center">Fetch</span>';
    formData =[];
    $("#br_id").keypress(function(e) {
        // Enter pressed?
        if(e.which == 10 || e.which == 13) {
           
           getBRdetails();
        }
    });

    $("#fetchBtn").click((e)=>{
        getBRdetails();
    })

    const getBRdetails = () =>{
        $(".alert").html("").fadeOut();
        $br_id = $("#br_id").val();
        $acc_no = $("#acc_no").val();
        $payment_mode = $("#payment_mode").val();
        if($br_id=="" || $br_id==null || $br_id==undefined){
            $("#br_id_err").html("Enter valid BR Number").fadeIn();
            return;
        }
        if($acc_no==""){
            $("#acc_no_err").html("Select Account").fadeIn();
            return;
        }

        if($payment_mode==""){
            $("#payment_mode_err").html("Select Payment Mode").fadeIn();
            return;
        }

        //check if the BR already present in the list
        $br_list = [];
        $('.br_no').each(function() {
            $br_list.push($(this).val());
        });

        if($br_list.includes($br_id)){
            $("#br_id_err").html("BR No number already applied").fadeIn();
            return false;
        }
        
        $("#fetchBtn").html($loader);
        $.ajax({
            url: "./getBrDetails",
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {br_id:$br_id,acc_no:$acc_no},
            success: (data) => {
                $("#fetchBtn").html($fetchBtn);
                if(data.status){
                    $("#br_id").val("");
                    br_data = data.data;
                    vr_no = br_data.vr_no
                    formData[br_data.id]=br_data;
                    formData[br_data.id].deductions=[];
                    formData[br_data.id].employees=[];
                    // if($(".vr_no:first").val()!="" && $(".vr_no:first").val()!=undefined && $(".vr_no:first").val()!=null){
                    //     vr_no = +$(".vr_no:first").val() + +1;
                    // }
                    console.log(formData);
                    $row = $('table#br_table tr.br_row:last')
                    $row.find("#br_no").val(br_data.id);
                    $row.find("#vr_no").val(vr_no);
                    $row.find("#particulars").val(br_data.particulars);
                    $row.find("#amount").val(br_data.amount);
                    $row.find("#total_amount").val(br_data.amount);

                    $("#br_list").append(`
                        <table class="table table-bordered" id="br_table">
                        <thead class="bg-inverse-secondary text-dark">
                            <th class="text-center">BR. No</th>
                            <th class="text-center">VR. No</th>
                            <th class="text-center">Particulars</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Head of Acc.</th>
                            <th class="text-center">Tot Amt.</th>
                            <th class="text-center">Actions</th>
                            <!-- <th class="text-center">Add Deductions</th> -->
                        </thead>
                        <tbody id="br_table_body">
                            <tr class="br_row">
                                <td style="width: 150px;">
                                    <input type="number" id="br_no" name="br_no[]" class="form-control br_no" readonly placeholder="BR. No">
                                </td>
                                <td style="width: 150px;">
                                    <input type="number" id="vr_no" name="vr_no[]" class="form-control vr_no" readonly placeholder="VR. No">
                                </td>
                                <td>
                                    <input type="text" id="particulars" name="particulars[]" class="form-control particulars" placeholder="Particulars">
                                </td>
                                <td style="width: 150px;">
                                    <input type="number" id="amount" name="amount[]" class="form-control text-right amount" placeholder="Amount">
                                </td>
                                <td style="width: 150px;">
                                    <select name="head_acc[]" id="head_acc" class="form-control head_acc">
                                        <option value="">Select Head</option>
                                        <option value="OH-31">OH-31</option>
                                        <option value="OH-35">OH-35</option>
                                        <option value="OH-36">OH-36</option>
                                    </select>
                                </td>
                                <td style="width: 150px;">
                                    <input type="number" id="total_amount" name="total_amount[]" class="form-control text-right total_amount" readonly placeholder="Total Amount">
                                </td>
                                <td style="width: 100px;">
                                    <a class="nav-link dropdown-toggle text-gray-700" href="#" data-toggle="dropdown" id="profileDropdown">
                                        <i class="mdi mdi-dots-vertical m-0 p-0" style="font-size:18px"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown p-2" aria-labelledby="profileDropdown">
                                        <a href="#" class="text-decoration-none d-block p-2 text-gray-800 addEmployee d-flex gap-4 align-items-center"><i class="mdi mdi-account-plus m-0 p-0" style="font-size:18px"></i>  <span>List Employee</span></a>
                                        <a href="#" class="text-decoration-none d-block p-2 text-gray-800 addDeduction d-flex gap-4 align-items-center"><i class="mdi mdi-percent m-0 p-0" style="font-size:18px"></i>  <span>Add Deduction</span></a>
                                        <a href="#" class="text-decoration-none d-block p-2 text-danger removeTable d-flex gap-4 align-items-center"><i class="mdi mdi-minus-circle m-0 p-0" style="font-size:18px"></i> <span>Delete</span></a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            </table>
                    `);
                    calculateChequeAmount();
                }else{
                    $("#br_id_err").html(data.message).fadeIn();
                }
                console.log(data)
            },
            error: (err) => {
                $("#fetchBtn").html($fetchBtn);
                console.log(err)
            },
        });
    }

    $(document).on("click",".addEmployee",async (e)=>{
        $empList = $("#hideEmpList").html();
        $row = await getRow($(e.target).closest("tr"));
        console.log($row);
        // $row = $(e.target).closest("tr");
        // $nextRow = $row.next("tr");   
        // // nextRowClass = $nextRow.attr("class");
        // $row = getRow($nextRow);
        // if(nextRowClass == 'dedution_tr'){
        //     $row = $nextRow;
        // }

        // <select name="employee[]" id="${uniqueId}" class="form-control form-control-sm select-input-selected selectTag d-block">
        //                     `+$empList+`
        //                 </select>

        $curr_row = $(e.target).parents("tr");
        $br_id = $curr_row.find("#br_no").val();
        let uniqueId = `select-${Date.now()}`;
        $row.after(`
            <tr class="deduction_tr" data-id='${$br_id}'>
                 <td style="width: 150px;" colspan="2">
                    <input type="hidden" id="employee_br_no" name="employee_br_no[]" class="form-control" readonly placeholder="BR. No">
                    <input type="hidden" id="employee_vr_no" name="employee_vr_no[]" class="form-control" readonly placeholder="VR. No">
                        <label class="form-label font-italic d-block text-gray-400">Select Employee</label>
                        <input type="text" id="employee" name="employee[]" class="form-control form-control-sm employee" placeholder="Enter Employee Name">
                </td>
                <td style="padding:0px">
                    <table style="width:100%;">
                        <tr>
                            <td style="border:0px solid white;">
                                <label class="form-label font-italic text-gray-400">Select Deduction Type</label>
                                <select name="deduction_type[]" id="deduction_type" class="form-control form-control-sm">
                                    <option value="">Select Type</option>
                                    <option value='TDS'>TDS on IT %</option>
                                    <option value='GST'>GST %</option>
                                </select>
                            </td>
                            <td style="border:0px solid white;">
                                <label class="form-label font-italic text-gray-400">Enter Deduction %</label>
                                <input type="number" id="deduction_perc" name="deduction_perc[]" class="form-control form-control-sm deductionInput deduction_perc" placeholder="Enter %">
                            </td>
                            <td style="border:0px solid white;">
                                <label class="form-label font-italic text-gray-400">Enter CESS % (If required)</label>
                                <input type="number" id="cess_perc" name="cess_perc[]" class="form-control form-control-sm deductionInput cess_perc" placeholder="Enter %">
                            </td>
                        </tr>
                    </table>
                </td>
                 <td>
                    <label class="form-label font-italic text-gray-400">Amount</label>
                    <input type="number" id="deduction_tot_amount" name="deduction_tot_amount[]" class="form-control text-right form-control-sm deductionInput deduction_tot_amount" placeholder="">
                </td>
                <td>
                    <label class="form-label font-italic text-gray-400">Deducted Amount</label>
                    <input type="number" id="deduction_deducted_amount" name="deduction_deducted_amount[]" class="form-control text-right form-control-sm deductionInput deduction_deducted_amount" placeholder="">
                </td>
                <td>
                    <label class="form-label font-italic text-gray-400">Net Amount</label>
                    <input type="number" id="deduction_net_amount" name="deduction_net_amount[]" class="form-control text-right form-control-sm deductionInput deduction_net_amount" placeholder="">
                </td>
                <td>
                    <div class="d-flex justify-center align-items-center removeRow">
                        <span class="d-flex justify-center align-items-center rounded-full h-10 w-10 bg-danger text-light shadow-md cursor-pointer">
                            <i class="mdi mdi-minus m-0 p-0" style="font-size:18px"></i>
                        </span>
                    </div>
                </td>
             </tr>
         `);
         $(".selectTag").last().select2();
    })

    $(document).on("click",".addDeduction",async (e)=>{
        $row = await getRow($(e.target).closest("tr"));
        console.log($row);
        // $row = $(e.target).closest("tr");
        // $nextRow = $row.next("tr");   
        // // nextRowClass = $nextRow.attr("class");
        // $row = getRow($nextRow);
        // if(nextRowClass == 'dedution_tr'){
        //     $row = $nextRow;
        // }
        $curr_row = $(e.target).parents("tr");
        $br_id = $curr_row.find("#br_no").val();
        $row.after(`
            <tr class="deduction_tr" data-id='${$br_id}'>
                <td style="width: 150px;" colspan="2">
                    <input type="hidden" id="decution_br_no" name="decution_br_no[]" class="form-control" readonly placeholder="BR. No">
                    <input type="hidden" id="decution_vr_no" name="decution_vr_no[]" class="form-control" readonly placeholder="VR. No">
                </td>
                <td style="padding:0px">
                    <table style="width:100%;">
                        <tr>
                            <td style="border:0px solid white">
                                <label class="form-label font-italic text-gray-400">Select Deduction Type</label>
                                <select name="deduction_type[]" id="deduction_type" class="form-control form-control-sm">
                                    <option value="">Select Type</option>
                                    <option value='TDS'>TDS on IT %</option>
                                    <option value='GST'>GST %</option>
                                </select>
                            </td>
                             <td style="border:0px solid white;">
                                <label class="form-label font-italic text-gray-400">Enter Deduction %</label>
                                <input type="number" id="deduction_perc" name="deduction_perc[]" class="form-control form-control-sm deductionInput deduction_perc" placeholder="Enter %">
                            </td>
                            <td style="border:0px solid white;">
                                <label class="form-label font-italic text-gray-400">Enter CESS % (If required)</label>
                                <input type="number" id="cess_perc" name="cess_perc[]" class="form-control form-control-sm deductionInput cess_perc" placeholder="Enter %">
                            </td>
                        </tr>
                    </table>
                </td>
                 <td>
                    <label class="form-label font-italic text-gray-400">Amount</label>
                    <input type="number" id="deduction_tot_amount" name="deduction_tot_amount[]" class="form-control text-right form-control-sm deductionInput deduction_tot_amount" placeholder="">
                </td>
                <td>
                    <label class="form-label font-italic text-gray-400">Deducted Amount</label>
                    <input type="number" id="deduction_deducted_amount" name="deduction_deducted_amount[]" class="form-control text-right form-control-sm deductionInput deduction_deducted_amount" placeholder="">
                </td>
                <td>
                    <label class="form-label font-italic text-gray-400">Net Amount</label>
                    <input type="number" id="deduction_net_amount" name="deduction_net_amount[]" class="form-control text-right form-control-sm deductionInput deduction_net_amount" placeholder="">
                </td>
                <td>
                    <div class="d-flex justify-center align-items-center removeRow">
                        <span class="d-flex justify-center align-items-center rounded-full h-10 w-10 bg-danger text-light shadow-md cursor-pointer">
                            <i class="mdi mdi-minus m-0 p-0" style="font-size:18px"></i>
                        </span>
                    </div>
                </td>
            </tr>
         `);
    })

    // $(document).on('click',".removeRow",async (e)=>{
    //     $row = $(e.target).parents("table");
    //     console.log($row)
    // })

    // const removeRow=($row)=>{
    //     $nextRow = $row.next('tr');
    //     nextRowClass = $nextRow.attr("class");
    //     console.log(nextRowClass)
    //     if(nextRowClass=="deduction_tr"){
    //         // console.log("ded tr")
    //         // $row = $nextRow;
    //         $nextRow.remove();
    //         return getRow($row);
    //     }else{
    //         $row.remove();
    //         return $row;
    //     }
    // }

    const getRow=($row)=>{
        $nextRow = $row.next('tr');
        nextRowClass = $nextRow.attr("class");
        console.log(nextRowClass)
        if(nextRowClass=="deduction_tr"){
            console.log("ded tr")
            $row = $nextRow;
            return getRow($row);
        }else{
            return $row;
        }
    }
    $(document).on("click",".removeTable",(e)=>{
        $row = $(e.target).parents("tr");
        $br_id = $row.find("#br_no").val();
        $vr_id = $row.find("#vr_no").val();
        $acc_no = $("#acc_no").val();
        $(".alert").fadeOut();
        $("#loader").fadeIn();
        $.confirm({
            title: 'Are you sure you want to delete?',
            content: "You can't revert the action",
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm:{
                    btnClass: 'btn btn-danger',
                    action:function(){
                        $.ajax({
                            url: "./deleteVR",
                            type: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {vr_id:$vr_id,br_id:$br_id,acc_no:$acc_no},
                            success: (data) => {
                                if(data.status){
                                    $(e.target).parents('table').remove()
                                    formData[$br_id] = {};
                                    calculateChequeAmount();
                                    $("#form_success").html(data.data).fadeIn();

                                    console.log(formData)
                                }else{
                                    $("#form_err").html(data.data).fadeIn();
                                }
                                console.log(data)
                                $("#loader").fadeOut();
                            },
                            error: (err) => {
                                // $("#fetchBtn").html($fetchBtn);
                                console.log(err)
                            },
                        });
                    }
                },
                cancel:{
                    btnClass: 'btn btn-dark',
                    action:function(){
                        return true;
                        $(".spinner-body").fadeOut();
                    }
                },
            }
        });
    });

    $(document).on("click",".removeRow",(e)=>{
        $parent_row = $(e.target).parents("tr.deduction_tr");
        $index = $parent_row.index();
        $br_id = $parent_row.attr("data-id");
        $table = $(e.target).parents("table");
        formData[$br_id].deductions[$index]=null

        $(e.target).parents("tr").remove();
        calculateBRtotal($table);
    });

    $(document).on("change",".particulars, .amount, .head_acc",(e)=>{
        $parent_row = $(e.target).parents('tr');
        $br_id = $parent_row.find("#br_no").val();
        $particulars = $parent_row.find("#particulars").val();
        $amount = $parent_row.find("#amount").val();
        $head_acc = $parent_row.find("#head_acc").val();

        formData[$br_id].particulars = $particulars;
        formData[$br_id].amount = $amount;
        formData[$br_id].head_acc = $head_acc;

        $table = $(e.target).parents("table");
        calculateBRtotal($table);
    })

    $(document).on("change","#deduction_type, .employee",(e)=>{
        $parent_row = $(e.target).parents("tr.deduction_tr");
        $index = $parent_row.index();
        $br_id = $parent_row.attr("data-id");

        $employee = $parent_row.find("#employee").val();
        $deduction_type = $parent_row.find("#deduction_type").val();
        
        formData[$br_id].deductions[$index].employee = $employee;
        formData[$br_id].deductions[$index].deduction_type = $deduction_type;

    })

    $(document).on("keyup",".deductionInput",(e)=>{
        $parent_row = $(e.target).parents("tr.deduction_tr");
        $index = $parent_row.index();
        $br_id = $parent_row.attr("data-id");
        
        let $row = $(e.target).parents("tr");
        $perc = $row.find("#deduction_perc").val();
        $cess_perc = $row.find("#cess_perc").val();
        $deduction_tot_amount = $row.find("#deduction_tot_amount").val();

        $deduction_deducted_amount = $deduction_tot_amount*($perc/100);
        if($cess_perc!="" && $cess_perc!=undefined && $cess_perc!=null){
            $deduction_deducted_amount = +$deduction_deducted_amount + +($deduction_deducted_amount*($cess_perc/100));
        }
        $deduction_net_amount = $deduction_tot_amount-$deduction_deducted_amount;

        $row.find("#deduction_deducted_amount").val(Math.round($deduction_deducted_amount))
        $row.find("#deduction_net_amount").val(Math.round($deduction_net_amount))

        $employee = $row.find("#employee").val();
        $deduction_type = $row.find("#deduction_type").val();

        $table = $(e.target).parents("table");

        formData[$br_id].deductions[$index] = {
            employee : $employee,
            deduction_type : $deduction_type,
            deduction_perc : $perc,
            cess_perc : $cess_perc,
            deduction_tot_amount : $deduction_tot_amount,
            deduction_deducted_amount : $deduction_deducted_amount,
            deduction_net_amount : $deduction_net_amount,
        }


        calculateBRtotal($table);
    })

    const calculateBRtotal = ($table)=>{
        $sum = 0;
        $table.find(".deduction_net_amount").each((index,ele)=>{
            $sum+= +$(ele).val();
        });

        if($sum==0){
            $total_amount = $table.find("#amount").val();
            $table.find(".total_amount").val(Math.round($total_amount));
        }else{
            $table.find(".total_amount").val(Math.round($sum));
        }

        calculateChequeAmount();
        
    }

    const calculateChequeAmount = ()=>{
        $chequeAmt = 0;
        $(".total_amount").each((index,ele)=>{
            $chequeAmt = +$chequeAmt + +$(ele).val();
        })

        $("#cheque_amount").val($chequeAmt);
    }

    $("#billRegisterForm").submit((e)=>{
        e.preventDefault();
        $url = $(e.target).attr("action");
        $method = $(e.target).attr('method');
        updateFlag = $("#updateFlag").val();
        cheque_no = $("#cheque_no").val();
        bank_acc_no = $("#acc_no").val();
        payment_mode = $("#payment_mode").val();
        cheque_date = $("#cheque_date").val();
        cheque_amount = $("#cheque_amount").val();
        $("#brSubmitForm").html($loader);
        $.ajax({
            url: $url,
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {formData,
                cheque_no:cheque_no,
                updateFlag:updateFlag,
                bank_acc_no:bank_acc_no,
                payment_mode:payment_mode,
                cheque_date:cheque_date,
                cheque_amount:cheque_amount,
            },
            success: (data) => {
                if(data.status){
                    $("#brSubmitForm").html("Submit Register");
                    $("#form_success").html(data.data).fadeIn();
                    formData =[];
                    $('#br_list').children(':not(:last-child)').remove();
                    $('#billRegisterForm').trigger('reset');
                    if(data.redirectUrl){
                        window.location.href = data.redirectUrl;
                    }
                }else{
                    $("#form_err").html(data.data).fadeIn();
                }
                console.log(data)
                $("#loader").fadeOut();
            },
            error: (err) => {
                // $("#fetchBtn").html($fetchBtn);
                console.log(err)
            },
        });
    })

});
const setFormData = (data)=>{
    console.log(data);
}