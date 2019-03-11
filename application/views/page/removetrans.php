<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Query Result (Remove Transaction)
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
                    <div class="box-body">
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
                                            <label class="col-md-3">Transaction #: <span class="span<?php echo $value['trans_id']; ?>"><?php echo $value['trans_num']; ?></span></label>
                                            <label class="col-md-4">Date: <span class="sp<?php echo $value['trans_id']; ?>"><?php echo _dateFormat($value['trans_datetime']); ?></span></label>
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btnremovetr" type="button" data-trid="<?php echo $value['trans_id']; ?>"><i class="fa fa-fw fa-remove"></i></button>
                                            </div>
                                        </div>   
                                        <div class="form-group">
                                            <label class="col-md-3">Store: <?php echo ucwords($value['trans_store']); ?></label>
                                            <label class="col-md-4">Cashier: <?php echo ucwords($value['trans_cashier']); ?></label>
                                        </div> 
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-4">Item</div>
                                            <div class="col-sm-2">Quantity</div>
                                            <div class="col-sm-2">Price</div>
                                            <div class="col-sm-2">Sub Total</div>
                                        </div>
                                        <?php 
                                            foreach ($value['items'] as $i => $ivalue): 
                                            $stotal = $ivalue['item_qty'] * $ivalue['items_srp'];
                                        ?>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-4"><?php echo $ivalue['item_name']; ?></div>
                                                <div class="col-sm-2"><?php echo $ivalue['item_qty']; ?></div>
                                                <div class="col-sm-2"><?php echo number_format($ivalue['items_srp'],2); ?></div>
                                                <div class="col-sm-2"><?php echo number_format($stotal,2); ?></div>
                                            </div>
                                            <?php if(trim($ivalue['item_ref'])!=''): ?>
                                                <div class="col-sm-offset-2 col-sm-2">Ref # <?php echo $ivalue['item_ref']; ?></div>
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
                    <!-- /.box-body -->
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->