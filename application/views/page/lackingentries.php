<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Lacking Entries
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
                <table class="demo-table6">
                    <thead>
                        <tr>
                        	<th>#</th>
                            <th>Transaction Date</th>
                            <th>Item Name</th>
                            <th>Quantity/Load</th>
                            <th>SRP</th>
                            <th>Net</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        <?php
                            $cnt = 0; 
                            foreach ($items as $d): ?>
                            <tr data-id="<?php echo $d['trans_saleid']; ?>">
                                <td><?php $cnt++; echo $cnt; ?></td>
                                <td><?php echo $d['trans_datesold']; ?></td>
                                <td><?php echo strtoupper($d['trans_itemname']); ?></td>
                                <td class="right"><?php echo number_format($d['tran_qty'],2); ?></td>
                                <td class="right"><?php echo number_format($d['trans_net'],2); ?></td>
                                <td class="right"><?php echo number_format($d['trans_srp'],2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->