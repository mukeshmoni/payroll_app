$(document).ready(()=>{
    $("#submitForm").click((e)=>{
        e.preventDefault();
        $(".errorTxt").fadeOut();
        data=[
            {"center":"required|msg:Provide Center Name"},
            {"hra_perc":"required|numeric|msg:Enter valid HRA %"},
        ];
        let validate = validatorJS(data);
        if(validate.status){
            $("#centersForm").submit();
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