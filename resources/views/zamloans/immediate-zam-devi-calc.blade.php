<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/11/2022
 * Time: 5:26 AM
 */ ?>
<script>
    function calculate() {
        var intRate = {!! getDeviceInterestRate() !!};
        var loanAmt = document.getElementById('loan_principal_amount').value;
        var loanTenure = document.getElementById('loan_duration').value;
        var gross_amount = loanAmt/0.95;

        var installMent =  -1*pmt(intRate/100,loanTenure,gross_amount,0,0);

        var netSalary = document.getElementById('net_salary').value;
        var grossSalary = document.getElementById('gross_salary').value;

        var adminFee =  0.05 * loanAmt;
        var insuranceFee =  0.025 * loanAmt;
        var appFee =  0.01 * loanAmt;
        var totalCharges = adminFee + insuranceFee + appFee;
        var netAfterCharges = loanAmt + totalCharges;


        var bt = document.getElementById('btSubmit');
        let eligible = "";
        let loanLimiter = "";

        var loanLimit =  3*netSalary;

        var deductions = parseFloat(netSalary) + parseFloat(installMent);
        var lawAllowed = 0.6*parseFloat(grossSalary);

        if (parseFloat(deductions.toFixed(2)) < parseFloat(lawAllowed.toFixed(2))){
            eligible = "Pass";
        } else {
            eligible = "Fail";
        }

        if (loanAmt < loanLimit){
            loanLimiter = "Pass";
        } else {
            loanLimiter = "Fail";
        }

        if (eligible === "Pass") {
            bt.disabled = false;
        } else {
            bt.disabled = true;
        }

        var afterDeduction = netSalary - installMent;

        display(adminFee, insuranceFee, appFee,netAfterCharges,totalCharges,installMent,eligible,loanLimit,loanLimiter,afterDeduction,lawAllowed, deductions);
    }

    function display(adminFee, insuranceFee, appFee,netAfterCharges,totalCharges, installMent, eligible,loanLimit, loanLimiter,afterDeduction,lawAllowed, deductions){
        document.getElementById("admin_fee").value=parseFloat(adminFee).toFixed(2);
        document.getElementById("insurance_fee").value=parseFloat(insuranceFee).toFixed(2);
        document.getElementById("app_fee").value=parseFloat(appFee).toFixed(2);
        document.getElementById("net_after_charge").value=parseFloat(netAfterCharges).toFixed(2);
        document.getElementById("total_charges").value=parseFloat(totalCharges).toFixed(2);
        document.getElementById("monthly").value=parseFloat(installMent).toFixed(2);
        document.getElementById("cf_11353_installment").value=parseFloat(installMent).toFixed(2);
        document.getElementById("eligibility").value=eligible;
        document.getElementById("loan_limit").value=parseFloat(loanLimit).toFixed(2);
        document.getElementById("limit_test").value=loanLimiter;
        document.getElementById("sixty_gross").value=parseFloat(lawAllowed).toFixed(2);
        document.getElementById("tot_deductions").value=parseFloat(deductions).toFixed(2);

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
