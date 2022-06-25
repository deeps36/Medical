<?php 
/*

General category is excluded in this form.
Basic fields are covered in "BASIC" category.

*/

	include __DIR__."/../../header.php";

?>
<link rel="stylesheet" href="../../css/contact-us-section.css" type="text/css" />

<style>
	.tabs-content{
		border-bottom: 0;
	}
</style>

		
		<div class="row">
			<div class="medium-6">
				<h3>Get In Touch</h3>
				<form id="contact_form" name="contact_form" method="post" action="/Contact/postContact">
					<div class="row form-group" id="questionDiv37">
						<div class="medium-12  columns">
							<input name="question[37]" maxlength="25" id="question_37" class="form-control" value="" placeholder="First Name">
						</div>
						<div class="cf"></div>
					</div>
					<div class="row form-group" id="questionDiv40">
						<div class="medium-12 columns">
							<input name="question[40]" maxlength="25" id="question_40" class="form-control" value="" placeholder="Last Name">
						</div>
						<div class="cf"></div>
					</div>
					<div class="row form-group" id="questionDiv36">
							<div class="medium-12 columns">
								<input name="question[36]" maxlength="100" id="question_36" class="form-control" value="" placeholder="Email Address">
						</div>
						<div class="cf"></div>
					</div>
					<div class="row form-group" id="questionDiv38">
						<div class="medium-12 columns">
							<input name="question[38]" maxlength="100" id="question_38" class="form-control" value="" placeholder="Subject">
						</div>
						<div class="cf"></div>
					</div>
					<div class="row form-group" id="questionDiv39">
						<div class="large-12 columns">
							<textarea name="question[39]" id="question_39" class="form-control" rows="10" placeholder="Message"></textarea>
						</div>
						<div class="cf"></div>
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-primary"><i class="fa fa-envelope fa-fw"></i> Send Message</button>
					</div>
				</form>	
			</div>
			<div class="medium-5 columns">
				
				<div class="contact-info">
					<h3>Contact Information</h3>
					<ul class="contact">
						<li>Post Box No. 29, At - Jahangirpura, <br>PO - Gopalpura, Vadod- 388 370, <br>Hadgud, District - Anand, <br>Gujarat, INDIA</li>
						<li><a href="mail:anchor&amp;curator@fes.org.in" target="_blank"><i class="fa fa-envelope fa-fw"></i> info@indiaobservatory.org.in</a></li>
						<li><a href="https://www.indiaobservatory.org.in" target="_blank"><i class="fa fa-globe fa-fw"></i> www.indiaobservatory.org.in</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- contact JSLPS / OLM -->
		<div class="small-12 large-12 columns">
			<div class="small-12 large-12 columns">
				<br />
				<h6 class="titleBar">Contact JSLPS</h6>
				<BR />
			</div>
				<div class="small-12 large-6 medium-5 columns float-left">
					<h5>Jharkhand State Livelihood Promotion Society(JSLPS)</h5>
					SRC, 3rd Floor, FFP Building, HEC Campus<br>Dhurwa, Ranchi - 834004<br>Jharkhand, India<br><br><b>Email:</b> <img src="/images/jslps-email.png" /><br><b>Phone No:</b> 0651-2401783
				</div>
			<div class="small-12 large-6 medium-5 columns float-right"  style="text-align: center;">
				<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14656.638010125847!2d85.2781674459717!3d23.309970975942985!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8f3c20ffb7bcc07e!2sJSLPS!5e0!3m2!1sen!2sin!4v1523537895361" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
			</div>
		</div>

		<div class="small-12 large-12 columns">
		 	<div class="small-12 large-12 columns">
                    <br />
                    <h6 class="titleBar">Contact OLM</h6>
                    <BR />
            </div>
            <div class="small-12 large-6 medium-5 columns float-left">
                    <h5>Odisha Livelyhoods Mission (OLM)</h5>
                    Unit - 8, SIRD & PR Campus<br>Near Stewart School<br>Bhubaneswar - 751012<br>Odisha, India<br><br><b>Email:</b> <img src="/images/olm-email.png" /><br><b>Phone No:</b> 0674-2560166, 2560126
            </div>

            <div class="small-12 large-6 medium-5 columns float-right" style="text-align: center;">
            	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3742.502389332367!2d85.80675191492018!3d20.279462086409!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a19a77feaaaaaab%3A0xdc11f7169f72aa7d!2sOdisha%20Livelihoods%20Mission!5e0!3m2!1sen!2sin!4v1571381747693!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            </div>
    	</div>
			
			
<?php include __DIR__."/../../footer.php" ?>