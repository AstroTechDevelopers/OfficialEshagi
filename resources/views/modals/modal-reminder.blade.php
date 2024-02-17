<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 17/2/2021
 * Time: 19:59
 */
?>
<div class="modal fade" id="setReminder" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Set Appointment Date & Time
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div>
                    <label>Inline Datepicker</label>
                    <input type="text" name="appointment" class="form-control datepicker-here{{ $errors->has('appointment') ? ' is-invalid' : '' }}" data-timepicker="true" data-language="en" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>
