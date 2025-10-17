function removeAllowance(e){
    $(e).parents(".allowance_card").remove();
    calculateNetsalary();
}
function removeDeduction(e){
    $(e).parents(".deduction_card").remove();
    calculateNetsalary();
}
function removeLA(e){
    $(e).parents(".la_card").remove();
    calculateNetsalary();
}
const calculateNetsalary = ()=>{
    // check for additional allowance_opt,deduction and loan/Advances
    let $addAllowance = 0
    let $addDeduction = 0
    let $addls = 0
    
    if($(".allowance_card").length>0){
        $('.allowance_card').each(function() {
            let allowance_amount = $(this).find('#allowance_amount').val();
            if(allowance_amount!=""){
                $addAllowance = $addAllowance+ +allowance_amount;
            }
        });
    }
    if($(".deduction_card").length>0){
        $('.deduction_card').each(function() {
            let deduction_amount = $(this).find('#deduction_amount').val();
            if(deduction_amount!=""){
                $addDeduction = $addDeduction+ +deduction_amount;
            }
        });
    }
    if($(".la_card").length>0){
        $('.la_card').each(function() {
            let la_amount = $(this).find('#la_amount').val();
            if(la_amount!=""){
                $addls = $addls+ +la_amount;
            }
        });
    }
    $("#gross_salary").val((+$("#basicsalary").val() + +$("#addtl_pension").val() + +$("#da").val() + +$("#medic_allow").val() + +$("#misc").val() + +$addAllowance)-(+$("#less_comm").val()));
    $("#total_deduction").val(+$("#misc_rec").val() + +$("#irg").val() + +$("#it").val() + +$addDeduction + +$addls);
    $("#net_salary").val((+$("#gross_salary").val()) - (+$("#misc_rec").val() + +$("#irg").val() + +$("#it").val() + +$addDeduction + +$addls));
}

$(document).ready((e)=>{
    let allowance_opt = "";
    let deduction_opt = "";
    $allowances.forEach(val => {
        allowance_opt+=`<option value='`+val.id+`'>`+val.allowance_name+`</option>`;
    });
    $deductions.forEach(val => {
        deduction_opt+=`<option value='`+val.id+`'>`+val.deduction_name+`</option>`;
    });
    $("#category").change((e)=>{
        // let dept = $("#department").val();
        // let desg = $("#designation").val();
        let category = $("#category").val();
         $("#employee").html('<option value="">Searching<option>')
        if(category){
            $.ajaxSetup({
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
             });
             $.ajax({
                type:'get',
                url:'./pension/getEmployees',
                // data:{dept:dept,desg:desg},
                data:{cat:category,from:"pensioner"},
                success:function(data) {
                   $("#employee").html(data.message)
                },
                error: function (msg) {
                   console.log(msg);
                   var errors = msg.responseJSON;
                }
             });
        }else{
            $("#employee").html('<option value="">Select Employee</option>')
        }
    })
    $("#employee").change((e)=>{
        const empid = $(e.target).val();
        $.ajaxSetup({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         $.ajax({
            type:'get',
            url:'./getLoanDetails',
            data:{empid:empid},
            success:function(data) {
                const la = data.la
                let det = "";
                $("#department").val(data.department);
                $("#designation").val(data.designation);
                if(data.existing_salary){
                    const salary = data.salary;
                    console.log(salary)

                    $("#basicsalary").val(salary.basic_salary).keyup();
                    $("#addtl_pension").val(salary.addtl_pension).keyup();
                    $("#medic_allow").val(salary.medic_allow).keyup();
                    $("#misc").val(salary.misc).keyup();
                    $("#less_comm").val(salary.less_comm).keyup();
                    $("#misc_rec").val(salary.it).keyup();
                    $("#irg").val(salary.it).keyup();
                    $("#it").val(salary.it).keyup();
                    $("#narration").val(salary.narration);

                    //allowance
                    $("#add_allowance").empty();
                    let allowances = JSON.parse(salary.allowances);
                    let tempLoop1 = Object.keys(allowances)
                    tempLoop1.forEach(key=>{
                        $("#allowance_add_btn").click();
                        $("#add_allowance .allowance_card").last().find("#allowance_type").val(key);
                        $("#add_allowance .allowance_card").last().find("#allowance_amount").val(allowances[key]);
                    });

                    //deductions
                    $("#add_deductions").empty();
                    let deductions = JSON.parse(salary.deductions);
                    let tempLoop2 = Object.keys(deductions)
                    tempLoop2.forEach(key=>{
                        $("#deduction_add_btn").click();
                        $("#add_deductions .deduction_card").last().find("#deduction_type").val(key);
                        $("#add_deductions .deduction_card").last().find("#deduction_amount").val(deductions[key]);
                    });
                    calculateNetsalary();
                }else{
                    $("#basicsalary").val(data.emppay).keyup();
                }
                $("#add_loans_advance").empty();
               la.forEach(data => {
                $("#add_loans_advance").append(`
                    <div class='col-md-6 rounded border shadow-sm mb-4 la_card position-relative'>
                        <div class="row">
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label class="form-label">Loan/Advance Type <span class="text-danger">*</span></label>
                                    <select name="la_type[]" id="la_type" class="form-control text-capitalize" required readonly>
                                        <option value="`+data.id+`">`+data.da_types+`</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" id="la_amount" name="la_amount[]" class="form-control additional_value" value="`+data.amt+`" required readonly>
                                </div>
                            </div>
                            <span class='position-absolute text-danger fw-bold' style="top:5px;right:5px;cursor:pointer" onclick="removeLA(this)"><i class="mdi mdi-minus-circle-outline" style="vertical-align: middle;"></i></span>
                        </div>
                    </div>
                `)
               });
               
               calculateNetsalary();
            },
            error: function (msg) {
               console.log(msg);
               var errors = msg.responseJSON;
            }
         });
    })
    $("#allowance_add_btn").click((e)=>{
        $("#add_allowance").append(`
            <div class='col-md-6 rounded border shadow-sm mb-4 allowance_card position-relative'>
                <div class="row">
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class="form-label">Allowance Type <span class="text-danger">*</span></label>
                            <select name="allowance_type[]" id="allowance_type" class="form-control text-capitalize" required>
                                <option value="">Select Type</option>
                                `+allowance_opt+`
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" id="allowance_amount" name="allowance_amount[]" class="form-control additional_value" required>
                        </div>
                    </div>
                    <span class='position-absolute text-danger fw-bold' style="top:5px;right:5px;cursor:pointer" onclick="removeAllowance(this)"><i class="mdi mdi-minus-circle-outline" style="vertical-align: middle;"></i></span>
                </div>
            </div>
        `)
    });
    $("#deduction_add_btn").click((e)=>{
        $("#add_deductions").append(`
            <div class='col-md-6 rounded border shadow-sm mb-4 deduction_card position-relative'>
                <div class="row">
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class="form-label">Deduction Type <span class="text-danger">*</span></label>
                            <select name="deduction_type[]" id="deduction_type" class="form-control text-capitalize" required>
                                <option value="">Select Type</option>
                                `+deduction_opt+`
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" id="deduction_amount" name="deduction_amount[]" class="form-control additional_value" required>
                        </div>
                    </div>
                    <span class='position-absolute text-danger fw-bold' style="top:5px;right:5px;cursor:pointer" onclick="removeDeduction(this)"><i class="mdi mdi-minus-circle-outline" style="vertical-align: middle;"></i></span>
                </div>
            </div>
        `)
    });
    // const $da = 42;
    // let $hra = $("#hra_perc").val();
    // const $slab = 7200;
    console.log($allowances,$deductions,$da);
    $("#basicsalary,#addtl_pension").keyup((e)=>{

        $basic_pension = $("#basicsalary").val()
        $add_pension = $("#addtl_pension").val()
        $("#da").val(da_allowances(+$basic_pension + +$add_pension));

        calculateNetsalary();

    });

    $("#medic_allow,#misc,#less_comm,#da,#it,#misc_rec,#irg").keyup((e)=>{
        calculateNetsalary();
    })
    // $("#pf").keyup(()=>{
    //     let pfAmnt = $("#pf").val();
    //     if((pfAmnt*12)>500000){
    //         $("#pf_err").html("PF deduction exceeds 5 lakhs for the year").fadeIn();
    //     }else{
    //         $("#pf_err").html("").fadeIn();
    //     }
    // })
    $(document).on("keyup",".additional_value",(e)=>{
        calculateNetsalary();
    })

    // allowances
    const da_allowances = (pay)=>{
        // Basic Pay x DA%
        return Math.round(pay*($da/100));
    }

    // const hra_allowances = (pay)=>{
    //     // Basic Pay x HRA%
    //     return Math.round(pay*($hra/100));
    // }

    // const travel_allowances=()=>{
    //     // (Slab Rate x DA%)+Slab Rate
    //     let $slab = $("#slab").val();
    //     return Math.round(($slab*($da/100))+ + +$slab);
    // }

    // deductions
    // const pf_deduction = (pay)=>{
    //     // (Basic Pay + DA%) x 12% 
    //     // return Math.round((+pay + +$("#da").val())*(12/100));
    //         let pfAmnt = Math.round((pay)*(6/100));
    //         if((pfAmnt*12)>500000){
    //             $("#pf_err").html("PF deduction exceeds 5 lakhs for the year").fadeIn();
    //         }else{
    //             $("#pf_err").html("").fadeIn();
    //         }
    //         if(!$nps){
    //             return Math.round((pay)*(6/100));
    //         }else{
    //             return 0;
    //         }
    // }

    // const eps_deduction=(pay)=>{
    //     //(Basic Pay + DA) x 8.33
    //     return (+pay + +$("#da").val())*8.33
    // }

    // const epf_deduction = (pay)=>{
    //     //Employee PF - EPS
    //     return pf_deduction(pay)-eps_deduction(pay);
    // }

    // const nps_employee = (pay)=>{
    //     // (Basic Pay + DA%) x 10% 
    //     if($nps){
    //         return Math.round((+pay + +$("#da").val())*(10/100));
    //     }else{
    //         return 0;
    //     }
    // }

    // const nps_employer = (pay)=>{
    //     // (Basic Pay + DA%) x 14% 
    //     if($nps){
    //         return Math.round((+pay + +$("#da").val())*(14/100));
    //     }else{
    //         return 0;
    //     }
    // }


    $("#submitForm").click((e)=>{
        e.preventDefault();
        $(".errorTxt").fadeOut();
        data=[
            // {"department":"required|numeric|msg:Select Department"},
            // {"designation":"required|numeric|msg:Select Designation"},
            {"basicsalary":"required|numeric|msg:Basic Pension is required"},
            {"da":"required|numeric|msg:DA allowances required"},
            {"gross_salary":"required|numeric|msg:Gross Total is invalid"},
            {"net_salary":"required|numeric|msg:Net Total is required"},
        ];
        if($(".allowance_card").length>0){
            $('.allowance_card').each(function() {
                let allowance_type = $(this).find('#allowance_type').val();
                let allowance_amount = $(this).find('#allowance_amount').val();
                if(allowance_type=="" || allowance_amount==""){
                    $("#allowance_type_err").html("Type/Amount is not provided for some allowance").fadeIn();
                    return false;
                }
            });
        }
        if($(".deduction_card").length>0){
            $('.deduction_card').each(function() {
                let deduction_type = $(this).find('#deduction_type').val();
                let deduction_amount = $(this).find('#deduction_amount').val();
                if(deduction_type=="" || deduction_amount==""){
                    $("#deduction_type_err").html("Type/Amount is not provided for some deduction").fadeIn();
                    return false;
                }
            });
        }
        if($(".la_card").length>0){
            $('.la_card').each(function() {
                let la_type = $(this).find('#la_type').val();
                let la_amount = $(this).find('#la_amount').val();
                if(la_type=="" || la_amount==""){
                    $("#la_type_err").html("Type/Amount is not provided for some Loan/Advance").fadeIn();
                    return false;
                }
            });
        }
        let validate = validatorJS(data);
        console.log(validate);
        if(validate.status){
            $("#SalaryForm").submit();
            return true;
        }else{
            let errors = validate.errors;
            errors.forEach(error=>{
                Object.keys(error).forEach(id=>{
                    $("#"+id).addClass("is-invalid");
                    $("#"+id+"_err").html(error[id]).fadeIn();
                })
            });
        }
    })

    $("#basicsalary").keyup();

});
