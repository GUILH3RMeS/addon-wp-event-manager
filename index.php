<?php
/**
 * Lucros hype eventos
 *  Plugin Name: Lucros hype eventos
 *  Plugin URI: https://hypeeventos.net.br/
 *  Description: plugin made to validate and automate sending abandoned carts to mautic
 *  Version: 1.0
 *  author: Guilherme S
 *  Author URI: https://www.instagram.com/guiapenas/
 *  License: GPLv2 or later$
 */

add_action('admin_menu', 'lucros');

function lucros()
{
	add_menu_page('Lucros Hype Eventos', 'Lucros', 'manage_options', 'lucros', 'lucros_page', "", 2);
	add_submenu_page('lucros', 'Colaboradores', 'Colaboradores', 'manage_options', 'Colaboradores', 'AdminColaboradores');
}
function AdminColaboradores(){
	global $current_user;
	
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    if ($user_role == 'contributor') {
    }
    $eventsIDS = get_posts([
        'post_type' => "event_listing",
        'post_status' => array(),
        'fields' => 'ID',
        'posts_per_page' => -1
    ]);
    $event_id = [];
    $event_title = [];
    foreach ($eventsIDS as $event) {
        foreach ($event as $key => $value) {
            if ($key == "ID") {
                array_push($event_id, $value);
            }
            if ($key == "post_title") {
                array_push($event_title, $value);
            }
        }
    }
    $users = get_users(array('fields' => array('ID', 'user_nicename', 'user_email')));
    ?>
    <style>
        .checkboxes {
            display: none;
            border: 1px #dadada solid;
			height: 300px;
    		overflow-y: scroll;
        }

        .checkboxes input {
            background-color: #fff;
            border-radius: 3px;
            border: 1px solid #dfe0df;
            box-shadow: none;
            padding: 10px;
            height: 40px;
        }

        .checkboxes label:hover {
            background-color: lightgray;
        }

        .overSelect {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
        }

        .multiselect {
            width: 100%;
        }

        .selectBox {
            position: relative;
        }

        .eventTableHeader {
            display: flex;
            font-size: 12pt;
            font-weight: bold;
            padding: 5px 5px;
            justify-content: space-between;
            border: 1px solid #ebebeb;
            border-bottom: 0px;
            border-radius: 5px 5px 0px 0px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            align-items: center;
        }

        .eventTableHeadCell {
            width: 30%;
            text-align: center;
        }

        .users {
            border: 1px solid #e7e7e7;
            border-radius: 0px 0px 8px 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .usersHeader {
            display: flex;
            font-size: 12pt;
            font-weight: bold;
            padding: 5px 5px;
            justify-content: space-between;
            border: 1px solid #ebebeb;
            border-bottom: 0px;
            border-radius: 5px 5px 0px 0px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            align-items: center;
        }

        .usersHeaderCell {
            width: 30%;
            text-align: center;
        }
        .userBodyCell {
            display: flex;
            width: 30%;
            flex-direction: column;
            align-items: center;
            flex-wrap: wrap;
            font-size: 12pt;
            font-weight: 500;
        }

        .userBodyLine {
            display: flex;
            height: 58px;
            width: 100%;
            margin-bottom: 5px;
            text-align: center;
            align-items: flex-start;
            justify-content: space-between;
        }

        .actionBtn {
            padding: 0px 10px;
            color: #111111;
            text-decoration: none;
            cursor: pointer;
        }

        .actionBtn:hover {
            cursor: pointer;
            text-decoration: underline;
            color: var(--wpem-primary-color);
        }

        .Warning {
            display: flex;
            flex-direction: column;
            font-size: 14pt;
            font-weight: 600;
        }

        .Warning span {
            font-size: 12pt;
            font-weight: 500;
        }
		.eventTable{
			padding:5px;
		}
		.userAdd{
			display:none;
			text-align: center;
		}
		.displayBlock{
			display: block;
		}
		.SubmitContributors{
			background: var(--wpem-primary-color);
    color: var(--wpem-white-color);
    border: none;
    padding: 10px 20px;
    width: auto;
    text-transform: uppercase;
    font-weight: 600;
    border-radius: 4px;
    font-size: 16px;
    line-height: 1.42;
    margin: 5px 0;
    letter-spacing: 0;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    overflow-wrap: break-word;
    transition: all 0.2s;
    vertical-align: middle;
		}
		.SubmitContributors:hover, .SubmitContributors:active{
			background: #111111;;
		}
		.submit{
			padding:5px;
		}
    </style>
<div style='background: white;'>
    <?php
    foreach ($event_id as $key => $id) {
        ?>
        <div class="eventTable">
            <div class="eventTableHeader" style='align-items: flex-start'>
                <div class="eventTableHeadCell" style="width: 50%;">
                    <span style='white-space: nowrap;'>
                        <?php echo $event_title[$key]; ?>
                    </span>
                </div>
                <div class="eventTableHeadCell" style="width: 50%;">
                    <div class='actionBtn' id='addBtn_<?php echo $id; ?>'>
                        Adicionar Colaboradores
                    </div>
					<script>
						jQuery("#addBtn_<?php echo $id; ?>").on('click',()=>{
							if(!jQuery(".userAdd").eq(<?php echo $key; ?>).hasClass('displayBlock')){
								jQuery(".userAdd").eq(<?php echo $key; ?>).addClass('displayBlock');
							}else{
								jQuery(".userAdd").eq(<?php echo $key; ?>).removeClass('displayBlock')
							}
						})
					</script>
                </div>
            </div>
            <div class="eventTableBody">
                <div class="users">
                    <div class="usersHeader">
                        <div class="usersHeaderCell">
                            Nome
                        </div>
                        <div class="usersHeaderCell">
                            Email
                        </div>
                        <div class="usersHeaderCell">
                            Ações
                        </div>
                    </div>
                    <div class="usersBody">
                        <div class='user'>
							<?php
							$contributors = get_post_meta($id, '_event_contributors');
		if(count($contributors) == 0){
			echo "<div class='Warning' style='padding: 10px; text-align: center;'>O evento não possui Colaboradores</div>";
	}else{
			if(!isset($contributors[0])){
			echo "<div class='Warning' style='padding: 10px; text-align: center;'>O evento não possui Colaboradores</div>";
				
		}else{
			foreach($contributors[0] as $k => $value){
				$contributor = get_userdata($value);
						?>
                            <div class='userBodyLine'>
                                <div class="userBodyCell userName">
                                    <?php print_r($contributor->user_nicename); ?>
                                </div>
                                <div class="userBodyCell userEmail">
                                    <?php print_r($contributor->user_email); ?>
                                </div>
                                <div class="userBodyCell actionBtn" id='removeBtn_<?php echo $contributor->ID; ?>'>
                                    Remover
                                </div>
								<script>
									jQuery("#removeBtn_<?php echo $contributor->ID; ?>").on('click',()=>{																			jQuery("#act_<?php echo $id; ?>").val('remove')
										jQuery("#remove_<?php echo $id; ?>").val('<?php echo $contributor->ID; ?>')
										jQuery('#submitBtnContributors_<?php echo $id; ?>').click()
									})
								</script>
                            </div>
							<?php
			}
		}
		}
								?>
                            <div class='userAdd'>
                                <div class='Warning'>
                                    <span>(ao adicionar um colaborador ele terá acesso aos eventos, ingressos e cupons de desconto)</span>
                                </div>
                                <div class="addContributor">
									 <form id='sendContributors_<?php echo $id;?>' method='post' action='' enctype='multipart/form-data'>
										 <input value='<?php echo $id; ?>' name='eventID' hidden>
										 <input value='submit' id='act_<?php echo $id; ?>' name='act' hidden>
										 <input value='_' id='remove_<?php echo $id; ?>' name='removeID' hidden>
                                    <div class="multiselect">
                                        <div class="selectBox" name='users_select' id='users_select'
                                            style='width:80%;margin:10px auto; ' onclick="showCheckboxes(<?php echo $id; ?>)">
                                            <select>
                                                <option>Selecione os Colaboradores</option>
                                            </select>
                                            <div class="overSelect"></div>
                                        </div>
                                        <div id="users_checkboxes_<?php echo $id; ?>" class='checkboxes'>
                                            <?php
                                            foreach ($users as $key => $user) {
                                                print_r('
							<label for="'. $id .'_user_' . $user->ID . '" style="display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-wrap: nowrap;
    padding: 5px;"><input type="checkbox" id="'.$id.'_user_' . $user->ID . '" value="' . $user->ID . '" name="userID_' . $user->ID . '" /><p style="margin: auto auto auto 15px;">' . $user->user_email . '</p></label>');
                                            }
                                            ?>
                                        </div>
                                    </div>
										
									</form>
                                    <script>
                                        var expanded = false;

                                        function showCheckboxes(n) {
                                            var checkboxes = document.getElementById(`users_checkboxes_${n}`);
                                            if (!expanded) {
                                                checkboxes.style.display = "block";
                                                expanded = true;
                                            } else {
                                                checkboxes.style.display = "none";
                                                expanded = false;
                                            }
                                        }
                                    </script>
                                </div>
								<div class='submit'>
									<button class='SubmitContributors' id='submitBtnContributors_<?php echo $id; ?>' type='button'>
										Adicionar Colaboradores
									</button>
								</div>
								<script>
										jQuery('#submitBtnContributors_<?php echo $id; ?>').on('click', (e)=>{
											e.preventDefault();
							
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: "action=addContributors&" + jQuery("#sendContributors_<?php echo $id;?>").serialize(),
                                success: function (msg) {
                                    console.log(msg);
									window.location.reload()
                                }
                            });
										})
									</script>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
	<?php }
	?>
	</div>
	<?php
}
function lucros_page()
{
	global $wpdb;
	$eventsIDS = get_posts([
		'post_type' => "event_listing",
		'post_status' => array('publish', 'private', 'expired'),
		'fields' => 'ID',
		'posts_per_page' => -1
	]);
	$event_id = [];
	$event_title = [];
	foreach ($eventsIDS as $event) {
		foreach ($event as $key => $value) {
			if ($key == "ID") {
				array_push($event_id, $value);
			}
			if ($key == "post_title") {
				array_push($event_title, $value);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$tickets_ids = [];
	foreach ($event_id as $key => $id) {
		$eventMetaData = get_post_meta($event_id[$key]);
		$unserializedPaidTickets = unserialize($eventMetaData['_paid_tickets'][0]);
		$unserializedFreeTickets = unserialize($eventMetaData['_free_tickets'][0]);
		foreach ($unserializedPaidTickets as $kkey => $product) {
			array_push($tickets_ids, $product['product_id']);
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$args = array(
		'post_type' => 'product',
		'post_status' => array('publish', 'private', 'expired'),
		'posts_per_page' => -1,
		'taxonomy' => array('product_type'),
		'terms' => 'event_ticket'
	);
	$products = new WP_Query($args);
	while ($products->have_posts()):
		$products->the_post();
		global $product;
		$productID = $product->get_id();
		$productMetaData = get_post_meta($productID);

	endwhile;
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	?>
	<style>
		.tableEvents {
			display: flex;
			flex-direction: column;
		}

		.event {
			margin: 10px 0px;
		}

		.eventHeader {
			display: flex;
			background: white;
			border: 1px solid lightgray;
			border-radius: 8px 8px 0px 0px;
			height: 30px;
			align-items: center;
			justify-content: flex-start;
			padding: 5px 30px;
			border-bottom: none;
		}

		.eventHeaderCell {
			font-size: 15pt;
			font-weight: bold;
			color: #565656;
		}

		.eventBody {
			background: white;
			border-radius: 0px 0px 8px 8px;
			border: 1px solid lightgray;
			border-top: none;
		}

		.ProductsHeader,
		.product,
		.detailsHeader,
		.detailsBody {
			display: flex;
			align-items: center;
		}
		.detailsHeader{
			    margin-top: 30px;
		}
		.product{
			border-top: 3px solid lightgray;
		}

		.ProductsHeaderCell,
		.detailsHeaderCell {
			width: 20%;
			padding: 5px 15px;
			font-size: 10pt;
			font-weight: 500;
		}

		.productBodyCell,
		.detailsBodyCell {
			width: 20%;
			padding: 5px 15px;
			font-size: 10pt;
			font-weight: 400;
		}
		
		.totalSales, .sells{
			width:40%;
		}
		.totalSales,
		.subtotal {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
		}

		.repasseValue {
			text-align: center;
			width: 80px;
			padding: 10px 20px;
			border-radius: 8px;
			border: 1px solid lightgray;
			font-size: 16px;
			margin: 5px;
		}

		.submitRepasse {
			background: #0096ff;
			color: white;
			border: none;
			padding: 10px 20px;
			width: auto;
			text-transform: uppercase;
			font-weight: 600;
			border-radius: 4px;
			font-size: 16px;
			line-height: 1.42;
			margin: 5px 0;
			letter-spacing: 0;
			text-decoration: none;
			display: inline-block;
			cursor: pointer;
			overflow-wrap: break-word;
			transition: all 0.2s;
			vertical-align: middle;
		}

		.submitRepasse:hover {
			background: #111111;
			text-decoration: none;
		}
		.paymentValue{
			display: flex;
    		flex-direction: column;
			padding:5px 0px;
		}
		.paymentValue span{
			padding: 0px 15px;
			white-space: nowrap;
}
		}
	</style>
	<div class="tableEvents">
		<?php foreach ($event_id as $key => $id) {
			$totalPixTax = 0;
			$totalCartTax = 0;
			$totalBoletoTax = 0;
		?>

			<div class="event">
				<div class="eventHeader">
					<div class="eventHeaderCell titleEvent">
						<?php echo $event_title[$key]; ?>
					</div>
				</div>
				<div class="eventBody">
					<?php
					$eventMetaData = get_post_meta($event_id[$key]);
					$unserializedPaidTickets = unserialize($eventMetaData['_paid_tickets'][0]);
					$unserializedFreeTickets = unserialize($eventMetaData['_free_tickets'][0]);
					?>
					<div class='Products'>
						<div class="ProductsHeader">
							<div class="ProductsHeaderCell">
								Ingresso
							</div>
							<div class="ProductsHeaderCell">
								Tipo
							</div>
							<div class="ProductsHeaderCell sells">
								Vendidos
							</div>
							<div class="ProductsHeaderCell">
								Subtotal
							</div>
						</div>
						<?php
						$paidLastTicket = count($unserializedPaidTickets);
						$LastTicketVerify = 0;
						foreach ($unserializedPaidTickets as $kkey => $productID) {
							$LastTicketVerify++;
							$product = wc_get_product($productID['product_id']);
							$pproductID = $productID['product_id'];
							$orders_statuses = "'wc-completed'";
							$orders = $wpdb->get_col(
								"
        SELECT DISTINCT woi.order_id
        FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
             {$wpdb->prefix}woocommerce_order_items as woi, 
             {$wpdb->prefix}posts as p
        WHERE  woi.order_item_id = woim.order_item_id
        AND woi.order_id = p.ID
        AND p.post_status IN ( $orders_statuses )
        AND woim.meta_key IN ( '_product_id', '_variation_id' )
        AND woim.meta_value LIKE '$pproductID'
        ORDER BY woi.order_item_id DESC"
							);
							$ordersTotal = [];
							$ordersItensKey = [];
							$ordersPaymentMethod = [];
							$ordersDiscountTotal = [];
							foreach ($orders as $kkkkey => $orderID) {
								$order = wc_get_order($orderID);
								array_push($ordersTotal, $order->get_total());
								array_push($ordersItensKey, $order->get_item_count());
								array_push($ordersPaymentMethod, $order->get_payment_method_title());
								array_push($ordersDiscountTotal ,$order->get_discount_total());
							}
							$totalIngresso = 0;
							$pixValue = 0;
							$cartValue = 0;	
							$boletoValue = 0;
							$pixTax = 0;
							$cartTax = 0;
							$boletoTax = 0;
							foreach ($ordersTotal as $orderKey => $orderValue) {
								if ($ordersPaymentMethod[$orderKey] == "Pix") {
									$pixValue += $orderValue;
									$temp = $orderValue * 0.1 ;
									$pixTax += ($temp - ($orderValue * 0.0099));
								} else if ($ordersPaymentMethod[$orderKey] == "Débito e crédito") {
									$cartValue += $orderValue;
									$temp = $orderValue * 0.1;
									$cartTax += ($temp - ($orderValue * 0.0498));
								} else {
									$boletoValue += $orderValue;
									$temp = $orderValue * 0.1 ;
									$boletoTax += ($temp - ($orderValue * 0.0349));
								}
							}
							$totalPixTax += $pixTax;
							$totalCartTax += $cartTax;
							$totalBoletoTax += $boletoTax;
							$comCoupom = 0;
							$semCoupom = 0;
							foreach ($ordersTotal as $kkkey => $orderTotal) {
								if($ordersItensKey[$kkkey] > 1){
									if($ordersDiscountTotal[$kkkey] > 0){
									$comCoupom += $ordersItensKey[$kkkey];
								}else{
									$semCoupom += $ordersItensKey[$kkkey];
								}	
								}else{
									if($ordersDiscountTotal[$kkkey] > 0){
									$comCoupom++;
								}else{
									$semCoupom++;
								}	
								}
							}
							?>
							<div class="product">
								<div class="productBodyCell productName">
									<?php echo $product->get_name(); ?>
								</div>
								<div class="productBodyCell productType">
									Ingresso Pago
								</div>
								<div class="productBodyCell totalSales">
									<?php $totalAmount = $product->get_total_sales() + $product->get_stock_quantity();
									echo "<span><b>" . "Com Desconto: </b>" . $comCoupom . "/" . $totalAmount . "</span>";
									echo "<span><b>" . "Sem Desconto: </b>" . $semCoupom . "/" . $totalAmount . "</span>";
									?>
								</div>
								<div class="productBodyCell subtotal" style='white-space: nowrap;'>
									<?php
									$tt = 0;
									foreach ($ordersTotal as $lkey => $total) {
										$tt += $total;
									}
									echo "<span>" . "R$ " . number_format($tt, 2, ',', '.') . "</span>";
									$totalIngresso += $tt;
									?>
								</div>
							</div>
						<div class="details">
							<div class="detailsHeader">
								<div class="detailsHeaderCell paymentMethods">
									Pix
								</div>
								<div class="detailsHeaderCell paymentMethods" style='width: 35%; white-space: nowrap;'>
									Débito/Crédito
								</div>
								<div class="detailsHeaderCell paymentMethods">
									Boleto
								</div>
								<div class="detailsHeaderCell paymentMethods" style='width:25%;'>
									Total
								</div>
							</div>
							<div class="detailsBody">
								<div class="detailsBodyCell paymentValue">
									<?php echo "<span>R$ " . number_format($pixValue, 2, ',', '.'). "</span>"; ?>
									<span style='background: black; color:white; width:100%; height: 20px;'>Taxas</span>
									<?php echo "<span>R$ " . number_format($pixTax, 2, ',', '.') . "</span>"; ?>
								</div>
								<div class="detailsBodyCell paymentValue" style='width: 35%; white-space: nowrap;'>
									<?php echo "<span>R$ " . number_format($cartValue, 2, ',', '.'). "</span>"; ?>
									<span style='background: black; color:white; width:100%; height: 20px;'></span>
									<?php echo "<span>R$ " . number_format($cartTax, 2, ',', '.') . "</span>"; ?>
								</div>
								<div class="detailsBodyCell paymentValue">
									<?php echo "<span>R$ " . number_format($boletoValue, 2, ',', '.') . "</span>" ; ?>
									<span style='background: black; color:white; width:100%; height: 20px;'></span>
									<?php echo "<span>R$ " . number_format($boletoTax, 2, ',', '.') . "</span>" ; ?>
								</div>
								<div class="detailsBodyCell paymentValue" style='width:25%;'>
									<?php echo "<span>R$ " . number_format($totalIngresso, 2, ',', '.') . "</span>"; ?>
									<span style='background: black; color:white; width:100%; height: 20px; padding:0px;'></span>
									<?php $totalTax = $boletoTax + $cartTax + $pixTax; ?>
									<?php echo "<span>R$ " . number_format($totalTax, 2, ',', '.') . "</span>"; ?>
								</div>
							</div>
						</div>
						<?php
						}
						$subtotal = 0;
						foreach ($unserializedFreeTickets as $kkey => $productID) {
							$product = wc_get_product($productID['product_id']);
							?>
							<div class="product">
								<div class="productBodyCell productName">
									<?php echo $product->get_name(); ?>
								</div>
								<div class="productBodyCell productType">
									Ingresso Gratuito
								</div>
								<div class="productBodyCell totalSales">
									<?php $totalAmount = $product->get_total_sales() + $product->get_stock_quantity();
									echo $product->get_total_sales() . "/" . $totalAmount; ?>
								</div>
								<div class="productBodyCell subtotal">
									<?php
									$subtotal = $product->get_price() * $product->get_total_sales();
									echo "R$ " . number_format($subtotal, 2, ',', '.');
									?>
								</div>
							</div>
						<div class="details">
							<div class="detailsHeader">
								<div class="detailsHeaderCell paymentMethods">
									
								</div>
								<div class="detailsHeaderCell paymentMethods" style='width: 35%; white-space: nowrap;'>
									
								</div>
								<div class="detailsHeaderCell paymentMethods">
									
								</div>
								<div class="detailsHeaderCell paymentMethods" style='width:25%;'>
									
								</div>
							</div>
							<div class="detailsBody">
								<div class="detailsBodyCell paymentValue">
									<span style='background: black; color:white; width:100%; height: 20px;'>Ingresso Gratuito</span>
								</div>
								<div class="detailsBodyCell paymentValue" style='width: 35%; white-space: nowrap;'>
									<span style='background: black; color:white; width:100%; height: 20px; padding:0px;'></span>
								</div>
								<div class="detailsBodyCell paymentValue" style='width:25%;'>
									<span style='background: black; color:white; width:100%; height: 20px;'></span>
								</div>
								<div class="detailsBodyCell paymentValue">
									<span style='background: black; color:white; width:100%; height: 20px; padding:0px;'></span>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php
							$repasse = $wpdb->get_results("
    SELECT * 
    FROM  {$wpdb->prefix}repasses
	WHERE evento = '$id'
");
							?>
							<div class="bdRepasse">
								<div class="details">
							<div class="detailsHeader">
								<div class="detailsHeaderCell paymentMethods">
									Pix
								</div>
								<div class="detailsHeaderCell paymentMethods" style='width: 35%; white-space: nowrap;'>
									Débito/Crédito
								</div>
								<div class="detailsHeaderCell paymentMethods">
									Boleto
								</div>
								<div class="detailsHeaderCell paymentMethods" style='width:25%;'>
									Total
								</div>
							</div>
							<div class="detailsBody">
								<div class="detailsBodyCell paymentValue">
									<?php echo "<span>R$ " . number_format($totalPixTax, 2, ',', '.') . "</span>"; ?>
								</div>
								<div class="detailsBodyCell paymentValue" style='width: 35%; white-space: nowrap;'>
									<?php echo "<span>R$ " . number_format($totalCartTax, 2, ',', '.') . "</span>"; ?>
								</div>
								<div class="detailsBodyCell paymentValue" style='width:25%;'>
									<?php echo "<span>R$ " . number_format($totalBoletoTax, 2, ',', '.') . "</span>" ; ?>
								</div>
								<div class="detailsBodyCell paymentValue">
									<?php $totalTax = $totalBoletoTax + $totalCartTax + $totalPixTax; ?>
									<?php echo "<span>R$ " . number_format($totalTax, 2, ',', '.') . "</span>"; ?>
								</div>
							</div>
						</div>
								<form method='post' action='' enctype='multipart/form-data' id="<?php echo $id; ?>"
									style="display:flex;align-items: center;">
									<label style="font-size: 16px; margin-left: 10px;">
										Valor Repassado:
										<?php
										if ($repasse) {
											foreach ($repasse as $repasseKey => $repasseValue) {
											?>
												<input class="repasseValue" value="<?php echo $repasseValue->valor; ?>" name="repasseValue">
												<input value="update" name="insertupdate" hidden>
													<?php		
											}
										} else {
											?>
											<input class="repasseValue" value="0" name="repasseValue">
											<input value="insert" name="insertupdate" hidden>
											<?php
										}
										?>
									</label>
									<button type='button' id="submitRepasse_<?php echo $id; ?>" class="submitRepasse">
										Salvar
									</button>
								</form>
							</div>
							<script>
		jQuery("#submitRepasse_<?php echo $id; ?>").click(function (e) {
										e.preventDefault();
										jQuery.ajax({
											type: "POST",
											url: ajaxurl,
											data: "action=updateRepasse&event=<?php echo $id; ?>&" + jQuery("#<?php echo $id; ?>").serialize(),
											success: function (msg) {
												console.log('success');
												console.log(msg)
												window.location.reload();
											}
										});
									})
							</script>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
}
?>