<div class="form-container"> 
    <form method="POST" action="<?php echo base_url(); ?>item/saveSimcard" id="_addSimcard">	
        <input type="hidden" name="simcardid" value="">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="label-dialog"><span class="requiredf">*</span>Sim Card Number</label>
                    <input type="text" class="form form-control input-lg inp-b" id="simcardnum" name="simcardnum" autocomplete="off" autofocus maxlength="11">
                </div>
                <div class="form-group">
                    <label class="label-dialog"><span class="requiredf">*</span>Sim Card Type</label>
                    <select class="form form-control input-sm inp-b" id="simtype" name="simtype">
                        <option value="">- Select -</option>
                        <?php foreach ($itemdesc as $it): ?>
                            <option value="<?php echo $it->it_id; ?>"><?php echo ucwords($it->it_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>		
                <div class="form-group">
                    <label class="label-dialog"><span class="requiredf">*</span>Beg Bal</label>
                    <input type="text" class="form form-control input-sm inp-b" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="data" autocomplete="off" value="0.00" id="begbal" name="begbal" autocomplete="off">
                </div>
                <div class="response">
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $('input#simcardnum').select();
    $('#begbal').inputmask();
	//$('#srp,#netprice,#simcard,#loaddeduct,#begbalance').inputmask();
</script>