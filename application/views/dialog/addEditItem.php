<div class="form-container"> 
    <?php if($mode==1): ?>
        <form method="POST" action="<?php echo base_url(); ?>item/updateItem" id="_updateItem">	
            <input type="hidden" name="itemid" value="<?php echo $itemdetails->it_id; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Item Name</label>
                        <input type="text" class="form form-control input-sm inp-b" id="itemname" name="itemname" autocomplete="off" value="<?php echo $itemdetails->it_name; ?>" autofocus>
                    </div>
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Item Type</label>
                        <select class="form form-control input-sm inp-b" id="itemtypeadd" name="itemtype">
                            <?php foreach ($itemtype as $it): ?>
                                <option value="<?php echo $it->ity_id; ?>" <?php echo $it->ity_id == $itemdetails->it_type ? "selected" :""?>><?php echo ucwords($it->ity_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>			
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Fad Item Code</label>
                        <input type="text" class="form form-control input-sm inp-b" id="faditemcode" name="faditemcode" value="<?php echo $itemdetails->it_fad_itemcode; ?>" autocomplete="off" autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>SRP</label>
                        <input type="text" class="form form-control input-sm inp-b" id="srp" name="srp" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" autocomplete="off" value="<?php echo $itemdetails->it_srp; ?>" <?php echo $itemdetails->it_type == '1' ? 'disabled' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Net Price</label>
                        <input type="text" class="form form-control input-sm inp-b" id="netprice" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="netprice" autocomplete="off" value="<?php echo $itemdetails->it_netprice; ?>" <?php echo $itemdetails->it_type == '1' ? 'disabled' : '' ?>>
                    </div>				
                    
                    <div class="response">
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <form method="POST" action="<?php echo base_url(); ?>item/saveItem" id="_addItem">	
            <input type="hidden" name="userid" value="">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Item Name</label>
                        <input type="text" class="form form-control input-sm inp-b" id="itemname" name="itemname" autocomplete="off" autofocus>
                    </div>
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Item Type</label>
                        <select class="form form-control input-sm inp-b" id="itemtypeadd" name="itemtype">
                            <option value="">- Select -</option>
                            <?php foreach ($itemtype as $it): ?>
                                <option value="<?php echo $it->ity_id; ?>"><?php echo ucwords($it->ity_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>			
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Fad Item Code</label>
                        <input type="text" class="form form-control input-sm inp-b" value="" id="faditemcode" name="faditemcode" autocomplete="off" autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>SRP</label>
                        <input type="text" class="form form-control input-sm inp-b" value="" id="srp" name="srp" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" autocomplete="off" disabled>
                    </div>
                    <div class="form-group">
                        <label class="label-dialog"><span class="requiredf">*</span>Net Price</label>
                        <input type="text" class="form form-control input-sm inp-b" value="" id="netprice" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="netprice" autocomplete="off" disabled>
                    </div>				
                    
                    <div class="response">
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<script type="text/javascript">
	$('input#itemname').select();
	$('#srp,#netprice,#simcard,#loaddeduct,#begbalance').inputmask();
</script>