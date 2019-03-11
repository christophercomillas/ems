<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sales Per Day Result
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
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Date: <?php echo $ddate; ?></h4>
                        <input type="hidden" name="dquery" id="dquery" value="<?php echo $ddates; ?>">
                    </div>
                    <div class="col-sm-6">
                        <h5 class="totsales">Total Sales: <span class="_totsales"><?php echo number_format($total,2); ?></span></h5>
                    </div>
                </div>             
                <table class="demo-table4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Qty/Load</th>
                            <th>SRP</th>
                            <th>Net</th>
                            <th>Load Ref#</th>
                            <th>Load Mobile #</th>
                            <th>Subtotal</th>
                            <th>Transact By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        <?php
                            $cnt = 0; 
                            foreach ($items as $d): 
                                $err = false;
                                if($d['trans_itemnet'] == '300.00')
                                {
                                    $err = true;
                                }
                            ?>
                            
                            <tr class="list-<?php echo $d['trans_ledid']; echo $err ? ' err_net' : '';?>" data-trid="<?php echo $d['trans_id']; ?>" data-id="<?php echo $d['trans_ledid']; ?>" data-qty="<?php echo number_format($d['trans_itemqty'],2); ?>" data-srp="<?php echo number_format($d['trans_itemsrp'],2); ?>" data-net="<?php echo number_format($d['trans_itemnet'],2); ?>">
                                <td><?php $cnt++; echo $cnt; ?></td>
                                <td><?php echo strtoupper($d['trans_itemname']); ?></td>
                                <td class="right salesqty" 
                                    <?php if($d['trans_oum']==''): ?>
                                        contenteditable="true"
                                    <?php endif; ?>
                                ><?php echo number_format($d['trans_itemqty'],2); ?></td>
                                <td class="right salessrp" contenteditable="true"><?php echo number_format($d['trans_itemsrp'],2); ?></td>
                                <td class="right salesnet" contenteditable="true"><?php echo number_format($d['trans_itemnet'],2); ?></td>
                                <td class="right"><?php echo $d['trans_itemloadref']; ?></td>
                                <td class="right"><?php echo $d['trans_itemloadmobile']; ?></td>
                                <td class="right salestotal"><?php echo number_format($d['trans_subtotal'],2); ?></td>
                                <td><?php echo ucwords($d['trans_by']); ?></td>
                                <td>
                                    <i class="fa fa-fw fa-edit faction achangedate" title="Change Date"></i>
                                    <i class="fa fa-fw fa-remove faction aremove" title="Remove Transaction"></i>
                                </td>
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