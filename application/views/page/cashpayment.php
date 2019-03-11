<div class="form-container">  
	<form method="POST" action="<?php echo base_url(); ?>transaction/cashpaymentdetails" id="_cashpayment">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>Amount Tender</label>
					<input type="text" id="amttender" class="form form-control inpmedx paycash"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false">
				</div>
				<div class="form-group">
					<label class="label-dialog"><span class="reqspan">*</span>Change</label>
					<input type="text" class="form form-control inpmedx" id="change" disabled value="0">
				</div>
			</div>
		</div>
		<div class="response-dialog">
		</div>
	</form>
</div>
<script type="text/javascript">
	$('#amttender').focus();
	$('#amttender').inputmask();
</script>