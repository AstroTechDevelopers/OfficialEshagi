<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/15/2020
 *Time: 11:39 PM
 */

?>
<li class="dropdown-item notify-item" id="markasread" onclick="markNotificationAsRead()">
    <a href="{{url('loan-details/'.$notification->data['loanUnpaid']['id'])}}">Loan ID: {{$notification->data['loanUnpaid']['id']}} disbursement has been reported to be delayed. </a>
</li>
