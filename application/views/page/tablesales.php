	<?php 
		$tablerows = 10;

		if($this->session->userdata('cart')):

			$cartarr =  $this->session->userdata('cart');

			rsort($cartarr);

			if(count($cartarr) > 10)
			{
				$tablerows = 0;
			}
			else 
			{
				$tablerows = $tablerows - count($cartarr);
			}

			foreach ($cartarr as $c): ?>
			<tr>
				<td class="btnsidetdrev"><button onclick="voidTableSalesItem(<?php echo $c['itemindex']; ?>,'<?php echo $c['itemname']; ?>');" data-index="<?php echo $c['itemindex']; ?>" class="btnside">></button></td>
				<td class="itemnametd"><?php echo $c['itemname']; ?></td>
				<td class="qtytd"><?php echo number_format($c['qty']); ?></td>
				<td class="dsctd"><?php echo $c['disc']; ?></td>
				<td class="nettd"><?php echo number_format($c['srp'],2); ?></td>		
				<td class="srptd"><?php echo number_format($c['total'],2); ?></td>		
			</tr>
			<?php endforeach; 
		endif;
	?>

	<?php for($x=0; $x<$tablerows; $x++): ?>
		<tr>
			<td class="btnsidetdrev"></td>
			<td class="itemnametd"></td>
			<td class="qtytd"></td>
			<td class="dsctd"></td>
			<td class="nettd"></td>
			<td class="srptd"></td>
		</tr>
	<?php endfor; ?>