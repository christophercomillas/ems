<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Textfile to Excel
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
                <form method="POST" action="<?php echo base_url(); ?>Excel_export/reportAccounting" id="_uploadtxtfile" enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Select File</h3>
                        </div>
                        <div class="box-body">
                            <!-- Date range -->
                            <div class="form-group">
                                <input type="file" id="upload" class="form-control pull-right" accept=".txt, Text Document" name="loadtxtfile" autocomplete="off">
                            </div>
                            <div class="response">                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-block btn-info pull-right" id="btn-uploadtxtfile">Submit</button>
                            </div>                         
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
