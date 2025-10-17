

    $("#submitForm").click((e)=>{
        e.preventDefault();
        data=[
            {"empid":"required|msg:Emplaoyee Name Must"},
            {"startdt":"required|date|msg:Starting Date is Must"},
            {"enddt":"required|date|msg:End Date is Must"},
            {"leavetype":"required|char|msg:Leave Type is Must"},
            {"days":"required?mdays:1|msg:Choose Full/Half day"},
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
