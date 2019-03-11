<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Generate Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-5">
                <form method="POST" action="<?php echo base_url(); ?>Excel_export/reportAccounting" id="_querytrdateremove" enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Receiving Id</h3>
                        </div>
                        <div class="box-body">
                            <!-- Receive id -->
                            <div class="form-group">
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right" id="re_id" name="re_id" autocomplete="off">
                                </div>
                                    <!-- /.input group -->
                            </div>
                            <div class="response">                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" class="btn btn-info pull-right" id="btn-report">Query</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="false">All</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="alert alert-info pull-right" id="exportreceived">Export Excel</button>
                                    <?php if(count($list) > 0): 
                                        foreach ($list as $l => $value):
                                    ?>
                                            <div class="row row-bot-s">
                                                <div class="trwrap" data-id="<?php echo $l['rec_id']; ?>">  
                                                    <div class="form-group">
                                                        <label class="col-md-7">Receving #: <?php echo $value['rec_num']; ?></label>
                                                        <label class="col-md-5">Date: <?php echo _dateFormat($value['rec_datetime']); ?></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4">P.O. #: <?php echo $value['rec_po']; ?></label>
                                                        <label class="col-md-4">S.I. #: <?php echo $value['rec_si']; ?></label>
                                                        <label class="col-md-4">Ref. #: <?php echo $value['rec_ref']; ?></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-offset-1 col-md-6 col-header">Item Description</label>
                                                        <label class="col-md-5 col-header">Quantity / Load</label>
                                                    </div>
                                                    <?php 
                                                        foreach ($value['rec_items'] as $i => $ivalue): 
                                                    ?>
                                                        <div class="form-group">
                                                            <label class="col-md-offset-1 col-md-6 col-items"><?php echo ucwords($ivalue['item_name']); ?></label>
                                                            <label class="col-md-5 col-items"><?php echo number_format($ivalue['item_qty'],2); ?></label>                               
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>                    
                                    <?php else: ?>
                                        <h4>Empty Result</h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
