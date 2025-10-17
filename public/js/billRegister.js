//category submit form
$("#brSubmitForm").click((e)=>{
    e.preventDefault();
    data=[
        {"bill_date":"required|msg:Bill Date is required"},
        {"particulars":"required|msg:Particulars is required"},
        {"amount":"required|msg:Amount is required"},
        {"name_of_clerk":"required|msg:Name of Clerk is required"},
        {"received_from":"required|msg:Received From is required"},
    ];
    let validate = validatorJS(data);
    if(validate.status){
        $("#billRegisterForm").submit();
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
