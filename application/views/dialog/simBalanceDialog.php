<div class="row">
    <div class="form-horizontal"> 
        <div class="col-md-12">
            <form method="POST" id="_simBalance">
                <?php foreach($sims as $sim): ?>
                    <div class="form-group">
                        <label class="control-label col-xs-4 lbl-c normalabel"><?php echo $sim->scard_number; ?></label>
                        <div class="col-xs-8">
                            <input type="text" class="form-control inpmed normal balance" name="sim-<?php echo $sim->scard_id; ?>" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" autocomplete="off">
                        </div>				
                    </div>
                <?php endforeach; ?>
            </form>

        </div>
    </div>
</div>
<script type="text/javascript">
	$('.balance').inputmask();
</script>
