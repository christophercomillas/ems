<?php 
$tablerows = 10;
$gctemp_numrows = 0;
if($gctemp_numrows > 10)
{
	$tablerows = 0;
}
else
{
	$tablerows = $tablerows - $gctemp_numrows;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>E-loading POS</title>	
	<link rel="shortcut icon" href="<?php echo base_url().'assets/img/eload.ico'?>" type="image/icon">
	<link href="<?php echo base_url().'assets/bootstrap/css/bootstrap-yeti.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url().'assets/css/override.css'?>" rel="stylesheet" type="text/css" />
	<style media="print" type="text/css">
		@media print
		{
			body * { visibility: hidden; }
			#print-receipt * { visibility: visible; }
			#print-receipt { display:block; position: absolute; top: -20px; left: 0px; }
			#xprintreports * { visibility: visible; }
		}
	</style> 
</head>
<body>
<div class="container86">
	<input type="hidden" id="siteurl" value="<?php echo base_url(); ?>">
	<div class="content-right">
		<div class="cashier-main <?php echo isset($_SESSION['gc_super_id']) ? 'hidediv' : ''; ?>">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Start Sales</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Receiving</span>
			</button>
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Supervisor Menu</span>
				<span class="nextrow">></span>
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Discount</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Others</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f6" onclick="f6();">
				<span class="btnkey">[F6]</span> <span class="btnames">Reports</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f7" onclick="f7();">
				<span class="btnkey">[F7]</span> <span class="btnames">User Account</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns f8" onclick="f8();">
				<span class="btnkey">[F8]</span> <span class="btnames">Logout</span>
			</button>
		</div>
		<div class="cashier-sales">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Add Item</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Edit Item</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Payment</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="cashier-receiving">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Add Item</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Edit Item</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Save</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="cashier-paymentmode">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Cash</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Credit Card</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">GC</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">ATP</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="cashier-reports">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Terminal Report</span>
			</button>		
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Cashier Report</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Shortage / Overage</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">End of Day</span>
			</button>
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
		</div>	
		<div class="cashier-account">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Change Username</span>
			</button>		
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Change Password</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Back</span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
		</div>	
		<div class="cashier-others">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Load Transfer</span>
			</button>		
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Back</span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
		</div>	
		<div class="manager-mode <?php echo isset($_SESSION['gc_super_id']) ? '' : 'hidediv'; ?>">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Lookup</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void All</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">GC Refund</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Discount</span>
				<span class="nextrow">></span>
			</button>			
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Reports</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f6" onclick="f6();">
				<span class="btnkey">[F6]</span> <span class="btnames">End of Day</span>
			</button>	
			<button class="btns btnborderbot f7" onclick="f7();">
				<span class="btnkey">[F7]</span> <span class="btnames">Shortage / Overage </span>
			</button>
			<button class="btns btnborderbot f8" onclick="f8();">
				<span class="btnkey">[F8]</span> <span class="btnames">Supervisor Logout</span>
			</button>	
		</div>
		<div class="otherincome-mode">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">GC Revalidation</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="reports">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Terminal Report</span>
			</button>		
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Cashier Report</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">GC Sales Report</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="discounts">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Line Disc</span>
			</button>
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Subtotal <br />Discount</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Remove <br />Line Discount</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Remove <br />SubDiscount</span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>		
		</div>
		<div class="returngc">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Refund</span>
			</button>	
			<!-- 			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Service Fee</span>
			</button>	 -->
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void Line</span>
			</button>
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
			<button class="btns btnborderbot">
			</button>
		</div>	
	</div>
	<div class="content-left">
		<div class="content-main">
			<h3 class="title-eload">E-loading Monitoring System</h3>
		</div>
		<div class="content-eloadsales">
			<div class="containerscan-new">
				<h3 class="c-title">Sales</h3>
			</div>
			<table class="tablef" id="gcsales">
				<thead>
					<tr>
						<th class="itemnameth">Item Name</th>
						<th class="qtyth">Qty</th>
						<th class="discamtth">Disc Amt</th>
						<th class="netamtth">SRP</th>
						<th class="netamtth">Total</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="scrollable">
					<div class="item-list">
						<table class="tablef">
							<tbody class="_loadtempsalesitems">
									<?php for($x=0; $x<$tablerows; $x++): ?>
										<tr>
											<td class="btnsidetd"></td>
											<td class="itemnametd"></td>
											<td class="qtytd"></td>
											<td class="dsctd"></td>
											<td class="nettd"></td>
											<td class="srptd"></td>
										</tr>
									<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="transacdetails">
				<div class="tdetials-right">
				<input type="text" class="response-msg msgsales" readonly="readonly" value="" tabIndex="-1">
				<h4 class="amt-due">Amount Due</h4>
				<input type="text" class="inp-amtdue _cashier_total" readonly="readonly" value="0" tabIndex="-1">

				</div>
				<div class="tdetails-left">
					<input type="hidden" name="sbtotal" value="0">
					<input type="hidden" name="docdiscount" value="0.00">
					<input type="hidden" name="ocharge" value="0.00">
					<input type="hidden" name="tax" value="0.00">
					<input type="hidden" name="linediscount" value="0.00">
					<table class="tabledetails">
						<tbody>
							<tr>
								<td>Subtotal:. . . . . . . . .  </td>
								<td><input type="text" class="amts sbtotal" value="0" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Line Discount:. . . . </td>
								<td><input type="text" class="amts linediscount" value="0" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Subtotal Discount:.. </td>
								<td><input type="text" class="amts docdiscount" value="0" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Customer Disc:. . . . .  </td>
								<td><input type="text" class="amts cdisc" value="0.00" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Tax:. . . . . . . . . . . . .  </td>
								<td><input type="text" class="amts tax" value="0.00" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>No. of Items:. . . . . .  </td>
								<td><input type="text" class="amts amts-b noitems" value="0" readonly="readonly" tabIndex="-1"></td>
							</tr>
						</tbody>				
					</table>
				</div>
			</div>
		</div>
		<div class="content-receiving">
			<div class="containerscan-new">
				<h3 class="c-title">Receiving</h3>
			</div>
			<table class="tablef" id="gcsales">
				<thead>
					<tr>
						<th class="itemnameth">Item Name</th>
						<th class="qtyth">Qty</th>
						<th class="discamtth">Disc Amt</th>
						<th class="netamtth">Net Price</th>
						<th class="netamtth">Total</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="scrollable">
					<div class="item-list">
						<table class="tablef">
							<tbody class="_loadtempsrecitems">
									<?php for($x=0; $x<$tablerows; $x++): ?>
										<tr>
											<td class="btnsidetd"></td>
											<td class="itemnametd"></td>
											<td class="qtytd"></td>
											<td class="dsctd"></td>
											<td class="nettd"></td>
											<td class="srptd"></td>
										</tr>
									<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="transacdetails">
				<div class="tdetials-right">
				<input type="text" class="response-msg msgsales" readonly="readonly" value="" tabIndex="-1">
				<h4 class="amt-due">Total Amount</h4>
				<input type="text" class="inp-amtdue _cashier_totalrec" readonly="readonly" value="0" tabIndex="-1">

				</div>
				<div class="tdetails-left">
					<table class="tabledetails">
						<tbody>
							<tr>
								<td>Receiving #:. . . . </td>
								<td><input type="text" class="amts recnumber" value="0" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td><span class="reqspan">*</span>SI #. . . . . . . . . . . . . </td>
								<td><input type="text" class="amts sinumbers" value=""></td>
							</tr>
							<tr>
								<td><span class="reqspan">*</span>PO #. . . . . . . . . . . . . </td>
								<td><input type="text" class="amts ponumber" value=""></td>
							</tr>
							<tr>
								<td><span class="reqspan">*</span>Reference #:. . . . .  </td>
								<td><input type="text" class="amts refnumber" value=""></td>
							</tr>
							<tr>
								<td><span class="reqspan">*</span>Checked By: . . . . .  </td>
								<td><input type="text" class="amts checkedby" value=""></td>
							</tr>
							<tr>
								<td>No. of Items:. . . . . .  </td>
								<td><input type="text" class="amts amts-b noitemsrec" value="0" readonly="readonly" tabIndex="-1"></td>
							</tr>
						</tbody>				
					</table>
				</div>
			</div>
		</div>

		<div class="content-returngc">
			<div class="containerscan">
				<label class="labelscan">GC Barcode #</label><input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="input-lg scan" name="inprefundgc" id="numOnlyreturn" autocomplete="off" maxlength="13" autofocus />  
			</div> 
			<table class="tablef" id="gcsales">
				<thead>
					<tr>
						<th class="barcodethref">GC Barcode #</th>
						<th class="typethref">Type</th>
						<th class="denomthref">Denom</th>
						<th class="soldrelref">Line Disc.</th>
						<th class="netamtth">Sub Disc.</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="scrollable">
					<div class="item-list">
						<table class="tablef">
							<tbody class="_barcodesrefund">								
								<?php
									$revalrows = 10; 
									for($x=0; $x<$revalrows; $x++): ?>
									<tr>
										<td class="btnsidetdref"></td>
										<td class="barcodetdref"></td>
										<td class="typetdref"></td>
										<td class="denomtdref"></td>
										<td class="linediscref"></td>
										<td class="subdiscref"></td>
									</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="transacdetails">
				<div class="tdetials-right">
					<input type="text" class="response-msg msgrefund" readonly="readonly" value="" tabIndex="-1">
					<h4 class="amt-due">Total Refund</h4>
					<input type="text" class="inp-amtdue _cashier_totalrefund" readonly="readonly" value="0.00" tabIndex="-1">				
				</div>
				<div class="tdetails-left">
					<h4 class="revaltitle">GC Refund</h4>
					<table class="tabledetails">
						<tbody>
							<tr>
								<td>
									<td>Total Denom:. . . . . .  </td>
									<td><input type="text" class="amts amts totdenomref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>Total Sub Disc:. . . .</td>
									<td><input type="text" class="amts amts totsubdiscref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>Total Line Disc:. . . .</td>
									<td><input type="text" class="amts amts totlinedisref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>Service Charge:. . . .</td>
									<td><input type="text" class="amts serviceref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>No. of Items:. . . . . .  </td>
									<td><input type="text" class="amts amts amts-b noitemsref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="footerpanel">
			<table class="tablefooter">
				<tr>
					<td class="store_footer1"></td>
					<td class="storename_footer1"></td>
					<td class="cashier_footer"></td>
					<td class="cashiername_footer1"></td>
					<td class="datetime"><?php echo _dateFormat(todays_date()); ?> <span id="time"></span></td>
				</tr>
			</table>
			<table class="tablefooter">
				<tr>
					<td class="store_footer">Store:</td>
					<td class="storename_footer">
						<?php echo $this->session->userdata('load_buname'); ?>						
					</td>
					<td class="cashier_footer">Cashier:</td>
					<td class="cashiername_footer"><?php echo ucwords($this->session->userdata('load_fullname')); ?></td>
					<td class="datetime">Supervisor Key <input id="managerkey" type="checkbox" disabled=""/></td>
				</tr>
			</table>
		</div>
	</div>

</div>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url().'assets/js/jquery-1.10.2.js'?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js'?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/js/bootstrap-modalb.js'?>"></script>
<script src="<?php echo base_url().'assets/js/jquery.inputmask.bundle.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/jquery.dataTables.js'?>"></script>
<script src="<?php echo base_url().'assets/js/snowflakes.js'?>"></script>
<script src="<?php echo base_url().'assets/js/shortcut.js '?>"></script>
<script src="<?php echo base_url().'assets/js/bootstrap-datepicker1.min.js '?>"></script>
<script src="<?php echo base_url().'assets/js/cashier-main1.js '?>"></script>
<script type="text/javascript">
var App = {
    params: {
        size: 'fullscreen',
        bg: 'white',
        count: 30,
        speed: 2,
        useRotate: true,
        useScale: true
    },
    setBg: function(value) {
        this.params.bg = value;
        this.updateSettings();
    },
    setCount: function(value) {
        this.params.count = parseInt(value, 10);
        this.redraw();
    },
    setSpeed: function(value) {
        this.params.speed = parseFloat(value);
        this.redraw();
    },
    setSize: function(value) {
        this.params.size = value;
        this.updateSettings();
        this.redraw();
    },
    setRotate: function(value) {
        this.params.useRotate = value;
        this.redraw();
    },
    setScale: function(value) {
        this.params.useScale = value;
        this.redraw();
    },
    updateSettings: function() {
        document.body.className = 'bg_' + this.params.bg + ' size_' + this.params.size;
    },
    redraw: function() {
        if (this._snow) {
            this._snow.destroy();
        }

        this._snow = new Snowflakes({
            container: this.params.size === 'fullscreen' ? document.body : document.getElementById('layer'),
            count: this.params.count,
            speed: this.params.speed,
            useRotate: this.params.useRotate,
            useScale: this.params.useScale
        });
    },
    start: function() {
        this.updateSettings();
        this.redraw();
    }
};

//App.start();
</script>
</body>
</html>	