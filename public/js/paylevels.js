$(document).ready(()=>{
    $("#submitForm").click((e)=>{
        e.preventDefault();
        $(".errorTxt").fadeOut();
        data=[
            {"paylevel":"required|msg:Provide Pay level"},
            {"slab":"required|numeric|msg:Invalid Slab amount"},
        ];
        let validate = validatorJS(data);
        if(validate.status){
            $("#paylevelsForm").submit();
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
})