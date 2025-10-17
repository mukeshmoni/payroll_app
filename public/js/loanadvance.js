

    $("#submitForm").click((e)=>{
        e.preventDefault();
        data=[
            {"empid":"required|msg:Employee Name Must"},
            {"la":"required|msg:Choose Either Loan Advance"},
            {"ded":"required?aval:1|msg:Choose Deduction Type"},
            //{"alw":"required?dval:1|msg:Choose Allowance Type"},
            {"tenure":"required|msg:Tenure is Must"},
            {"amt":"required|msg:Amount is Must"},
            {"startdt":"required|date|msg:Starting Date is Must"},           
            // {"tenure":"required|msg:Tenure is Must"},           
        ];
        let validate = validatorJS(data);
        if(validate.status){
            $("#attendanceForm").submit();
            return true;
        }
        else{
            let errors = validate.errors;
            errors.forEach(error=>{
                Object.keys(error).forEach(id=>{
                    $("#"+id).addClass("is-invalid");
                    $("#"+id+"_err").html(error[id]).fadeIn();
                })
            });
        }
    })
