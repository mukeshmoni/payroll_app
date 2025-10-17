$(document).ready((e)=>{
    $(".drill_down_dept").click((e)=>{
        $dept = $(e.target).attr("data-id");
        $(e.target).toggleClass("ti-arrow-circle-right ti-arrow-circle-down");
        $("."+$dept).slideToggle();
    })
    $("#updateBtn").click(()=>{
        $.confirm({
            title: 'Update this Income Tax!',
            content: 'Are you sure you want to update?',
            type: 'green',
            typeAnimated: true,
            buttons: {
                confirm:{
                    btnClass: 'btn btn-success',
                    action:function(){
                        $(".spinner-body").fadeIn();
                        $("#incometaxForm").submit();
                    }
                },
                cancel:{
                    btnClass: 'btn btn-dark',
                    action:function(){
                        return true;
                        $(".spinner-body").fadeOut();
                    }
                },
            }
        });
    });
    $("#regime").change((e)=>{
        if($("#regime").val()=="old"){
            let sno=7
            $(".order_sno").each((index,ele)=>{
                $(ele).html(sno);
                sno++;
            })
            $(".old_regime").fadeIn();

            caculateHRAexempted();
        }else{
            $(".old_regime").fadeOut();
            let sno=7
            $(".order_sno").each((index,ele)=>{
                if($(ele).parent("tr").attr("class")!="old_regime"){
                    $(ele).html(sno);
                    sno++;
                }
            })
            calculateGross();
        }
    })

    function roundNearest10(num) {
        return Math.round(num / 10) * 10;
    }

    $(".add_income").keyup(()=>{
        calculateGross();
    });

    $(".less_1").keyup(()=>{
        calculateTotal_after_less_1();
    });

    $("#tax_rebate").keyup(()=>{
        calculateNetIncomeTax();
    })

    $("#health_cess").keyup(()=>{
        netamountTobededucted();
    })

    $("#already_deducted").keyup(()=>{
        balancetobeDeducted();
    })

    $("#hra_received,#rent_paid,#rent_calc").keyup((e)=>{
        caculateHRAexempted();
    })

    $("#prefessional_tax").keyup((e)=>{
        calculateTotal_after_less_2();
    })
    $(".less_3").keyup((e)=>{
        calculateTotal_after_less_3();
    })
    $(".savings_deduction,#nps_add").keyup((e)=>{
        calculateSavingsDeduction();
    })

    $(".rap_value").keyup((e)=>{
        calculateRAP();
    })

    const calculateGross = ()=>{
        let gross_income = 0;
        $(".add_income").each((index,ele)=>{
            gross_income = gross_income + +$(ele).val();
        })
        $("#gross_income").val(gross_income);
        
        calculateRAP();
       
    }

    const calculateRAP =()=>{
        let rap_total = 0;
        let gross_income = $("#gross_income").val();
        $(".rap_value").each((index,ele)=>{
            rap_total = rap_total + +$(ele).val();
        })

        $("#rap_total").val(rap_total);

        $("#gross_income_rap").val(+gross_income + +rap_total);
        calculateTotal_after_less_1();
    }
    const calculateTotal_after_less_1=()=>{
        let less_1 = 0
        $(".less_1").each((index,ele)=>{
            less_1 = less_1 + +$(ele).val();
        })
        let total_amount = $("#gross_income_rap").val() - less_1;
        $("#total_amount").val(total_amount);

        if($("#regime").val()=="new"){
            calculateTaxableIncome(total_amount);
        }else{
            calculateTotal_after_less_2()
        }
    }

    const calculateTotal_after_less_2=()=>{
        let hra_exempted = $("#hra_exempted").val();
        let prefessional_tax = $("#prefessional_tax").val();
        let deduct_total = hra_exempted + +prefessional_tax;
        let less_1 = 0
        $(".less_1").each((index,ele)=>{
            less_1 = less_1 + +$(ele).val();
        })
        let less = less_1 + +parseInt(deduct_total);
        let total_amount = $("#gross_income_rap").val() - less;
        $("#balance_after_pt").val(total_amount);

        calculateTotal_after_less_3();
    }

    const calculateTotal_after_less_3 =()=>{
        let less_3 = 0
        $(".less_3").each((index,ele)=>{
            less_3 = less_3 + +$(ele).val();
        })
        let total_amount = $("#balance_after_pt").val() - less_3;
        $("#total_deduction_1").val(less_3);
        $("#deduction_balance_1").val(total_amount);

        calculateSavingsDeduction()
    }

    const calculateSavingsDeduction=()=>{
        let savings = 0
        let balance_deduction = 0
        $(".savings_deduction").each((index,ele)=>{
            savings = savings + +$(ele).val();
        })
        $("#total_savings").val(savings);
        if(savings<=150000){
            $("#eligible_deduction").val(savings)
            balance_deduction =  $("#deduction_balance_1").val() - savings;
        }else{
            $("#eligible_deduction").val(150000)
            balance_deduction =  $("#deduction_balance_1").val() - 150000;
        }

        
        $("#deduction_balance_2").val(balance_deduction);

        balance_deduction = balance_deduction - $("#nps_add").val();

        $("#total_amount").val(balance_deduction);

        calculateTaxableIncome(balance_deduction);
    }

    const calculateTaxableIncome=(total_amount)=>{
        $("#income_tax_round").val(roundNearest10(total_amount));
        let income_tax = calculateIncomeTax(roundNearest10(total_amount))
        $("#income_tax").val(income_tax);

        calculateNetIncomeTax();
    }

    const calculateNetIncomeTax = ()=>{
        let $taxable_income = $("#income_tax_round").val();
        let income_tax = $("#income_tax").val();
        if($("#regime").val()=="new"){
            let rebate = 25000;
        if($taxable_income<=700000){
            $("#tax_rebate").val(rebate);
            net_income_tax = 0
            $("#net_income_tax").val(net_income_tax);
        }else{
            rebate_income = $taxable_income-700000;
            rebate = income_tax-rebate_income;
            if(rebate<0){
                rebate = 0
            }
            $("#tax_rebate").val(rebate);
            net_income_tax = income_tax-rebate
            $("#net_income_tax").val(net_income_tax);
        }
        }else{
            let rebate = 12500;
            if($taxable_income<=500000){
                $("#tax_rebate").val(rebate);
                net_income_tax = 0
                $("#net_income_tax").val(net_income_tax);
            }else{
                // rebate_income = $taxable_income-500000;
                // rebate = income_tax-rebate_income;
                // if(rebate<0){
                //     rebate = 0
                // }
                rebate = 0;
                $("#tax_rebate").val(rebate);
                net_income_tax = income_tax-rebate
                $("#net_income_tax").val(net_income_tax);
            }
        }
        calculateCess();
    }

    const calculateCess=()=>{
        let net_income_tax = $("#net_income_tax").val();
        let $taxable_income = $("#income_tax_round").val();
        let cess = 0;
        if($taxable_income>5000000){
            cess = ($taxable_income+($taxable_income*0.10))*0.04;
        }else{
            cess = net_income_tax*0.04;
        }
        $("#health_cess").val(Math.round(cess));

        netamountTobededucted();
    }

    const netamountTobededucted = ()=>{
        let net_income_tax = $("#net_income_tax").val();
        let cess = $("#health_cess").val();

        let netamount_to_be_deducted = +net_income_tax + +cess

        $("#amt_to_be_deducted").val(netamount_to_be_deducted);

        balancetobeDeducted();
    }

    const balancetobeDeducted =()=>{
        let net_amount_to_be_deducted = $("#amt_to_be_deducted").val();
        let it_paid = $("#already_deducted").val();
        let balance_to_be_paid = net_amount_to_be_deducted-it_paid
        if(balance_to_be_paid>0){
            $("#balance_to_be_deducted").val(balance_to_be_paid);
            let to_be_deducted = Math.round(balance_to_be_paid/4);
            $(".it_monthly_deduct").val(to_be_deducted);
            $("#total_month_deduction").val(balance_to_be_paid);
        }

    }

    const caculateHRAexempted = ()=>{
        let $hra_received = $("#hra_received").val();
        let $rent_paid = $("#rent_paid").val();
        let $rent_calc = $("#rent_calc").val();

        let hra_balance = ($rent_paid-$rent_calc>0)?$rent_paid-$rent_calc:0;
        
        $("#hra_balance").val(hra_balance);
        $("#hra_exempted").val(Math.min($hra_received,hra_balance));

        calculateTotal_after_less_2();
    }


    const calculateIncomeTax = (income)=>{

        if($("#regime").val()=="new"){
            if(income<=300000){
                return 0;
            }else if(income>300000 && income<=600000){
                return 0.05*(income-300000);
            }else if(income>600000 && income<=900000){
                return (0.10*(income-600000))+15000;
            }else if(income>900000 && income<=1200000){
                return (0.15*(income-900000))+45000;
            }else if(income>1200000 && income<=1500000){
                return (0.20*(income-1200000))+90000;
            }else{
                return (0.30*(income-1500000))+150000;
            }
        }else{
            let age = $("#age").val();
            if(income<=250000){
                return 0;
            }else if(income>250000 && income<=300000){
                if(age<60){
                    return 0.05*(income-250000);
                }else if(age>=60 && age<80){
                    return 0
                }else{
                    return 0
                }
            }else if(income>300000 && income<=500000){
                if(age<60){
                    return 0.05*(income-250000);
                }else if(age>=60 && age<80){
                    return 0.05*(income-300000);
                }else{
                    return 0
                }
            }else if(income>500000 && income<=750000){
                if(age<60){
                    return (0.2*(income-500000))+12500;
                }else if(age>=60 && age<80){
                    return (0.2*(income-500000))+10000;
                }else{
                    return 0.2*(income-500000);
                }
            }else if(income>750000 && income<=1000000){
                if(age<60){
                    return (0.2*(income-500000))+12500;
                }else if(age>=60 && age<80){
                    return (0.2*(income-500000))+10000;
                }else{
                    return 0.2*(income-500000);
                }
            }else if(income>1000000 && income<=1250000){
                if(age<60){
                    return (0.3*(income-1000000))+112500;
                }else if(age>=60 && age<80){
                    return (0.3*(income-1000000))+110000;
                }else{
                    return (0.3*(income-1000000))+100000;
                }
            }else if(income>1250000 && income<=1500000){
                if(age<60){
                    return (0.3*(income-1000000))+112500;
                }else if(age>=60 && age<80){
                    return (0.3*(income-1000000))+110000;
                }else{
                    return (0.3*(income-1000000))+100000;
                }
            }else{
                if(age<60){
                    return (0.3*(income-1000000))+112500;
                }else if(age>=60 && age<80){
                    return (0.3*(income-1000000))+110000;
                }else{
                    return (0.3*(income-1000000))+100000;
                }
            }



            
        }
    }

    calculateGross();
})