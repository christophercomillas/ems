<div class="form-container"> 
	<form method="POST" action="<?php echo base_url(); ?>transaction/changetransactionDate" id="_changeTRDate">	
		<input type="hidden" name="trid" value="<?php echo $salestrid; ?>">
		<div class="row">
            <div class="col-md-12">
                <div class="input-group input-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" data-dateid="" autocomplete="off" class="form-control pull-right datepickers datep" id="datepickers" name="date_c">
                </div>
				<div class="checkbox">
				 	<label><input type="checkbox" name="eod" value="1">EOD</label>
				</div>
                <div class="" style="display:none;">
                	<input type="text" name="dates">
                </div>
                <div class="response-dialog">
                </div>
            </div>
		</div>
	</form>
</div>
<script type="text/javascript">
    $('.datepickers').datepicker({
    	autoclose: true
    });
</script>


