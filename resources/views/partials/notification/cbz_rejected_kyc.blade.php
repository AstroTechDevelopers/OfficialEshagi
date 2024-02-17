<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 15/2/2021
 * Time: 04:22
 */
?>
<li class="dropdown-item notify-item" id="markasread" onclick="markNotificationAsRead()">
    <a href="{{url('view-kyc/'.$notification->data['kyc']['id'])}}">{{$notification->data['kyc']['cbz_evaluator']}} has rejected the KYC for {{$notification->data['kyc']['natid']}}. </a>
</li>
