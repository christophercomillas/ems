<div class="row">
	<div class="col-xs-12 form-horizontal">
		<div class="input-daterange input-group" id="datepicker">
			<div class="form-group fg-nobot">
				<label class="control-label col-xs-4 lbl-c normalabel">Date Start</label>
				<div class="col-xs-8">
					<input type="text" class="form-control inpmed normal" name="start" id="start">
				</div>				
			</div>
			<div class="form-group fg-nobot">
				<label class="control-label col-xs-4 lbl-c normalabel">Date End</label>
				<div class="col-xs-8">
					<input type="text" class="form-control inpmed normal" name="end" id="end">
				</div>				
			</div>
			<div class="form-group fg-nobot">
				<label class="control-label col-xs-4 lbl-c normalabel">Select</label>
				<div class="col-xs-8">
					<select class="form-control inpmed normal" name="trans">
						<option value="1">Cashier Transactions</option>
						<option value="2">All Transactions</option>
					</select>					
				</div>				
			</div>
		</div>
		<div class="response">
		</div>
	</div>
	</div>
</div>

<script type="text/javascript">
	$("#start, #end").inputmask("m/d/y",{ "placeholder": "mm/dd/yyyy" });
	$('#start').focus();
	// $('.input-daterange').datepicker({
	//     endDate: "+Infinity",
	//     todayBtn: "linked",
	//     autoclose: true
	// });
</script>