<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sales Per Day 
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
                <form method="POST" action="<?php echo base_url(); ?>home/salesreportperday" id="" enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Date</h3>
                        </div>
                        <div class="box-body">
                            <!-- Date range -->
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="datepicker" name="date" autocomplete="off" required>
                                </div>
                                    <!-- /.input group -->
                            </div>
                            <div class="response">                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Query</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-5">
                <form method="POST" action="<?php echo base_url(); ?>home/filteritemsales" id="" enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Filter by Item Name</h3>
                        </div>
                        <div class="box-body">
                            <!-- Date range -->
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="datepicker1" name="date" autocomplete="off" required>
                                </div>
                                    <!-- /.input group -->
                            </div>
                            <div class="response">                                
                            </div>
                            <div class="form-group">
                                <label class="label-dialog"><span class="requiredf"></span>Item Name</label>
                                <select class="form form-control input-sm inp-b" id="item_name" name="item_name">
                                    <option value="">- Select -</option>
                                    <?php foreach ($it_item as $it): ?>
                                        <option value="<?php echo $it->it_id; ?>"><?php echo $it->it_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Query</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->