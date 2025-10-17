

    $("#submitForm").click((e)=>{
        e.preventDefault();
        data=[
            {"designation":"required|char"},
        ];
        let validate = validatorJS(data);
        if(validate.status){
            $("#designationForm").submit();
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
