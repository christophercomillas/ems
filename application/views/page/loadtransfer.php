<div class="form-container">  
	<form method="POST" action="<?php echo base_url(); ?>transaction/loadtransfer" id="_loadtransfer">
		<input type="hidden" name="haserror" value="0" id="haserror">
		<div class="row">
			<div class="col-md-7">
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>From Sim Card<span class="ssimfrom"></span></label>
					<select class="form form-control inptxt input-md select-dialog" name="simfrom" id="simfrom" autofocus onchange="changeSimCardTransferFrom(this.value)">
						<option value="">- Select -</option>
						<?php foreach ($sim as $s): ?>
							<option value="<?php echo $s->scard_id; ?>"><?php echo $s->it_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>To Sim Card<span class="ssimto"></span></label>
					<select class="form form-control inptxt input-md select-dialog" name="simto" id="simto" onchange="changeSimCardTransferTo(this.value)">
						<option value="">- Select -</option>
						<?php foreach ($sim as $s): ?>
							<option value="<?php echo $s->scard_id; ?>"><?php echo $s->it_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="response-dial"></div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>Load Transfer</label>
					<input type="text" class="form form-control inpmedx" name="loadamt" id="loadamt" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="input-lg scan" name="data" autocomplete="off" maxlength="13">
				</div>
			</div>
			<div class="" style="display:none;">
				<input type="text" name="hidden" value="hidden">
			</div>

		</div>
	</form>
</div>
<script type="text/javascript">
	$('select#simfrom').focus();
	$('#loadamt').inputmask();
</script>