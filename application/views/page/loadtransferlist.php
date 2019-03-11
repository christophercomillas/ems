<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Load Transfer List 
        </h1>
        <ol class="breadcrumb">            
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
  
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <!-- Date range -->
                        <table class="table" id="loadtransferlist">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount</th>  
                                    <th>Transfer By</th>                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transferlist as $tl): ?>
                                    <tr>
                                        <td><?php echo $tl->datetransfer; ?></td>
                                        <td><?php echo $tl->namefromm.'('.$tl->frmsimcard.')'; ?></td>
                                        <td><?php echo $tl->nametom.'('.$tl->tosimcard.')'; ?></td>
                                        <td><?php echo number_format($tl->str_loadamt,2); ?></td>
                                        <td><?php echo strtoupper($tl->u_fullname); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
