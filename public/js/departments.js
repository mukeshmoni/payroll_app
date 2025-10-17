

    $("#submitForm").click((e)=>{
        e.preventDefault();
        data=[
            {"departments":"required|char"},
        ];
        let validate = validatorJS(data);
        if(validate.status){
            $("#departmentsForm").submit();
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
