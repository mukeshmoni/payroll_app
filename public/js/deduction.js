//category submit form
$("#categorySubmitForm").click((e)=>{
    e.preventDefault();
    data=[
        {"deduction_name":"required|msg:Deduction name is required"},
        {"deduction_type_name":"required|msg:Deduction type name is required"},
        {"mode":"required|msg:Mode is required"},
        {"mode_value":"required|numeric|msg:Mode value is required"},
        {"tax_amount":"required?taxability:1|numeric|msg:Tax percentage is required"}
    ];
    let validate = validatorJS(data);
    if(validate.status){
        $("#deductionCategoryForm").submit();
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
