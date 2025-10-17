

    $("#submitForm").click((e)=>{
        e.preventDefault();
        data=[
            {"rap":"required|msg:RAP Must"},
            //{"month":"required|msg:Select Month"},
            {"from":"required|msg:Select Month/Year"},
          
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
