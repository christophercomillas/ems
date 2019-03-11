<div class="row">
	<form name="_changepassword" class="_changepassword" action="<?php echo base_url(); ?>user/changePasswordValidation" method="post" id="_changepassword">
		<div class="col-xs-12 form-horizontal">
			<div class="input-daterange input-group" id="datepicker">
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c normalabel">Old Password</label>
					<div class="col-xs-7">
						<input type="password" class="form-control inpmed normal" name="opassword" id="opassword">
					</div>				
				</div>
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c normalabel">New Password:</label>
					<div class="col-xs-7">
						<input type="password" class="form-control inpmed normal" name="npassword" id="npassword" autocomplete="off">
					</div>				
				</div>
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c normalabel">Confirm New Password:</label>
					<div class="col-xs-7">
						<input type="password" class="form-control inpmed normal" name="cpassword" id="cpassword" autocomplete="off">
					</div>				
				</div>
			</div>
			<div class="response">
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$('#opassword').focus();
</script>