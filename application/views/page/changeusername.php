<div class="row">
	<form name="_changeusername" class="_changeusername" action="<?php echo base_url(); ?>user/changeUsernameValidation" method="post" id="_changeusername">
		<div class="col-xs-12 form-horizontal">
			<div class="input-daterange input-group" id="datepicker">
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c normalabel">Current Username</label>
					<div class="col-xs-7">
						<input type="text" class="form-control inpmed normal" name="curusername" id="curusername" value="<?php echo $this->session->userdata('load_username'); ?>" disabled>
					</div>				
				</div>
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c normalabel">New Username:</label>
					<div class="col-xs-7">
						<input type="text" class="form-control inpmed normal" name="username" id="username" autocomplete="off">
					</div>				
				</div>
			</div>
			<div class="response">

			</div>
		</div>
	</form>
</div>