



<div class="postbox mainbox">

<form method="post" enctype="multipart/form-data" id="gform_2" class="secundary-form" action="admin.php?page=edit_location&lid=<?php echo $_GET["lid"]; ?>">

<div class="event_head2"> </div>
<div class="toprow"><div class="leftcol"><a class="toplink" href="javascript(void)">Edit EVENT DETAIL</a></div></div>

<div class="divrow"><div class="leftcol">

<div class="row"><span class="lochead">Location #<?php echo $_GET["lid"];?></span></div>
<div class="row"><span class="subhead">Event Company Name: 
							</span><span class="locvalue"><input type="text" name="52" value="<?php echo $details[52];?>"> </span>
							</div>
<div class="row"><span class="subhead">On Site Contact Persons First Name: 
							</span><span class="locvalue"><input type="text" name="33" value="<?php echo $details[33];?>"> </span>
							</div>
	
<div class="row"><span class="subhead">On Site Contact Persons Last Name: 
							</span><span class="locvalue"><input type="text" name="49" value="<?php echo $details[49];?>"></span>
							</div>

<div class="row"><span class="subhead">On Site Contact Persons Phone Number: 
							</span><span class="locvalue"><input type="text" name="34" value="<?php echo $details[34];?>"></span>
							</div>

<div class="row"><span class="subhead">On Site Contact Persons Email: 
							</span><span class="locvalue"><input type="text" name="44" value="<?php echo $details[44];?>"></span>
							</div>

<div class="row"><span class="subhead"> 
							</span><span class="locvalue">
							<?php							
							$sep = '';
							$total_time = 0;
							foreach($datetime as $dt){
								?>
								Date : 
								<input type="text" name="date[]" value="<?php
								echo date('M d, Y',strtotime($dt->month.'/'.$dt->day.'/'.$dt->year));
								?>">
								<br />
								Time : 
								<input type="text" name="stime[]" value="<?php 
								echo $dt->stime;
								$sep = ', ';?>">
								To <input type="text" name="etime[]" value="<?php 
								echo $dt->etime;
								?>">
								<br /><br />
								<?php
								$total_time = $dt->etime-$dt->stime;
							}?> </span>
							</div>



<div class="row"><span class="subhead">Address: 
							</span><span class="locvalue"><input type="text" name="8" value="<?php echo $details[8];?>"></span>
							</div>

<div class="row"><span class="subhead">Zip Code: 
							</span><span class="locvalue"><input type="text" name="51" value="<?php echo $details[51];?>"></span>
							</div>

<div class="row"><span class="subhead">City: 
							</span><span class="locvalue"><input type="text" name="9" value="<?php echo $details[9];?>"></span>
							</div>

<div class="row"><span class="subhead">State: 
							</span><span class="locvalue"><input type="text" name="10" value="<?php echo $details[10];?>"></span>
							</div>

<div class="row"><span class="subhead">Time Zone: 
							</span><span class="locvalue"><input type="text" name="28" value="<?php echo $details[28];?>"></span>
							</div>
							
<div class="row"><span class="subhead">Hourly Pay: 
							</span><span class="locvalue"><input type="text" name="53" value="<?php echo $details[53];?>"></span>
							</div>
							
<div class="row"><span class="subhead">Total # of Therapist: 
							</span><span class="locvalue"><input type="text" name="7" value="<?php echo $details[7];?>"></span>
							</div>

<div class="row"><span class="subhead">Parking Instructions: 
							</span><span class="locvalue"></span>
							<textarea name="68" id="68" class="textarea small" aria-invalid="false" rows="5" cols="40"><?php echo $details[68];  //$details[30];?></textarea>
							</div>

<div class="row"><span class="subhead">Meet & Greet Information: 
							</span><span class="locvalue"><textarea name="67" id="67" class="textarea small" aria-invalid="false" rows="5" cols="40"><?php echo $details[67];  //$details[30];?></textarea></span>
							</div>

							<div class="row"><span class="subhead">Documents: 
							</span><span class="locvalue"><input type="file" name="66" value="<?php echo $details[66];?>"></span>
							</div>

<div class=""><input type="submit" name="update_location" id="update_location" value="Update Location" class="redbutton button" />
<input type="hidden" class="gform" name="gform_lid" value="<?php echo $_GET["lid"];?>">      
<input type="hidden" class="gform" name="location_mode_edit" value="edit">      

</div>

		</div>
	</div>

</form>
	
</div>
<div class="">&nbsp;</div>
