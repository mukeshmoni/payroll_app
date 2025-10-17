function validatorJS(data=[]){
    const $_response = {
        "status":true,
        "errors":[],
    };
    data.forEach(ele => {
        Object.keys(ele).forEach(key => {
            let $_id = $("#"+key).val();
            let $_error = [];
            let $_conds = ele[key].split("|");
            if(ele[key].includes("msg:")){
                $_msg = ele[key].split("msg:")[1];
            }else{
                $_msg="";
            }
            
            $_conds.forEach(cond=>{
                
                switch (cond) {
                    case "required":
                        if($_id=="" || $_id==null){
                            if($_msg==""){
                                $_msg=key+" is required";
                            }
                            $_response.status=false;
                            tempObj={};
                            tempObj[key]=$_msg;
                            $_response.errors.push(tempObj);
                            break;
                        }
                        break;
                    case "char":
                        if(!(/^[A-Za-z\s]*$/.test($_id))){
                            if($_msg==""){
                                $_msg=key+" should contain characters only";
                            }
                            $_response.status=false;
                            tempObj={};
                            tempObj[key]=$_msg;
                            $_response.errors.push(tempObj);
                            break;
                        }
                        break;
                    case "date":
                        if(isNaN(new Date($_id))){
                            if($_msg==""){
                                $_msg=key+" has invalid date format";
                            }
                            $_response.status=false;
                            tempObj={};
                            tempObj[key]=$_msg;
                            $_response.errors.push(tempObj);
                            break;
                        }
                        break;
                    case "numeric":
                        if(isNaN($_id)){
                            if($_msg==""){
                                $_msg=key+" should be numeric value";
                            }
                            $_response.status=false;
                            tempObj={};
                            tempObj[key]=$_msg;
                            $_response.errors.push(tempObj);
                            break;
                        }
                        break;
                    case "alphanumeric":
                        if(!(/^[a-zA-Z0-9-,()\s]+$/.test($_id))){
                            if($_msg==""){
                                $_msg=key+" should contain alpha numeric only";
                            }
                            $_response.status=false;
                            tempObj={};
                            tempObj[key]=$_msg;
                            $_response.errors.push(tempObj);
                            break;
                        }
                        break;
                    case "email":
                        if(!(/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/.test($_id))){
                            if($_msg==""){
                                $_msg=key+" is not valid";
                            }
                            $_response.status=false;
                            tempObj={};
                            tempObj[key]=$_msg;
                            $_response.errors.push(tempObj);
                            break;
                        }
                        break;
                    default:
                        if(cond.includes("min:")){
                            $_min = cond.split("min:")[1];
                            if($_id.length<$_min){
                                if($_msg==""){
                                    $_msg=key+" should contain minimum "+$_min+" characters";
                                }
                                $_response.status=false;
                                tempObj={};
                                tempObj[key]=$_msg;
                                $_response.errors.push(tempObj);
                                break;
                            }
                        }
                        if(cond.includes("max:")){
                            $_max = cond.split("max:")[1];
                            if($_id.length>$_max){
                                if($_msg==""){
                                    $_msg=key+" should contain maximum "+$_max+" characters";
                                }
                                $_response.status=false;
                                tempObj={};
                                tempObj[key]=$_msg;
                                $_response.errors.push(tempObj);
                                break;
                            }
                        }
                        if(cond.includes("required?")){
                            $temp = cond.split("?")[1];
                            $parent_id = $temp.split(":");
                            $parent_val = $("#"+$parent_id[0]).val();
                            if($parent_id[1]!=""){
                                if($parent_val == $parent_id[1]){
                                    if($_id=="" || $_id==null){
                                        if($_msg==""){
                                            $_msg=key+" is required";
                                        }
                                        $_response.status=false;
                                        tempObj={};
                                        tempObj[key]=$_msg;
                                        $_response.errors.push(tempObj);
                                        break;
                                    }
                                }
                            }else{
                                if($parent_val!="" && $parent_val!=null){
                                    if($_id=="" || $_id==null){
                                        if($_msg==""){
                                            $_msg=key+" is required";
                                        }
                                        $_response.status=false;
                                        tempObj={};
                                        tempObj[key]=$_msg;
                                        $_response.errors.push(tempObj);
                                        break;
                                    }
                                }
                            }
                        }
                        break;
                }
            });
        });
    });
    return $_response
}

function printDiv(id){
    $("#"+id).printThis({
        debug: false,                   // show the iframe for debugging
        importCSS: true,                // import parent page css
        importStyle: true,             // import style tags
        printContainer: true,           // grab outer container as well as the contents of the selector
        pageTitle: "",                  // add title to print page
        removeInline: false,            // remove all inline styles from print elements
        removeInlineSelector: "body *", // custom selectors to filter inline styles. removeInline must be true
        printDelay: 333,                // variable print delay
        header: null,                   // prefix to html
        footer: null,                   // postfix to html
        base: false,                    // preserve the BASE tag, or accept a string for the URL
        formValues: true,               // preserve input/form values
        canvas: false,                  // copy canvas elements
        doctypeString: '',           // enter a different doctype for older markup
        removeScripts: false,           // remove script tags from print content
        copyTagClasses: true,           // copy classes from the html & body tag
        beforePrintEvent: null,         // callback function for printEvent in iframe
        beforePrint: null,              // function called before iframe is filled
        afterPrint: null                // function called before iframe is removed
    });
}

$(document).ready((e)=>{
    $("select").change((e)=>{
        if(e.target.value!=""){
            $(e.target).addClass("select-input-selected")
        }else{
            $(e.target).removeClass("select-input-selected")
        }
    })
    $("select").each((index,e)=>{
        if($(e).val()!=""){
            $(e).addClass("select-input-selected")
        }else{
            $(e).removeClass("select-input-selected")
        }
    })
    // $('input[data-type=month]').datepicker({
    //     changeMonth: true,
    //     changeYear: true,
    //     showButtonPanel: true,
    //     dateFormat: 'MM-yy',
    //     onClose: function(dateText, inst) { 
    //         $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    //     }
    //     });
})