<div class="form-container"> 
	<form method="POST" action="<?php echo base_url(); ?>user/changeUserPassword" id="_changeUserPassword">
		<input type="hidden" id="userid" name="userid" value="<?php echo $id; ?>">
		<div style="display:none; ">
		<input type="text" class="form form-control inpmedx input-sm" id="password1" name="password1">
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label-dialog">Fullname</label>
					<input type="text" class="form form-control input-sm" id="fullname" name="fullname" value="<?php echo ucwords($fullname); ?>" autocomplete="off" readonly="readonly">
				</div>
				<div class="form-group">
					<label class="label-dialog"><span class="requiredf">*</span>Password</label>
					<div class="input-group">
                        <input type="text" class="form form-control inpmedx input-sm" id="password" name="password" autocomplete="off">
                        <span class="input-group-btn">
                        	<button class="btn btn-default input-sm btn-find upass" type="button" id="generatePassword">
                            	<i class="fa fa-cogs" aria-hidden="true"></i>
                        	</button>
                        </span>                        
                    </div>
				</div>
				<div class="response">
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$('input#password').select();
</script>
