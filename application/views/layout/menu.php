        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo base_url().'assets/dist/img/user.png'?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo ucwords($this->session->userdata('aload_username')); ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li <?php echo $menuactive=='dashboard' ? "class='active'" :''; ?>>
                        <a href="<?php echo base_url(); ?>home/dashboard"><i class="fa fa-book"></i> <span>Dashboard</span></a>
                    </li>
                    <?php if($this->session->userdata('aload_department')=="Admin" || $this->session->userdata('aload_department')=="Retail Store"): ?>
                        <li class="treeview <?php echo $menuactive=='masterfile' ? 'active' :''; ?>">
                            <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Masterfile</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if($this->session->userdata('aload_department')=="Admin"): ?>
                                    <li><a href="<?php echo base_url(); ?>user/manageusers"><i class="fa fa-circle-o"></i> Manage User</a></li>
                                    <li><a href="<?php echo base_url(); ?>item/manageitems"><i class="fa fa-circle-o"></i> Manage Items</a></li>
                                <?php endif; ?>
                                <?php if($this->session->userdata('aload_department')=="Admin" || $this->session->userdata('aload_department')=="Retail Store"): ?>
                                    <li><a href="<?php echo base_url(); ?>item/managesimcard"><i class="fa fa-circle-o"></i> Manage Simcards (Eload)</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li <?php echo $menuactive=='itemremain' ? "class='active'" :''; ?>>
                            <a href="<?php echo base_url(); ?>home/itemremaining"><i class="fa fa-book"></i> <span>Item Remaining</span></a>
                        </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata('aload_department')=="Admin" || $this->session->userdata('aload_department')=="Retail Store" ): ?>
                        <li class="treeview <?php echo $menuactive=='concerns' ? 'active' :''; ?>">
                            <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Concerns</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>                        
                                <ul class="treeview-menu">
                                    <li class="active"><a href="<?php echo base_url(); ?>home/changedate"><i class="fa fa-circle-o"></i> Change Date</a></li>
                        <!--    <ul class="treeview-menu">
                                    <li class="active"><a href="<?php echo base_url(); ?>home/removetransactionquery"><i class="fa fa-circle-o"></i> Remove Transaction</a></li>
                                </ul> -->

                                    <li class="active"><a href="<?php echo base_url(); ?>home/loadeducationquery"><i class="fa fa-circle-o"></i> Manage Load Deduction</a></li>
                                </ul>
                        </li>   
                    <?php endif; ?>                 
                        <li class="treeview <?php echo $menuactive=='list' ? 'active' :''; ?>">
                            <a href="#">
                            <i class="fa fa-dashboard"></i> <span>List</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url(); ?>home/receivedlist"><i class="fa fa-circle-o"></i> Received</a></li>
                                <li><a href="<?php echo base_url(); ?>home/loadtransferlist"><i class="fa fa-circle-o"></i> Load Transfer List</a></li>
                                <li><a href="<?php echo base_url(); ?>home/simcardeodlist"><i class="fa fa-circle-o"></i> SimCard EOD Balance</a></li>
                            </ul>  
                        </li>

                    <?php if($this->session->userdata('aload_department')=="Accounting" ||
                            $this->session->userdata('aload_department')=="Retail Store" ||
                            $this->session->userdata('aload_department')=="Admin" ||
                            $this->session->userdata('aload_department')=="IAD"
                            ): ?>
                        <li <?php echo $menuactive=='sales' ? "class='active'" :''; ?>>
                            <a href="<?php echo base_url(); ?>home/itemsalesquery"><i class="fa fa-book"></i> <span>Sales</span></a>
                        </li>
                    <?php endif; ?>

                    <?php if($this->session->userdata('aload_department')=="Accounting" ||
                        $this->session->userdata('aload_department')=="Admin"
                        ): ?>
                        <li <?php echo $menuactive=='lack' ? "class='active'" :''; ?>>
                            <a href="<?php echo base_url(); ?>home/checkLackingSalesItemsOnLedger"><i class="fa fa-book"></i> <span>Lacking Transaction</span></a>
                        </li>                    
                    <?php endif; ?>

                    <?php if($this->session->userdata('aload_department')=="Accounting" ||
                            $this->session->userdata('aload_department')=="Admin" ||
                            $this->session->userdata('aload_department')=="IAD"
                            ): ?>        
                        <li <?php echo $menuactive=='textfiletoexcel' ? "class='active'" :''; ?>>
                            <a href="<?php echo base_url(); ?>home/textfileExcelConversion"><i class="fa fa-book"></i> <span>Textfile to Excel</span></a>
                        </li>             
                        <li class="treeview <?php echo $menuactive=='reportsales' ? 'active' :''; ?>">
                            <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Report</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url(); ?>home/reportaccountingsales"><i class="fa fa-circle-o"></i> Report Per Day</a></li>
                                <li><a href="<?php echo base_url(); ?>home/rangereport"><i class="fa fa-circle-o"></i> Report By Range</a></li>
                                <li><a href="<?php echo base_url(); ?>home/receivereport"><i class="fa fa-circle-o"></i> Receiving Report</a></li>
                            </ul>
                        </li>
   
                    <?php endif; ?>   

            <!--    <li class="<?php echo $title=='Transactions' ? "class='active'" :''; ?> treeview">
                        <a href="#">
                            <i class="fa fa-files-o"></i>
                            <span>Layout Options</span>
                            <span class="pull-right-container">
                            <span class="label label-primary pull-right">4</span>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                            <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                            <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                        </ul>
                    </li> -->
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
