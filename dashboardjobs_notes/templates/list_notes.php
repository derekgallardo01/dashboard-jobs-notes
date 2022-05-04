<?php

//print_r($location); ?>

<div class="postbox mainbox">
<div class="event_head2"><?php echo $details[52];?></div>
<div class="toprow"><div class="leftcol"><a class="toplink" href="javascript(void)">EVENT DETAIL</a></div></div>

<div class="divrow"><div class="leftcol">

<div class="row"><span class="lochead">Location #<?php echo $_GET["lid"]; ?></span></div>
<div class="row"><span class="subhead">On Site Contact Persons First Name: 
							</span><span class="locvalue"><?php echo $details[33];?></span>
							</div>
	
<div class="row"><span class="subhead">On Site Contact Persons Last Name: 
							</span><span class="locvalue"><?php echo $details[49];?></span>
							</div>

<div class="row"><span class="subhead">On Site Contact Persons Phone Number: 
							</span><span class="locvalue"><?php echo $details[34];?></span>
							</div>

<div class="row"><span class="subhead">On Site Contact Persons Email: 
							</span><span class="locvalue"><?php echo $details[44];?></span>
							</div>

<div class="row"><span class="subhead">Date: 
							</span><span class="locvalue"><?php 
							$sep = '';
							foreach($datetime as $dt){
								echo $sep.date('M d, Y',strtotime($dt->month.'/'.$dt->day.'/'.$dt->year));
								$sep = ', ';
							};?></span>
							</div>

<div class="row"><span class="subhead">Time: 
							</span><span class="locvalue"><?php 
							$sep = '';
							$total_time = 0;
							foreach($datetime as $dt){
								echo $sep.$dt->stime.' To '.$dt->etime;
								$sep = ', ';
								$total_time = $dt->etime-$dt->stime;
							}
							?></span>
							</div>

<div class="row"><span class="subhead">Address: 
							</span><span class="locvalue"><?php echo $details[8];?></span>
							</div>

<div class="row"><span class="subhead">Zip Code: 
							</span><span class="locvalue"><?php echo $details[51];?></span>
							</div>

<div class="row"><span class="subhead">City: 
							</span><span class="locvalue"><?php echo $details[9];?></span>
							</div>

<div class="row"><span class="subhead">State: 
							</span><span class="locvalue"><?php echo $details[10];?></span>
							</div>

<div class="row"><span class="subhead">Time Zone: 
							</span><span class="locvalue"><?php echo $details[28];?></span>
							</div>
<div class="row"><span class="subhead">Hourly Pay: 
							</span><span class="locvalue"><?php echo $details[53];?></span>
							</div>

<div class="row"><span class="subhead">Total # of Therapist: 
							</span><span class="locvalue"><?php echo $details[7];?></span>
							</div>

<div class="row"><span class="subhead">Total # of Massage Hours: 
							</span><span class="locvalue"><?php echo $total_time * $details[7] ; //$details[30] * $details[7];?></span>
							</div>							

<div class="row"><span class="subhead">Invited Therapist: 
							</span><span class="locvalue"><?php echo count($users_inviteds);?></span>
							</div>	
							
<div class="row"><span class="subhead">Billing Status: 
							</span><span class="locvalue"><?php ?></span>
							</div>	
							
<div class="row"><span class="subhead">Invitation Request: 
							</span><span class="locvalue"><?php echo count($users_inviteds); ?></span>
							</div>	
							
<div class="row"><span class="subhead">Invoice Total: 
							</span><span class="locvalue"><?php echo $details[30]*$details[53]*$details[7];?></span>
							</div>


<div class="row"><span class="subhead">Parking Instructions: 
							</span><span class="locvalue">

							<textarea name="input_68" id="input_11_68" class="textarea small" aria-invalid="false" rows="5" cols="40"><?php echo $details[68];  //$details[30];?></textarea>

							</span>
							</div>

<div class="row"><span class="subhead">Meet & Greet Information: 
							</span><span class="locvalue">

							<textarea name="input_67" id="input_11_67" class="textarea small" aria-invalid="false" rows="5" cols="40"><?php echo $details[67];  //$details[30];?></textarea>


							</span>
							</div>

<div class="row"><span class="subhead">Documents: 
							</span><span class="locvalue"><input type="file" name="66" value="<?php echo $details[66];  //$details[30];?>"></span>
							</div>

</div>
				
				<div class="rightcol">
					<div>
					<?php 
					$lang = isset($lang)?$lang:'en';
					/* ?><a href="javascript:void(0);" class="button-secondary" onclick="blViewDatesEvents(this,<?php echo $location->ID; ?>);"><?php _e('View dates', $lang); ?></a><? */ ?>
					<a href="admin.php?page=edit_location&lid=<?php echo $_GET["lid"]; ?>" class="mainlinkred" ><?php _e('EDIT EVENT DETAILS', $lang); ?></a>
					</div>
					<p></p>											<div>											
					<a class="mainlinkred" href="admin.php?page=gf_entries&view=<?php echo $_GET["view"];?>&id=<?php echo $_GET["id"];?>&lid=<?php echo $_GET["lid"];?>&leid=<?php echo $_GET["leid"];?>&dir=<?php echo $_GET["dir"];?>&filter&paged=<?php echo $_GET["paged"];?>&pos=<?php echo $_GET["pos"];?>&field_id&operator&archive_one_location=yes&loid=<?php echo $_GET["lid"]; ?>"  onclick="return confirm('Do you want archive this location?')"><?php _e('ARCHIVE EVENT', $lang); ?></a></div> 											
					<p></p>											<div>											
					<a class="mainlinkred" href="admin.php?page=gf_entries&view=<?php echo $_GET["view"];?>&id=<?php echo $_GET["id"];?>&lid=<?php echo $_GET["lid"];?>&leid=<?php echo $_GET["leid"];?>&dir=<?php echo $_GET["dir"];?>&filter&paged=<?php echo $_GET["paged"];?>&pos=<?php echo $_GET["pos"];?>&field_id&operator&delete_location=yes&loid=<?php echo $_GET["lid"]; ?>"  onclick="return confirm('Do you want delete this location?')" ><?php _e('DELETE EVENT', $lang); ?></a></div>
					
					<p></p>											<div>											
					<a class="mainlinkred" href="admin.php?page=gf_entries&view=entry&id=<?php echo $_GET["id"];?>&lid=<?php echo $_GET["lid"];?>&leid=<?php echo $_GET["leid"];?>&dir=DESC&filter&paged=1&pos=0&field_id&operator#box_manage_jobs"  ><?php _e('BACK', $lang); ?></a></div>
					
				</div>
				</div>
				

<?php
/*
echo '<div class="divrow"><div class="leftcol">';
$sql_loc = "update ".$wpdb->prefix."location_invoice set paid_emailid= '".$_REQUEST["ssl_invoice_number"]."', paid_amount= '".$_REQUEST["ssl_invoice_number"]."', paid_date= '".$_REQUEST["ssl_invoice_number"]."', paid_userid= '".$_REQUEST["ssl_invoice_number"]."',   WHERE invoice_number=".$_REQUEST["ssl_invoice_number"];

$invoices = $wpdb->get_results($sql_loc);
//ssl_invoice_number
$sql_loc = "SELECT * FROM ".$wpdb->prefix."location_invoice WHERE location_id=".$_GET["lid"];
$invoices = $wpdb->get_results($sql_loc);

?>
<div class="postbox mainbox">
<table cellspacing="10" cellpadding="0" border="0">
<tr>
	<td class="nameheading">Date</td>
	<td class="nameheading">Amount</td>
	<td class="nameheading">Paid Amount</td>
	<td class="nameheading">Paid By</td>
	<td class="nameheading">Status</td>
	<td class="nameheading">Invoice Link</td>
	
	</tr>
<?php

foreach($invoices as $invoicedata){
	$invoicedata = (array)$invoicedata;
	$sql_loc = "SELECT * FROM ".$wpdb->prefix."users WHERE id=".$invoicedata["paid_userid"];

	$paiduser = $wpdb->get_results($sql_loc);
	$upload_dir = wp_upload_dir();
    $avatar_url = $upload_dir['baseurl'];
	?>
	<tr>
	<td><?php echo date('m d, Y',strtotime($invoicedata["invoice_date"])); ?></td>
	<td><?php echo $invoicedata["invoice_amount"]; ?></td>
	<td><?php echo $invoicedata["paid_amount"]; ?></td>
	<td><?php echo $invoicedata["paid_emailid"]; ?></td>
	<td><?php echo $invoicedata["paid_status"]; ?></td>
	<td><a target="_blank" href="<?php echo $avatar_url.'/pdf/'.$invoicedata["file_name"]; ?>">Invoice</a></td>
	</tr>
	<?php
}


echo '
</table>
</div>
</div>
</div>';
*/
?>




<div class="divrow"><div class="leftcol">
<table border="0">
<tr>
<td><span class="nameheading">Therapist Names</span></td>
<td><span class="nameheading">Email Id</span></td>
<td><span class="nameheading">Phone</span></td>

<td><span class="nameheading">Invitation Status</span></td>
<td><span class="nameheading"></span></td>
</tr>

<?php
if(!empty($users_inviteds)){
       foreach($users_inviteds as $id=>$therapist) {
		$user_info = get_userdata($id);
		$user_email = isset($user_info->user_email)?$user_info->user_email:'';
		
		$phone  = get_user_meta($id, 'mobile_phone_number', true);
		
	    echo '<tr><td><span class="simpletext">'.str_replace('View address','',$therapist).'</span></td><td><span class="simpletext">' . $user_email . '</span></td><td><span class="simpletext">' . $phone . '</span></td><td>';
		
		if( isset($status_accept[$id]) ) {
			echo '<span class="selectedstat">Accepted</span>';
		} else {
			echo '<span class="simplestat">Pending</span>';
			
            
			/* foreach($status_accept[$id] as $location_date_id) {
				$objLocDate = AJ_Location::getEventDateLocation($location_date_id);
				if($objLocDate) {
					$d = strtotime($objLocDate->year.'-'.$objLocDate->month.'-'.$objLocDate->day.' '.$objLocDate->stime);
				}

			} */
			//echo $txt;//$therapist;
		}
		
		echo '</td><td> &nbsp;&nbsp;&nbsp;&nbsp; <span class="selectedstat"><a href="admin.php?page=removeinvitedtherapist&id='.$id.'&lid='.$_GET["lid"].'&view=entry&form_id='.$_GET["id"].'&leid='.$_GET["leid"].'&dir=DESC&filter&paged=1&pos=2"  onclick="return confirm(\'Do you want delete this therapist?\')"><img src="'.esc_url( plugins_url( 'Sign-Error-icon.png', dirname(__FILE__) ) ) .'" /></a></span>';
		
echo '</td></tr>';
	}
}
                   
?>

</table>	


		
</div>
</div>	

					



	<?php /*?>&nbsp; &nbsp;
		<label for="name">
		<select name="archive_note" id="archive_note" onchange="location.href='admin.php?page=view_notes&view=<?php echo $_GET["view"];?>&id=<?php echo $_GET["id"];?>&lid=<?php echo $_GET["lid"];?>&dir=<?php echo $_GET["dir"];?>&filter&paged=<?php echo $_GET["paged"];?>&pos=<?php echo $_GET["pos"];?>&field_id&operator&archive_note=<?php echo $_GET["archive_note"];?>&archive_dir=<?php echo $_GET["archive_dir"];?>&archive_year='+this.value">
		<option value="">View All Notes</option>
		<?php
		$n_year = date('Y')-10;
		for($ty = $n_year;$ty<=date('Y');$ty++){
		
			?>                            
			<option value="<?php echo $ty;?>" <?php if($ty == $_GET["archive_year"] ){ ?> selected="selected" <?php } ?> ><?php echo $ty;?></option>
			<?php
			}
		?>
		</select>
		</label><? */ ?>
</div>
<div class="">&nbsp;</div>

<div class="postbox mainbox">

<div class="event_head2">Event Notes</div>
<div class="toprow"><div class="leftcol"><a class="toplink" href="<?php

		if(isset($_GET["archive_note"]) && $_GET["archive_note"]=='yes'){
			
		?>admin.php?page=view_location&lid=<?php echo $_GET["lid"];?>&archive_note=no<?php 
		$titlenote = 'VIEW NOTES';
		}else{
			
			?>admin.php?page=view_archive_notes&lid=<?php echo $_GET["lid"];?>&archive_note=yes<?php
			$titlenote = 'VIEW ARCHIVED NOTES';
		}
		
		?>"><?php echo $titlenote;?></a></div></div>
		
	<form method="post">
	<input type="hidden" name="action" id="action" value="" />
	<input type="hidden" name="lid" id="lid" value="<?php echo $_GET["lid"]?>" />
		<input type="hidden" name="screen_mode" id="screen_mode" value="<?php echo esc_attr( rgpost( 'screen_mode' ) ) ?>" />

		<input type="hidden" name="entry_id" id="entry_id" value="<?php echo absint( $lead['id'] ) ?>" />

		
		<?php wp_nonce_field( 'gforms_update_note', 'gforms_update_note' ) ?>
		<div class="inside">
			<?php
			
			$notes = Manage_jobs_notes::get_job_notes( $_GET["lid"] );
			//getting email values
			
			$emails = array();
			/* $email_fields = GFCommon::get_email_fields( $form );
			foreach ( $email_fields as $email_field ) {
				if ( ! empty( $lead[ $email_field->id ] ) ) {
					$emails[] = $lead[ $email_field->id ];
				}
			} */
			//displaying notes grid
			$subject = '';
			Manage_jobs_notes::notes_grid( $notes, true, $emails, $subject );
			?>
		</div>
	</form>
</div>
