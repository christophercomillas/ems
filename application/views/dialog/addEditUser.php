<div class="form-container"> 
	<?php if($mode==1): ?>
		<form method="POST" action="<?php echo base_url(); ?>user/updateUser" id="_updateUser">
			<?php foreach ($userinfo as $u): ?>			

				<input type="hidden" name="userid" value="<?php echo $u->u_id; ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>Username</label>
							<input type="text" class="form form-control input-sm inp-b" value="<?php echo $u->u_username; ?>" id="username" name="username" autocomplete="off" autofocus>
						</div>
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>ID Number</label>
							<input type="text" class="form form-control input-sm inp-b" value="<?php echo $u->u_idnumber; ?>" id="idnumber" name="idnumber" autocomplete="off">
						</div>
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>Fullname <small class="namesm">(Firstname middle initial Lastname)</small></label>
							<input type="text" class="form form-control input-sm inp-b" value="<?php echo $u->u_fullname; ?>" id="fullname" name="fullname" autocomplete="off" >
						</div>
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>Business Unit</label>
							<select class="form form-control input-sm inp-b" id="bunit" name="bunit">
								<option value=""
									<?php 
										if($u->u_bu=="")
										{
											echo 'selected';
										}
									?>
								>- Select -</option>
								<?php foreach ($bu as $b): ?>

									<option value="<?php echo $b->bu_id; ?>" 
										<?php 
											if($b->bu_id == $u->u_bu)
											{
												echo 'selected';
											} 
										?>
									><?php echo $b->bu_name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>Department</label>
							<select class="form form-control input-sm inp-b" id="department" name="department">
							<?php foreach ($dept as $d): ?>
								<option value="<?php echo $d->dept_id; ?>"

									<?php 
										if($d->dept_id == $u->u_department)
										{
											echo 'selected';
										} 
									?>

								><?php echo $d->dept_name; ?></option>
							<?php endforeach; ?>
<!-- 								<option value="">- Select -</option>
								<option value="admin" <?php echo $u->u_department =='admin' ? 'selected' : '' ?>>Admin</option>
								<option value="retail store" <?php echo $u->u_department =='retail store' ? 'selected' : '' ?>>Retail Store</option>
								<option value="accounting" <?php echo $u->u_department =='accounting' ? 'selected' : '' ?>>Accounting</option> -->
							</select>
						</div>
						<div class="form-group">
							<label class="label-dialog">IP Address <small class="namesm">(Optional)</small></label>
							<input type="text" class="form form-control input-sm inp-b" value="<?php echo $u->u_ipaddress; ?>" id="ipaddress" name="ipaddress" autocomplete="off">
						</div>
						<div class="response">
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</form>
	<?php else: ?>
		<form method="POST" action="<?php echo base_url(); ?>user/saveUser" id="_saveUser">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>Username</label>
						<input type="text" class="form form-control input-sm inp-b" id="username" name="username" autocomplete="off" autofocus>
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>ID Number</label>
						<input type="text" class="form form-control input-sm inp-b" id="idnumber" name="idnumber" autocomplete="off">
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>Fullname <small class="namesm">(Firstname middle initial Lastname)</small></label>
						<input type="text" class="form form-control input-sm inp-b" id="fullname" name="fullname" autocomplete="off" >
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>Business Unit</label>
						<select class="form form-control input-sm inp-b" id="bunit" name="bunit">
							<option value="">- Select -</option>
							<?php foreach ($bu as $b): ?>
								<option value="<?php echo $b->bu_id; ?>"><?php echo $b->bu_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>Department</label>
						<select class="form form-control input-sm inp-b" id="department" name="department">
							<option value="">- Select -</option>
							<?php foreach ($dept as $d): ?>
								<option value="<?php echo $d->dept_id; ?>"><?php echo $d->dept_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>Password</label>
						<div class="input-group">
	                        <input type="text" class="form form-control inpmedx input-sm inp-b" id="password" name="password">
	                        <span class="input-group-btn">
	                        	<button class="btn btn-default input-sm btn-find upass" type="button" id="generatePassword">
	                            	<i class="fa fa-cogs" aria-hidden="true"></i>
	                        	</button>
	                        </span>
	                    </div>
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="requiredf">*</span>Status</label>
						<select class="form form-control input-sm inp-b" id="status" name="status">
							<option value="">- Select -</option>
							<option value="active">Active</option>
							<option value="active">Inactive</option>
						</select>
					</div>
					<div class="form-group">
						<label class="label-dialog">IP Address <small class="namesm">(Optional)</small></label>
						<input type="text" class="form form-control input-sm inp-b" id="ipaddress" name="ipaddress" autocomplete="off">
					</div>
					<div class="response">
					</div>
				</div>
			</div>
		</form>
	<?php endif; ?>
</div>
<script type="text/javascript">
	$('input#username').select();
</script>
