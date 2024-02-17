<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/18/2022
 * Time: 1:43 PM
 */ ?>

<script>
    function calculate() {
        var intRate = {!! getSelfInterestRate() !!};
        var loanAmt = document.getElementById('loan_amount').value;
        var netSalary = document.getElementById('net_salary').value;
        var loanTenure = document.getElementById('tenure').value;
        let eligible = "";
        let loanLimiter = "";
        var adminFee =  0.05 * loanAmt;
        var insuranceFee =  0.025 * loanAmt;
        var appFee =  0.01 * loanAmt;

        var loanLimit =  3*netSalary;

        var installMent =  -1*pmt(intRate/100,loanTenure,loanAmt,0,0);

        if (netSalary*0.7 > installMent){
            eligible = "Pass";
        } else {
            eligible = "Fail";
        }

        if (loanAmt < loanLimit){
            loanLimiter = "Pass";
        } else {
            loanLimiter = "Fail";
        }

        var afterDeduction = netSalary - installMent;
        var netAfterCharges = loanAmt - adminFee - insuranceFee - appFee;
        var totalCharges = adminFee + insuranceFee + appFee;

        display(adminFee,insuranceFee, appFee,netAfterCharges,totalCharges,installMent,eligible,loanLimit,loanLimiter,afterDeduction);
    }

    function display(adminFee,insuranceFee, appFee, netAfterCharges,totalCharges, installMent, eligible,loanLimit, loanLimiter,afterDeduction){
        document.getElementById("admin_fee").value=parseFloat(adminFee).toFixed(2);
        document.getElementById("insurance_fee").value=parseFloat(insuranceFee).toFixed(2);
        document.getElementById("app_fee").value=parseFloat(appFee).toFixed(2);
        document.getElementById("net_after_charge").value=parseFloat(netAfterCharges).toFixed(2);
        document.getElementById("total_charges").value=parseFloat(totalCharges).toFixed(2);
        document.getElementById("monthly").value=parseFloat(installMent).toFixed(2);
        document.getElementById("eligibility").value=eligible;
        document.getElementById("loan_limit").value=parseFloat(loanLimit).toFixed(2);
        document.getElementById("limit_test").value=loanLimiter;
        document.getElementById("after_deduction").value=parseFloat(afterDeduction).toFixed(2);

    }

    function pmt(rate_per_period, number_of_payments, present_value, future_value, type){
        future_value = typeof future_value !== 'undefined' ? future_value : 0;
        type = typeof type !== 'undefined' ? type : 0;

        if(rate_per_period !== 0.0){
            // Interest rate exists
            var q = Math.pow(1 + rate_per_period, number_of_payments);
            return -(rate_per_period * (future_value + (q * present_value))) / ((-1 + q) * (1 + rate_per_period * (type)));

        } else if(number_of_payments !== 0.0){
            // No interest rate, but number of payments exists
            return -(future_value + present_value) / number_of_payments;
        }

        return 0;
    }

</script>
