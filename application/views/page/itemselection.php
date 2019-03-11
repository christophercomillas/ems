
<div class="form-container">  
	<form method="POST" action="<?php echo base_url(); ?>transaction/addItemToCartSales" id="_additem">
		<div class="row">
			<div class="col-md-7">
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>Item Category</label>
					<select class="form form-control inptxt input-md select-dialog" name="itemtype" id="itemtype" onchange="changeItemType(this.value)">
						<option value="">- Select -</option>
						<?php foreach ($itemtype as $it): ?>
							<option value="<?php echo $it->ity_id; ?>"><?php echo $it->ity_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>Item Name</label>
					<select class="form form-control inptxt input-md select-dialog" name="item" id="item" disabled onchange="changeItemsItemSelectionSales(this.value)">
						<option value="">-Select-</option>
					</select>

				</div>
				<div class="response-dial"></div>
			</div>
			<div class="col-md-5">
				<div class="eloadiv">
					<div class="form-group">
						<label class="label-dialog"><span class="reqspan">*</span>Load Amount</label>
						<input type="text" class="form form-control inpmedx" onkeyup="nLoadAmt(this.value);" name="loadamt" id="loadamt" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="input-lg scan" name="data" autocomplete="off" maxlength="13" autofocus="">
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="reqspan">*</span>Mobile Number</label>
						<input type="text" class="form form-control inpmedx" name="mobnum" id="mobnum" autocomplete="off" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="input-lg scan" name="data" autocomplete="off" maxlength="13" autofocus="">
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="reqspan">*</span>Load Deduction</label>
						<input type="text" class="form form-control inpmedx" name="loaddeduct" id="loaddeduct" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="input-lg scan" name="data" autocomplete="off" maxlength="13" autofocus="">
					</div>
					<div class="form-group">
						<label class="label-dialog"><span class="reqspan">*</span>Reference #</label>
						<input type="text" class="form form-control inpmedx" name="loadref" id="loadref" autocomplete="off">
					</div>
				</div>
				<div class="noteload">
					<div class="form-group">
						<label class="label-dialog"><span class="reqspan">*</span>Qty</label>
						<input type="text" class="form form-control inpmedx" name="qty" id="nitems" onkeyup="nItemsSales(this.value);" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="input-lg scan" name="data" autocomplete="off" maxlength="13" autofocus="">
					</div>
					<div class="form-group">
						<label class="label-dialog">Net Price</label>
						<input type="text" class="form form-control inpmedx" id="netprice" disabled>
					</div>
					<div class="form-group">
						<label class="label-dialog">SRP</label>
						<input type="text" class="form form-control inpmedx" id="srp" disabled>
					</div>
					<div class="form-group">
						<label class="label-dialog">Total</label>
						<input type="text" class="form form-control inpmedx" value="0" id="totalsrp" disabled>
					</div>					
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$('#nitems,#loadamt,#mobnum,#loaddeduct').inputmask();
</script>