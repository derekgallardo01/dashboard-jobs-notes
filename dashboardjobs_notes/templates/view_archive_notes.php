<?php

/**
 * Template to Meta Box show to the Jobs available
 **/
?>
<div id="box_manage_jobs" class="stuffbox" >
<div class="eventarchive_head">NOTE ARCHIVES <span class="admlogo" > <img src="<?php header_image(); ?>" height="44px" alt="lo" /></span> </div>

<form action="" name="archive_location" id="archive_location"  >
<div class="event_search">

<input type="hidden" name="page" id="page" value="<?php echo $_GET["page"];?>" />
<input type="hidden" name="lid" id="lid" value="<?php echo $_GET["lid"];?>" />
<input type="hidden" name="archive_note" id="archive_note" value="<?php echo $_GET["archive_note"];?>" />

<span class="year"> ENTER YEAR </span><input name="archive_year" value="<?php echo $_GET["archive_year"];?>" /> 
<?php
$year = (isset($_GET["archive_year"]) && $_GET["archive_year"]!='')?$_GET["archive_year"]:date('Y');
$pagelink = 'admin.php?page='.$_GET["page"].'&lid='.$_GET["lid"].'&archive_note=yes&archive_year='.$year.'';
for($i=1;$i<13;$i++)
print("<a href='".$pagelink."&month=".$i."'><span class='month'>".date('F',strtotime('01.'.$i.'.2001'))."</span></a>");

?>
</div>
</form>
</div>




<div class="postbox mainbox">

<div class="bottomrow">
<div class="leftcol">
<span class="eventarchive_head">
<?php
if(isset($_GET["month"])){
	echo date('F',strtotime('01.'.$_GET["month"].'.2001'));
	
}
?>
</span>
</div>
<div class="rightcol">
<div class="lochead">
<?php
echo $lead[14].' '.$lead[48];

?>
</div>

<div class="lochead">
<?php
echo 'Submit Job Entry # '.$_GET["lid"];
//print_r($lead);
?>
</div>
</div>
</div>


<div class="bottomrow">
	<form method="post">
	<input type="hidden" name="action" id="action" value="" />
	<input type="hidden" name="lid" id="lid" value="<?php echo $_GET["lid"]?>" />
		<input type="hidden" name="screen_mode" id="screen_mode" value="<?php echo esc_attr( rgpost( 'screen_mode' ) ) ?>" />

		<input type="hidden" name="entry_id" id="entry_id" value="<?php echo absint( $lead['id'] ) ?>" />

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
			Manage_jobs_notes::list_archive_notes( $notes, true, $emails, $subject );
			?>
		</div>
	</form>
</div>
</div>
