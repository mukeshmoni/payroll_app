
    function validateSection(sec){
        $(".alert").fadeOut();
        $(".form-control").removeClass("is-invalid");
        switch (sec) {
            case 1:
                data=[
                    {"empname":"required|msg:Employee name is required"},
                    {"fathername":"required|msg:Father's name is required"},
                    {"mothername":"required|msg:Mother's name is required"},
                    {"empdob":"required|date|msg:Date of Birth is required"},
                    {"empgender":"required|msg:Gender is required"},
                    {"maritalstatus":"required|msg:Marital status is required"},
                    {"empcontact":"required|numeric|min:10|max:15|msg:Provide valid contact number"},
                    {"emppanno":"required|alphanumeric|min:10|max:15|msg:Provide valid PAN number"},
                    {"empaadhaarno":"required|numeric|len:12|msg:Provide valid Aadhaar number"},
                    {"empaddress":"required|min:10|msg:Address is invalid"},
                    {"empstate":"required|msg:State is required"},
                    {"empcity":"required|msg:City is required"},
                    {"pincode":"required|numeric|msg:Pincode is required"},
                ];
                break;
            case 2:
                data=[
                    {"empdoj":"required|date|msg:Invalid Date of Joining"},
                    {"designation":"required|msg:Select Proper Designation"},
                    {"department":"required|msg:Select Proper Department"},
                    {"category":"required|msg:Select Proper Category"},
                    {"bankname":"required|msg:Provide valid Bank Name"},
                    {"empaccno":"required|numeric|msg:Invalid Account Number"},
                    {"center":"required|msg:Select Proper Center"},
                    {"pf_nps_cat":"required|msg:Select valid Category"},
                ];
                break;
            case 3:
                data=[
                    {"prev_exp":"required|msg:Select Experience"},
                    {"prevorgname":"required?prev_exp:yes|msg:Provide Previous Organisation name"},
                ];
                break;
            case 4:
                data=[
                    {"quarters":"required|msg:Select proper option"},
                    {"quartersno":"required?quarters:yes|msg:Provide Quarters Number"},
                    {"doccupied":"required?quarters:yes|msg:Select Date of Occupied"},
                    {"eligiblehra":"required|msg:Select Yes/No"},
                    {"handicap":"required|msg:Select Yes/No"},
                    {"prnop":"required|msg:Select Proper Option"},
                ];
                break;
            default:
                break;
        }
        let validate = validatorJS(data);
        if(validate.status){
            return true;
        }else{
            let errors = validate.errors;
            console.log(errors);
            errors.forEach(error=>{
                Object.keys(error).forEach(id=>{
                    $("#"+id).addClass("is-invalid");
                    $("#"+id+"_err").html(error[id]).fadeIn();
                })
            });
        }
    }

    function getCities(e,cities){
        console.log("hello");
        const state_id = $(e.target).val();
        let opt="";
        if(cities[state_id].length>0){
            cities[state_id].forEach(city => {
                opt+="<option value="+city[0]+">"+city[1]+"</option>"
            });
        }
        return opt;
    }

    //section navigator count manage
    let section = 1;
    $(".saveNext").click((e)=>{
        saveEmployeeData();
        // if(validateSection(section)){
            $(".section-"+section).addClass("visited");
            section++;
            $(".spinner-body").show();
            if(section>4){
                $("#EmployeeForm").submit();
            }else{
                setTimeout(() => {
                    $(".section-forms").fadeOut();
                    $(".section-"+section+"-form").fadeIn();
                    $(".spinner-body").hide();
                    $(".section-"+section).addClass("active");
                }, 500);
            }
            
        // }
        
    })
    $(".goBack").click((e)=>{
        $(".section-"+section).removeClass(["visited","active"]);
        section--;
        $(".spinner-body").show();
        setTimeout(() => {
            $(".section-forms").fadeOut();
            $(".section-"+section+"-form").fadeIn();
            $(".spinner-body").hide();
            $(".section-"+section).addClass("active");
        }, 500);
    })

    $(".saveBtn").click((e)=>{
        saveEmployeeData();
    })

    function saveEmployeeData(){
        let formValues = $('#EmployeeForm').serializeArray();
        sessionStorage.setItem("saved_employee_details",JSON.stringify(formValues));
    }
      
    const section1 = ['empname','fathername','mothername','empdob','empgender','maritalstatus','empcontact','emppanno','empaadhaarno','empaddress','empstate','empcity','pincode'];
    const section2 = ['empid','empdoj','designation','department','category','bankname','empaccno',"center","pf_nps_cat"];
    const section3 = ['prev_exp','prevorgname'];
    const section4 = ['quarters','quartersno','doccupied','eligiblehra','handicap','prnop']

    function changeSectionToshowErrors(errors){
        for (let index = 0; index < errors.length; index++) {
            let error = errors[index];
            if(section1.includes(error)){
                section = 1;
                break;
            }else if(section2.includes(error)){
                section = 2;
                break;
            }else if(section3.includes(error)){
                section = 3;
                break;
            }else if(section4.includes(error)){
                section = 4;
                break;
            }
        };
        $(".spinner-body").show();
        setTimeout(() => {
            $(".section-forms").fadeOut();
            $(".section-"+section+"-form").fadeIn();
            $(".spinner-body").hide();
            $(".section-"+section).addClass("active");
            for(i = 1;i<=section;i++){
                $(".section-"+i).addClass("visited");
            }
        }, 500);
    };
