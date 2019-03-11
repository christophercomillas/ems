<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Update Load Details
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12">                
                <div class="box box-primary">
                    <div class="box-body form-horizontal">
                        <div class="col-sm-12">
                        <?php if(!$hasquery): ?>

                            <h4>Empty Result</h4>

                        <?php
                        else:

                            if(count($results) > 0):

                                $grandtotal = 0;
                                foreach ($results as $r => $value):
                        ?>
                                    <div class="row row-bot-s">
                                    <div class="trwrap wrap<?php echo $value['trans_id']; ?>" data-id="<?php echo $value['trans_id']; ?>">                                
                                        <div class="form-group">
                                            <label class="col-md-2">Transaction #: <span class="span<?php echo $value['trans_id']; ?>"><?php echo $value['trans_num']; ?></span></label>
                                            <label class="col-md-3">Date: <span class="sp<?php echo $value['trans_id']; ?>"><?php echo _dateFormat($value['trans_datetime']); ?></span></label>
                                            <label class="col-md-3">Store: <?php echo ucwords($value['trans_store']); ?></label>
                                            <label class="col-md-3">Cashier: <?php echo ucwords($value['trans_cashier']); ?></label>
                                        </div>    
                                        <div class="form-group">
                                            <div class="col-sm-3">Item</div>
                                            <div class="col-sm-1">Quantity</div>
                                            <div class="col-sm-3">Load Type</div>
                                            <div class="col-sm-2">Net</div>
                                            <div class="col-sm-1">Price</div>
                                            <div class="col-sm-2">Sub Total</div>
                                        </div>
                                        <?php 
                                            foreach ($value['items'] as $i => $ivalue): 
                                                //var_dump($value['items']);
                                            $stotal = $ivalue['item_qty'] * $ivalue['items_srp'];
                                        ?>
                                            <div class="form-group">
                                                <div class="col-sm-3 iname-<?php echo $ivalue['item_itemtrid']; ?>"><?php echo $ivalue['item_name']; ?></div>
                                                <div class="col-sm-1"><?php echo $ivalue['item_qty']; ?></div>
                                                <div class="col-sm-3">
                                                    <?php if($ivalue['item_unit']=='load'): ?>
                                                        <select class="form form-control input-sm itemid-<?php echo $ivalue['item_itemtrid']; ?>">
                                                            <option value="<?php echo $ivalue['item_id']; ?>">
                                                                <?php echo $ivalue['item_name']; ?>
                                                            </option>
                                                            <?php foreach ($loaditem as $l): ?>
                                                                <?php if(intval($l->it_id)!==intval($ivalue['item_id'])): ?>
                                                                    <option value="<?php echo $l->it_id; ?>">
                                                                        <?php echo $l->it_name; ?>
                                                                    </option>                                                 
                                                                <?php endif; ?>                                            
                                                            <?php endforeach; ?>

                                                        </select>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php if($ivalue['item_unit']=='load'): ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form form-control inpmedx input-sm net-<?php echo $ivalue['item_itemtrid']; ?>" value="<?php echo $ivalue['item_net']; ?>" name="loaddeduct" id="loaddeduct" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="input-lg scan" name="data" 
                                                            autocomplete="off" maxlength="13" autofocus="" data-load-d="">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-info input-sm btn-find updatenet" type="button" data-itemidbtn="<?php echo $ivalue['item_itemtrid']; ?>">
                                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-1"><?php echo number_format($ivalue['items_srp'],2); ?></div>
                                                <div class="col-sm-2"><?php echo number_format($stotal,2); ?></div>
                                            </div>
                                            <?php if(trim($ivalue['item_ref'])!=''): ?>
                                                <div class="col-sm-offset-1 col-sm-2">Ref # <?php echo $ivalue['item_ref']; ?></div>
                                                <div class="col-sm-2">Mobile # <?php echo $ivalue['item_mobile']; ?></div>
                                            <?php endif; ?>
                                            <?php 

                                                $grandtotal += $stotal;

                                            ?>
                                            <div class="form-group">
                                                <div class="col-sm-offset-8 col-sm-2"><?php echo 'Total:'; ?></div>  
                                                <div class="col-sm-2" style="color:red;"><?php echo number_format($grandtotal,2); ?></div>                                              
                                            </div>

                                        <?php endforeach; ?>

                                    </div>
                                    </div>
                        <?php 

                                endforeach; 
                            else:
                        ?>
                            <h4>Empty Result</h4>

                        <?php 
                            endif; 
                        endif;
                        ?>
                    </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->