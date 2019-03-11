<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Generate Report / Date Clearing
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
                            <h3 class="box-title">Date</h3>
                        </div>
                        <div class="box-body">
                            <!-- Date range -->
                            <div class="form-group">
                                <label for="startDate">Start Date</label>
                                    <input id="startDate" name="startDate" id="startDate" type="text" class="form-control mb10" /> &nbsp;
                                <label for="endDate">End Date</label>
                                    <input id="endDate" name="endDate" id="endDate" type="text" class="form-control" />
                            </div>
                            <div class="response">                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-block btn-info pull-right" id="btn-reportran">Generate Report</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-block btn-info pull-right" id="btn-clearing">Clear Date Entry</button>
                            </div>                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<!-- /.content-wrapper -->

                <!-- <form id="form" name="form" class="form-inline">
                <div class="form-group">
                <label for="startDate">Start Date</label>
                <input id="startDate" name="startDate" type="text" class="form-control" /> &nbsp;
                <label for="endDate">End Date</label>
                <input id="endDate" name="endDate" type="text" class="form-control" />
                </div>
                </form> -->