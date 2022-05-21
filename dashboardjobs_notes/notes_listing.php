<?php
/*

  Plugin Name: Manage Location Notes

  Plugin URI: https://webcoastcustoms.com/

  Description: 

  Version: 1.0.0

  Author: Derek - webcoastcustoms.com

  Author URI: http://smadit.com/

 */
 
 
 class Manage_jobs_notes
 {
	 
	 function __construct() {
		
        $this->init();
		
    }
	
	
    function init() {
	////view location notes	
	add_action('admin_menu', array($this,'wpdocs_register_viewnotes_page'));
	
	
	if ( ( defined('IS_ADMIN') && false === ( defined( 'DOING_AJAX' ) && true === DOING_AJAX ) ) || is_multisite() ) {
			self::setupdatabase();
		}
	
	
	
	}
	
	public static function add_note( $lid, $user_id, $user_name, $note, $note_type = 'note' ) {
		global $wpdb;

		$table_name = self::get_job_notes_table_name();
		$sql        = $wpdb->prepare( "INSERT INTO $table_name(job_id, user_id, user_name, value, note_type, date_created) values(%d, %d, %s, %s, %s, utc_timestamp())", $lid, $user_id, $user_name, $note, $note_type );

		$wpdb->query( $sql );

		do_action( 'gform_post_lnote_added', $wpdb->insert_id, $lid, $user_id, $user_name, $note, $note_type );
	}
	
	public static function getnote($noteid){
		global $wpdb;
		$table_name = self::get_job_notes_table_name();
		
		$note_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $noteid ) );
		return $note_data;
		
	}
	
	public static function delete_note( $note_id ) {
		global $wpdb;

		$table_name = self::get_job_notes_table_name();

		$lead_id = $wpdb->get_var( $wpdb->prepare( "SELECT lead_id FROM $table_name WHERE id = %d", $note_id ) );

		/**
		 * Fires before a note is deleted
		 *
		 * @param int $note_id The current note ID
		 * @param int $lead_id The current lead ID
		 */
		do_action( 'gform_pre_note_deleted', $note_id, $lead_id );

		$sql        = $wpdb->prepare( "DELETE FROM $table_name WHERE id=%d", $note_id );
		$wpdb->query( $sql );
	}

	public static function delete_notes( $notes ) {
		if ( ! is_array( $notes ) ) {
			return;
		}

		foreach ( $notes as $note_id ) {
			self::delete_note( $note_id );
		}
	}
	
	public static function archive_note( $note_id ) { 
		global $wpdb;

		$table_name = self::get_job_notes_table_name();

		$lead_id = $wpdb->get_var( $wpdb->prepare( "SELECT lead_id FROM $table_name WHERE id = %d", $note_id ) );

		/**
		 * Fires before a note is deleted
		 *
		 * @param int $note_id The Current note ID
		 * @param int $lead_id The current lead ID
		 */
		do_action( 'gform_pre_note_deleted', $note_id, $lead_id );

		$sql        = $wpdb->prepare( "UPDATE $table_name set archive = 1, archive_dir = '".trim($_POST["archive_dir"])."' WHERE id=%d", $note_id );
		$wpdb->query( $sql );
	}
	
	public static function archive_notes( $notes ) {
		if ( ! is_array( $notes ) ) {
			return;
		}

		foreach ( $notes as $note_id ) {
			self::archive_note( $note_id );
		}
	}
	
	public static function notes_grid( $notes, $is_editable, $emails = null, $subject = '' ) {
		
		?>
		<table class=" fixed entry-detail-notes" cellspacing="0">
			<?php
			if ( ! $is_editable ) {
				?>
				<thead>
				<tr>
					<th id="notes">Notes</th>
				</tr>
				</thead>
			<?php
			}
			?>
			<tbody class="list:comment">
			<?php
			
			if ( $is_editable && GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
				?>
				<tr>
					<td  style="padding:10px 5px;">
						<textarea name="new_note" rows="5" cols="120"></textarea>
						<br />
						<br />
						
						<?php
						$note_button = '<input type="submit" name="add_note" value="' . esc_attr__( 'Add Note', 'gravityforms' ) . '" class="redbutton button" style="width:auto;padding-bottom:2px;" onclick="jQuery(\'#action\').val(\'location_add_note\');"/>';

						/**
						 * Allows for modification of the "Add Note" button for Entry Notes
						 *
						 * @param string $note_button The HTML for the "Add Note" Button
						 */
						echo apply_filters( 'gform_addnote_button', $note_button );

						if ( ! empty( $emails ) ) {
							?>
							&nbsp;&nbsp;
							<span>
                                <select name="gentry_email_notes_to" onchange="if(jQuery(this).val() != '') {jQuery('#gentry_email_subject_container').css('display', 'inline');} else{jQuery('#gentry_email_subject_container').css('display', 'none');}">
									<option value=""><?php esc_html_e( 'Also email this note to', 'gravityforms' ) ?></option>
									<?php foreach ( $emails as $email ) { ?>
										<option value="<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></option>
									<?php } ?>
								</select>
                                &nbsp;&nbsp;

                                <span id='gentry_email_subject_container' style="display:none;">
                                    <label for="gentry_email_subject"><?php esc_html_e( 'Subject:', 'gravityforms' ) ?></label>
                                    <input type="text" name="gentry_email_subject" id="gentry_email_subject" value="" style="width:100%" />
                                </span>
                            </span>
						<?php } ?>
					</td>
				</tr>
			<?php
			}
			
			$count = 0;
			$notes_count = sizeof( $notes );
			foreach ( $notes as $note ) {
				
				$count ++;
				$is_last = $count >= $notes_count ? true : false;
				?>
				<tr valign="top">
				
				<td colspan="2">
				<div class="note_head">&nbsp;</div>
				<div class="note_head">Note # <?php echo $count;?></div>
				<div class="simpletext"><?php esc_html_e( 'Added on', 'gravityforms' ); ?> <?php echo esc_html( GFCommon::format_date( $note->date_created, false ) ) ?></div>
<div class="looprow"><div class="leftcol"><a class="toplink" href="admin.php?page=edit_note&nid=<?php echo $note->id;?>" >EDIT NOTE</a></div>
<?php
if(isset($_GET["archive_note"])!='yes'){?>
<div class="rightcol"><a  class="archivenote" href="admin.php?page=view_location&lid=<?php echo $_GET["lid"];?>&bulk_action=archive&note_id=<?php echo $note->id;?>"  onclick="return confirm('Do you want to archive this note?')">ARCHIVE NOTE</a></div>
<?php } ?>
</div>
				
				</td>
				</tr>
				
				<tr valign="top">
					<td class="entry-detail-note<?php echo $is_last ? ' lastrow' : '' ?>">
					<?php
					$class = (isset($note->note_type) &&  $note->note_type) ? " gforms_note_{$note->note_type}" : '';
					?>
						<div style="margin-top:4px;">
							<div class="note-avatar"><?php
								/**
								 * Allows filtering of the notes avatar
								 *
								 * @param array $note The Note object that is being filtered when modifying the avatar
								 */
								//echo apply_filters( 'gform_notes_avatar', get_avatar( $note->user_id, 48 ), $note ); ?></div>
							<h6 class="note-author">
							<?php //echo esc_html( $note->user_name ) ?>
							<?php 
								if(isset($_GET["archive_note"]) && $_GET["archive_note"]=='yes'){
								//echo ' &nbsp; - &nbsp; <span style="background:#EEE; width:50px">'.esc_html($note->archive_dir).'</span>';
								}
								
								?>
							</h6>
							<!--<p class="note-email">
								<a href="mailto:<?php echo esc_attr( $note->user_email ) ?>"><?php echo esc_html( $note->user_email ) ?></a><br />
								
							</p>-->
						</div>
						<div class="detail-note-content<?php echo $class ?>"><?php echo nl2br( ( $note->value ) ) ?></div>
					</td>
					
					<td>
					<a href="admin.php?page=view_location&lid=<?php echo $_GET["lid"];?>&bulk_action=delete&note_id=<?php echo $note->id;?>" class="redbutton btn" onclick="return confirm('Do you want to delete this note?')"> DELETE NOTE</a>
					</td>
					
				</tr>
			<?php
			}
			
			?>
			</tbody>
		</table>
	<?php
	}
	
	
	public static function list_archive_notes( $notes, $is_editable, $emails = null, $subject = '' ) {
		
		?>
		<table class=" fixed entry-detail-notes" cellspacing="0">
			
			<tbody class="list:comment">
			<?php
			
			$count = 0;
			$notes_count = sizeof( $notes );
			foreach ( $notes as $note ) {
				$count ++;
				$is_last = $count >= $notes_count ? true : false;
				?>
				<tr valign="top">
				
				<td colspan="2">
				<div class="note_head">&nbsp;</div>
				<div class="note_head">Note # <?php echo $count;?></div>
				<div class="simpletext"><?php esc_html_e( 'Added on', 'gravityforms' ); ?> <?php echo esc_html( GFCommon::format_date( $note->date_created, false ) ) ?></div>
<div class="looprow">
<div class="leftcol"><a class="toplink" href="admin.php?page=edit_note&nid=<?php echo $note->id;?>" >EDIT NOTE</a></div>
<?php
if(isset($_GET["archive_note"])!='yes'){?>
<div class="rightcol"><a  class="archivenote" href="admin.php?page=view_location&lid=<?php echo $_GET["lid"];?>&bulk_action=archive&note_id=<?php echo $note->id;?>"  onclick="return confirm('Do you want to archive this note?')">ARCHIVE NOTE</a></div>
<?php } ?>
</div>
				
				</td>
				</tr>
				
				<tr valign="top">
					<td class="entry-detail-note<?php echo $is_last ? ' lastrow' : '' ?>">
					<?php
					$class = $note->note_type ? " gforms_note_{$note->note_type}" : '';
					?>
						<div style="margin-top:4px;">
							<div class="note-avatar"><?php
								/**
								 * Allows filtering of the notes avatar
								 *
								 * @param array $note The Note object that is being filtered when modifying the avatar
								 */
								//echo apply_filters( 'gform_notes_avatar', get_avatar( $note->user_id, 48 ), $note ); ?></div>
							<h6 class="note-author">
							<?php //echo esc_html( $note->user_name ) ?>
							<?php 
								if(isset($_GET["archive_note"]) && $_GET["archive_note"]=='yes'){
								//echo ' &nbsp; - &nbsp; <span style="background:#EEE; width:50px">'.esc_html($note->archive_dir).'</span>';
								}
								?>
							</h6>
							<!--<p class="note-email">
								<a href="mailto:<?php echo esc_attr( $note->user_email ) ?>"><?php echo esc_html( $note->user_email ) ?></a><br />
								
							</p>-->
						</div>
						<div class="detail-note-content<?php echo $class ?>"><?php echo nl2br( esc_html( $note->value ) ) ?></div>
					</td>
					
					<td class="rightbtn">
					<a href="admin.php?page=view_location&lid=<?php echo $_GET["lid"];?>&bulk_action=delete&note_id=<?php echo $note->id;?>" class="redbutton btn" onclick="return confirm('Do you want to delete this note?')"> DELETE NOTE</a>
					</td>
					
				</tr>
			<?php
			}
			
			?>
			</tbody>
		</table>
	<?php
	}
	
	
	function wpdocs_register_viewnotes_page(){
		
		add_submenu_page( null, 'View Notes', null, 'manage_options', 'view_location', array($this,'view_location') );
		
		add_submenu_page( null, 'Edit Location', null, 'manage_options', 'edit_location', array($this,'edit_location') );
		
		add_submenu_page( null, 'View Archive Notes', null, 'manage_options', 'view_archive_notes', array($this,'view_archive_notes') );
		add_submenu_page( null, '', null, 'manage_options', 'edit_note', array($this,'edit_note') );
		add_submenu_page( null, '', null, 'manage_options', 'removeinvitedtherapist', array($this,'removeinvitedtherapist') );
		
		
		//print_r($_GET);die();
		global $current_user;
				
		if ( isset($_GET['bulk_action']) && $_GET['bulk_action'] == 'delete' ) {
			//check_admin_referer( 'gforms_update_note', 'gforms_update_note' );
			if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
				die( esc_html__( "You don't have adequate permission to delete notes.", 'gravityforms' ) );
			}
			self::delete_note( $_GET['note_id'] );
		}
				
		if ( isset($_GET['bulk_action']) && $_GET['bulk_action'] == 'archive' ) {
			//check_admin_referer( 'gforms_update_note', 'gforms_update_note' );
			if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
				die( esc_html__( "You don't have adequate permission to archive notes.", 'gravityforms' ) );
			}
			
			self::archive_note( $_GET['note_id'] );
		}

		
		if(isset($_POST["action"]) && $_POST["action"]=='bulk'){
			
				check_admin_referer( 'gforms_update_note', 'gforms_update_note' );
				if ( $_POST['bulk_action'] == 'delete' ) {
					if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
						die( esc_html__( "You don't have adequate permission to delete notes.", 'gravityforms' ) );
					}
					self::delete_notes( $_POST['note'] );
				}
				
				if ( $_POST['bulk_action'] == 'archive' ) {
					if ( ! GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
						die( esc_html__( "You don't have adequate permission to archive notes.", 'gravityforms' ) );
					}
					
					self::archive_notes( $_POST['note'] );
				}
		
		}
		//print_r($_POST);die('gg');
		if(isset($_POST["action"]) && $_POST["action"]=='location_add_note'){//die('sadsd');
		check_admin_referer( 'gforms_update_note', 'gforms_update_note' );
				$user_data = get_userdata( $current_user->ID );
				self::add_note( $_POST['lid'], $current_user->ID, $user_data->display_name, stripslashes( $_POST['new_note'] ) );

				//emailing notes if configured
				if ( rgpost( 'gentry_email_notes_to' ) ) {
					GFCommon::log_debug( 'GFEntryDetail::lead_detail_page(): Preparing to email entry notes.' );
					$email_to      = $_POST['gentry_email_notes_to'];
					$email_from    = $current_user->user_email;
					$email_subject = stripslashes( $_POST['gentry_email_subject'] );
					$body = stripslashes( $_POST['new_note'] );

					$headers = "";//"From: \"$email_from\" <$email_from> \r\n";
					GFCommon::log_debug( "GFEntryDetail::lead_detail_page(): Emailing notes - TO: $email_to SUBJECT: $email_subject BODY: $body HEADERS: $headers" );
					$is_success  = wp_mail( $email_to, $email_subject, $body, $headers );
					$result = is_wp_error( $is_success ) ? $is_success->get_error_message() : $is_success;
					GFCommon::log_debug( "GFEntryDetail::lead_detail_page(): Result from wp_mail(): {$result}" );
					if ( ! is_wp_error( $is_success ) && $is_success ) {
						GFCommon::log_debug( 'GFEntryDetail::lead_detail_page(): Mail was passed from WordPress to the mail server.' );
					} else {
						GFCommon::log_error( 'GFEntryDetail::lead_detail_page(): The mail message was passed off to WordPress for processing, but WordPress was unable to send the message.' );
					}

					if ( has_filter( 'phpmailer_init' ) ) {
						GFCommon::log_debug( __METHOD__ . '(): The WordPress phpmailer_init hook has been detected, usually used by SMTP plugins, it can impact mail delivery.' );
					}

				
					do_action( 'gform_post_send_entry_note', $result, $email_to, $email_from, $email_subject, $body, $form, $lead );
				}
				
		}
			
	}
		
	public static function loadTemplate($path_file, $vars = array(), $return = false) {

        global $wp_query;

        $wp_query->query_vars = $vars;

        if($return) {
            ob_start();
            load_template( $path_file );

            return ob_get_clean();

        }

        load_template( $path_file );

    }
	
	
	function view_location(){
	
		$args = array();
		$location = Admin_Jobs::load_location($_GET['lid']);
		//print_r($location);
		$details = maybe_unserialize($location->data);
		$args["location"] = $location;
		$args["details"] = $details;
		//print_r($details);
		
		//$labels_location = get_option('gf_labels_location', array());
		//print_r($labels_location);
		
		$datetime = AJ_Location::getDatesEventByLocation($location->location_id);
		$args["datetime"] = $datetime;
		$users_inviteds = maybe_unserialize($location->users_invited);
		$accept_job = maybe_unserialize($location->accept_job);
		$args["users_inviteds"] = $users_inviteds;
		$args["status_accept"] = $accept_job;
		
		//print_r($users_inviteds);
		//print_r($accept_job);
		
		$this->loadTemplate( dirname( __FILE__ ) . '/templates/list_notes.php', $args);

	}
	
	
	function edit_location(){
		
		if(isset($_POST["location_mode_edit"]) && $_POST["location_mode_edit"]){
		global $wpdb;
		
		$lead_id = $wpdb->get_var( $wpdb->prepare( "SELECT lead_id FROM ".$wpdb->prefix."job_location WHERE ID = %d", $_POST["gform_lid"] ) );
		
		$data_location = $_POST;
		unset($data_location["update_location"]);
		unset($data_location["gform_lid"]);
		unset($data_location["location_mode_edit"]);
		
		//echo date('Y-m-d',strtotime($data_location["date"][0]));die();
		
		
		unset($data_location["date"]);
		unset($data_location["time"]);
		
		/* print_r($data_location);
		die(); */

		if(is_array($_POST["date"])){
			
			$sqldel = "DELETE FROM ".$wpdb->prefix."location_date_event  WHERE location_id=".$_POST["gform_lid"];

			$wpdb->query( $sqldel );
			$i = 0;
			foreach($_POST["date"] as $date){
				
			$sql1 = "INSERT INTO ".$wpdb->prefix."location_date_event set location_id = '".$_POST["gform_lid"]."', day = '".date('d',strtotime($date))."', month = '".date('m',strtotime($date))."', year = '".date('Y',strtotime($date))."', stime = '".$_POST["stime"][$i]."', etime = '".$_POST["etime"][$i]."' ";
			
			$sst = strtotime($_POST["stime"][$i] ).'<br />';
			$eet=  strtotime($_POST["etime"][$i] );
			$diff= $eet-$sst;
			$timeElapsed += round((($diff/60) / 60), 2);
			
			$time_start = date('l jS \of F Y h:i:s A', strtotime($date." ".$_POST["stime"][$i]));
			$time_end = date('l jS \of F Y h:i:s A', strtotime($date." ".$_POST["etime"][$i]));
			
			$wpdb->query( $sql1 );
			$i++;
			}
		}
		
		$data_location[30] = $timeElapsed;
		
		
		$data_location = serialize($data_location);
		
		$sql = "UPDATE ".$wpdb->prefix."job_location set data = '".$data_location."' WHERE id=".$_POST["gform_lid"];

        $wpdb->query( $sql );
		

			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=".get_bloginfo('charset')."" . "\r\n";
			$headers .= "From: Ahh Thats The Spot Massage <".get_bloginfo('admin_email').">" . "\r\n";
			
			
			$location_id = $_POST["gform_lid"];
			
			if($location_id>0){
				
				$sql_loc = "SELECT * FROM ".$wpdb->prefix."job_location WHERE status != 'archive' and status != 'delete' and ID=".$_POST["gform_lid"];

				$locations = $wpdb->get_results($sql_loc);
				if(!empty($locations)){
					
					$jobdata = maybe_unserialize($locations[0]->data);
					//$users_inviteds = maybe_unserialize($locations[0]->users_invited);
					//print_r($jobdata);die();
					$status_accept = maybe_unserialize($locations[0]->accept_job);
					
					//print_r($status_accept);
					if(!empty($status_accept)){
						foreach($status_accept as $id=>$data) {
						
						$user = get_user_by('id', $id);
						
						$user->full_name = $user->first_name ? $user->first_name.($user->last_name ? ' '.$user->last_name : '') : null;
						$email = $user->user_email;
						$name = empty($user->full_name) ? ( empty($user->display_name) ? $email : $user->display_name) : $user->full_name;

						//dev:lamprea: Added placeholders for userlogina dna userpass
						$userlogin = $user->user_login;
						$content_log .= "\n send mail to therapist -  ".$email;
						$userpass = get_user_meta($user->ID, 'generated_random', true);
						//".$data[8]." ".$data[9]." ".$data[51]." ".$data[10]."
						
						$content_for_therapist = "Dear ".$user->first_name.", we're sending you a friendly reminder for your event at ".((isset($jobdata[8]) && $jobdata[8]!='')?$jobdata[8]:'')." ".((isset($jobdata[9]) && $jobdata[9]!='')?$jobdata[9]:'')." ".((isset($jobdata[10]) && $jobdata[10]!='')?$jobdata[10]:'')." ".((isset($jobdata[51]) && $jobdata[51]!='')?$jobdata[51]:'')." , ".((isset($data[52]) && $data[52]!='')?$data[52]:$data[38])." will occur on ".$time_start." to ".$time_end.", for event details click <a href='".site_url('/my-events/')."'>here</a>.";
						
						$subject = 'Event Reminder';
						
						$therapistemail = array($email,'gpsbaroli@gmail','info@systork.com');
						
						$isSend = wp_mail($therapistemail, $subject, $content_for_therapist, $headers);
						wp_mail('gpsbaroli@gmail', $subject, $content_for_therapist, $headers);
						$isSend = wp_mail('info@systork.com', $subject, $content_for_therapist, $headers);
						//Code for SMS
						$message_sms = "Dear Admin, we're sending you a friendly reminder that your event will occur in ".$time_start." to ".$time_end;
						
						$to = get_user_meta($user->ID, 'mobile_phone_number', true);
						if($to==''){
							$to = get_user_meta($user->ID, 'phonenumber', true);
						}
						
						if( !empty($to) && substr($to,0,1) != '+') {
							$to = '+1'.$to;
						}
						$to = '+919610730117';
						//echo $to;
						//echo $message_sms;die();
						Admin_Jobs::sendSMS($to,$message_sms);    
						
						
						}
					}
					
					////send email to content person
					
					$contact_email = $jobdata[44];
					$contect_name = $jobdata[33]." ".$jobdata[49];
					
					//$customeremail = array('gsjpr7@gmail.com', 'info@systork.com');
					///$isSend = wp_mail($customeremail, 'email user', 'email for user'.$eventowner_email, $headers);
					
					if($contact_email!=''){
					$content_for_eventowner = "Dear ".$contect_name.", we're sending you a friendly reminder for your event at ".((isset($jobdata[8]) && $jobdata[8]!='')?$jobdata[8]:'')." ".((isset($jobdata[9]) && $jobdata[9]!='')?$jobdata[9]:'')." ".((isset($jobdata[10]) && $jobdata[10]!='')?$jobdata[10]:'')." ".((isset($jobdata[51]) && $jobdata[51]!='')?$jobdata[51]:'')." , ".((isset($data[52]) && $data[52]!='')?$data[52]:$data[38])." will occur on ".$time_start." to ".$time_end.", for event details click <a href='".site_url('/event-listing/')."'>here</a>.";
						
						$subject = 'Event Reminder';
							$content_log .= "\n send mail to customer -  ".$eventowner_email;
							$customeremail = array($contact_email,'gsjpr7@gmail.com', 'info@systork.com');
							
							$isSend = wp_mail($customeremail, $subject, $content_for_eventowner, $headers);
							
							//Code for SMS
							$message_sms = "Dear ".$contect_name.", we're sending you a friendly reminder that your event will occur in ".$time_start." to ".$time_end;
							
							$to = $jobdata[34];
							
							if( !empty($to) && substr($to,0,1) != '+') {
								$to = '+1'.$to;
							}
							//$to = '+919610730117';
							//echo $message_sms;die();
							//Admin_Jobs::sendSMS($to,$message_sms);    
					}
					
					
					
					
					////send email to event owner
					
					$eventowner_id	 = ($locations[0]->created_by);
					
					$eventowner = get_user_by('id', $eventowner_id);
					
					$eventowner->full_name = $eventowner->first_name ? $eventowner->first_name.($eventowner->last_name ? ' '.$eventowner->last_name : '') : null;
					$eventowner_email = $eventowner->user_email;
					$eventowner_name = empty($eventowner->full_name) ? ( empty($eventowner->display_name) ? $email : $eventowner->display_name) : $eventowner->full_name;
					
					//$customeremail = array('gsjpr7@gmail.com', 'info@systork.com');
					///$isSend = wp_mail($customeremail, 'email user', 'email for user'.$eventowner_email, $headers);
					
					if($eventowner_email!=''){
					$content_for_eventowner = "Dear ".$eventowner_name.", we're sending you a friendly reminder for your event at ".((isset($jobdata[8]) && $jobdata[8]!='')?$jobdata[8]:'')." ".((isset($jobdata[9]) && $jobdata[9]!='')?$jobdata[9]:'')." ".((isset($jobdata[10]) && $jobdata[10]!='')?$jobdata[10]:'')." ".((isset($jobdata[51]) && $jobdata[51]!='')?$jobdata[51]:'')." , ".((isset($data[52]) && $data[52]!='')?$data[52]:$data[38])." will occur on ".$time_start." to ".$time_end.", for event details click <a href='".site_url('/event-listing/')."'>here</a>.";
						
						$subject = 'Event Reminder';
							$content_log .= "\n send mail to customer -  ".$eventowner_email;
							$customeremail = array($eventowner_email,'gsjpr7@gmail.com', 'info@systork.com');
							
							$isSend = wp_mail($customeremail, $subject, $content_for_eventowner, $headers);
							
							//Code for SMS
							$message_sms = "Dear ".$eventowner_name.", we're sending you a friendly reminder that your event will occur in ".$time_start." to ".$time_end;
							
							$to = get_user_meta($eventowner_id, 'mobile_phone_number', true);
							if($to==''){
								$to = get_user_meta($user->ID, 'phonenumber', true);
							}
							if( !empty($to) && substr($to,0,1) != '+') {
								$to = '+1'.$to;
							}
							$to = '+919610730117';
							//echo $message_sms;die();
							//Admin_Jobs::sendSMS($to,$message_sms);    
					}
					
					
					
					
					
					//Send email to site owner
					$blogadmin = get_bloginfo('admin_email');
					
					if($blogadmin!=''){
					$content_for_admin = "Dear Admin, we're sending you a friendly reminder that your event, ".((isset($data[52]) && $data[52]!='')?$data[52]:$data[38])." will occur on ".$time_start." to ".$time_end.", for event details click <a href='".site_url('/my-events/')."'>here</a>. ";
						
						
						$subject = 'Event Reminder';
							
							$content_log .= "\n send mail to admin -  ".$blogadmin;
							$isSend = wp_mail($blogadmin, $subject, $content_for_admin, $headers);
							
							//Code for SMS
							$message_sms = "Dear Admin, we're sending you a friendly reminder that your event will occur in ".$time_start." to ".$time_end;
							
							/*$to = get_user_meta($user->ID, 'mobile_phone_number', true);
							
							if( !empty($to) && substr($to,0,1) != '+') {
								$to = '+91'.$to;
							}*/
							$to = '+17862279815';
							//echo $message_sms;die();
							Admin_Jobs::sendSMS($to,$message_sms);    
					}
				}
		/*//end location loop */
			}
		
			
		
		
		//print_r($timeElapsed);die();
		
		/*
		$timeElapsed  = 0;
		foreach($_POST["date"] as $date){
			$StartTime= $_POST["input_58"][0].':'.$_POST["input_58"][1].' '.$_POST["input_58"][2];
			$EndTime = $_POST["input_59"][0].':'.$_POST["input_59"][1].' '.$_POST["input_59"][2];
			$StartTime= $_POST["input_64"];
			$EndTime = $_POST["input_65"];
			$sst = strtotime($StartTime);
			$eet=  strtotime($EndTime);
			$diff= $eet-$sst;
			$timeElapsed += date("h.i",$diff);
		}
        //echo $timeElapsed;die();
        */        
        
		
		
		
		echo("<script>location.href = 'admin.php?page=view_location&lid=".$_POST["gform_lid"]."&view=entry&id=2&leid=".$lead_id."&dir=DESC&filter&paged=1&pos=2&field_id&operator';</script>");
		die();
		
		}
		
		
	
		$args = array();
		$location = Admin_Jobs::load_location($_GET['lid']);
		$details = maybe_unserialize($location->data);
		$args["details"] = $details;
		//print_r($location);
		
		//$labels_location = get_option('gf_labels_location', array());
		//print_r($labels_location);
		
		$datetime = AJ_Location::getDatesEventByLocation($location->location_id);
		$args["datetime"] = $datetime;
		$users_inviteds = maybe_unserialize($location->users_invited);
		$accept_job = maybe_unserialize($location->accept_job);
		$args["users_inviteds"] = $users_inviteds;
		$args["status_accept"] = $accept_job;
		
		//print_r($users_inviteds);
		//print_r($accept_job);
		
		/* $_POST["mode"] = 'edit';
		$GET["quote"] = 'appointment';
		echo $_POST["edit_id"] = $_GET["lid"]; */
		
		//echo do_shortcode('[gravityform id="2" name="Submit Job" title="false" description="false"]');
		$this->loadTemplate( dirname( __FILE__ ) . '/templates/edit_location.php', $args);

	}
	
	function removeinvitedtherapist(){
		
		global $wpdb;
		
		$location = Admin_Jobs::load_location($_GET['lid']);
		$details = maybe_unserialize($location->users_invited);
		//print_r($details);
		//print_r($details);
		
		if(isset($details[$_GET['id']]))
		unset($details[$_GET['id']]);
		
		$sql = "UPDATE ".$wpdb->prefix."job_location set users_invited = '".serialize($details)."' WHERE id=".$_GET["lid"];

        $wpdb->query( $sql );
		echo("<script>location.href = 'admin.php?page=view_location&lid=".$_GET["lid"]."&view=entry&id=".$_GET["form_id"]."&leid=".$_GET["leid"]."&dir=DESC&filter&paged=1&pos=2&field_id&operator';</script>");
		die();
	}
	
	function edit_note(){
		
		global $wpdb;
		$table_name = $wpdb->prefix . "jobs_notes";
		if(isset($_POST["screen_mode"]) && $_POST["screen_mode"]=='update_note'){
		
		$sql = $wpdb->prepare( "UPDATE $table_name set value = '".trim($_POST["new_note"])."' WHERE id=%d", $_POST["note_id"] );
		$wpdb->query( $sql );
		ob_start();	
		ob_get_clean();	
		//exit(header("location:admin.php?page=view_archive_notes&lid=".$_POST["job_id"]."&archive_note=yes"));	
		$exparam = '';
		if(isset($_POST["redirect_page"]) && $_POST["redirect_page"]=='view_archive_notes'){
			$exparam = '&archive_note=yes';
		}
		echo("<script>location.href = 'admin.php?page=".$_POST["redirect_page"]."&lid=".$_POST["job_id"].$exparam."';</script>");
		//wp_redirect("admin.php?page=view_archive_notes&lid=".$_POST["job_id"]."&archive_note=yes");
		//print_r($_POST);
		ob_end_flush();
		exit;
			
		}
		
		$this->loadTemplate( dirname( __FILE__ ) . '/templates/edit_note.php', $args);

	}
	
	
	function view_archive_notes(){
	
		$args = array();
		$location = Admin_Jobs::load_location($_GET['lid']);
		$details = maybe_unserialize($location->data);
		$args["details"] = $details;
		//print_r($details);
		
		//$labels_location = get_option('gf_labels_location', array());
		//print_r($labels_location);
		
		$datetime = AJ_Location::getDatesEventByLocation($location->ID);
		$args["datetime"] = $datetime;
		$users_inviteds = maybe_unserialize($location->users_invited);
		$accept_job = maybe_unserialize($location->accept_job);
		$args["users_inviteds"] = $users_inviteds;
		$args["status_accept"] = $accept_job;
		
		//print_r($users_inviteds);
		//print_r($accept_job);
		
		$this->loadTemplate( dirname( __FILE__ ) . '/templates/view_archive_notes.php', $args);

	}
	
	function archive_location(){
	
		$args = array();
		
		$this->loadTemplate( dirname( __FILE__ ) . '/templates/list_notes.php', $args);

	}
	
	
	
	public static function get_job_notes_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'jobs_notes';
	}
	
	public static function get_job_notes( $location_id ) {
		global $wpdb;
		$notes_table = self::get_job_notes_table_name();
		$archive = 0;
		if(isset($_GET["archive_note"]) && $_GET["archive_note"]=='yes'){
		$archive = 1;
		}
		$dirsql = " and archive = ".$archive." ";
		
		if(isset($_GET["archive_dir"]) && $_GET["archive_dir"]!=''){
		$dirsql .= " and archive_dir = '".$_GET["archive_dir"]."' ";
		}
		
		if(isset($_GET["archive_year"]) && $_GET["archive_year"]!=''&& isset($_GET["month"]) && $_GET["month"]!=''){
		$dirsql .= " and date_format(n.date_created,'%Y-%m') = '".$_GET["archive_year"].'-'.$_GET["month"]."' ";
		}
		
		
		$finalsql = $wpdb->prepare(" SELECT n.id, n.user_id, n.date_created, n.value, n.archive, n.archive_dir, ifnull(u.display_name,n.user_name) as user_name, u.user_email
		FROM $notes_table n	LEFT OUTER JOIN $wpdb->users u ON n.user_id = u.id WHERE job_id=%d ", $location_id);
		$finalsql = $finalsql." ".$dirsql." ORDER BY id ";
        return $wpdb->get_results($finalsql);
		
		
	}
	
	
	
	
	function setupdatabase () {

	global $wpdb;

	global $cnss_db_version;

	$upload_dir = wp_upload_dir();

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$table_name = $wpdb->prefix . "jobs_notes";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

	$sql2 = "CREATE TABLE $table_name (
	  `id` int(10) UNSIGNED NOT NULL,
	  `job_id` int(10) UNSIGNED NOT NULL,
	  `user_name` varchar(250) DEFAULT NULL,
	  `user_id` bigint(20) DEFAULT NULL,
	  `date_created` datetime NOT NULL,
	  `value` longtext,
	  `note_type` varchar(50) DEFAULT NULL,
	  `archive` TINYINT(1) NOT NULL,
	  `archive_dir` VARCHAR(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	dbDelta($sql2);

	$sql2 = "ALTER TABLE $table_name
	  ADD PRIMARY KEY (`id`),
	  ADD KEY `job_id` (`job_id`),
	  ADD KEY `lead_user_key` (`job_id`,`user_id`);";
	$wpdb->query($sql2);

	$sql2 = "ALTER TABLE $table_name
	  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
	$wpdb->query($sql2);
			

	//add_option( 'cnss-width', '32');

		

	}

}

//register_activation_hook(__FILE__,'jobs_notes_db_install');



	
	 
 }
 
$GLOBALS['events_jobs_notes'] = new Manage_jobs_notes();