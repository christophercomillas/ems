<div class="row">
	<div class="col-xs-12 form-horizontal">
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-5 lbl-c normalabel">Cashier: </label>
			<div class="col-xs-7">
				<input type="text" class="form-control inpmed normal" readonly="readonly" tabIndex="-1" value="<?php echo ucwords($this->session->userdata('load_fullname')); ?>">
			</div>
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-5 lbl-c normalabel">Last Login Date & Time: </label>
			<div class="col-xs-7">
				<input type="text" class="form-control inpmed normal" readonly="readonly" tabIndex="-1" value="">
			</div>			
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-12 lbl-c normalabel">Process cashier end of shift report?</label>		
		</div>
		<div class="response">
		</div>
	</div>
</div>