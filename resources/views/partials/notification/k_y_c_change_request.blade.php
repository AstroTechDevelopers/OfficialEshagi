<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/20/2020
 *Time: 2:10 PM
 */

?>
<li class="dropdown-item notify-item" id="markasread" onclick="markNotificationAsRead()">
    <a href="{{url('kycchanges/'.$notification->data['kycChange']['id'])}}">{{$notification->data['kycChange']['natid']}} has submitted a KYC Change Request.</a>
</li>
