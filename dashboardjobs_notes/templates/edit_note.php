<?php

$notedetail = Manage_jobs_notes::getnote( $_GET["nid"] );
//print_r($notedetail);

if(isset($_SERVER["HTTP_REFERER"]) && strstr($_SERVER["HTTP_REFERER"],'view_location')){
$page = 'view_location';		
}else{
$page = 'view_archive_notes';		
}

?>

<div class="postbox mainbox">

<div class="event_head2">Edit Note</div>
	
	<form method="post">
	
		<input type="hidden" name="screen_mode" id="screen_mode" value="update_note" />
		<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $page;?>" />
		<input type="hidden" name="job_id" id="job_id" value="<?php echo isset($notedetail[0])?$notedetail[0]->job_id:''; ?>" />
		<input type="hidden" name="note_id" id="note_id" value="<?php echo isset($notedetail[0])?$notedetail[0]->id:''; ?>" />
		<textarea name="new_note" rows="5" cols="120"><?php echo isset($notedetail[0])?$notedetail[0]->value:''; ?></textarea>
		<br />
		<br />
		<input type="submit" name="add_note" value="Save Note" class="redbutton button" style="width:auto;padding-bottom:2px;" onclick="jQuery('#action').val('location_add_note');"/>

	</form>
</div>
