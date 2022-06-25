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
	h3{
		color: #313131;
	}
	.contact-info ul{
		list-style-type: none;
		line-height: 1.7;
		font-size: 1.2rem;
		color: #313131;
	}
</style>

<script type="text/javascript">
	function resetCaptcha(){
		$.ajax({
			url: "/Utils/refreshCaptcha",
			type: 'get',
			success: function(response){
				var data = JSON.parse(response);
				$("#captchaBox span img").attr('src', data['text']);
				$("#captcha").val('');
			},
			error: function(e){
				$("#captchaBox").append('<div class="callout alert">Error: there was an error refreshing captcha. Kindly contact site administrator.</div>');				
			}
		});
	}
</script>

		
		<div class="large-12 small-12 columns">
			<div class="large-6 medium-6 small-12 columns large-offset-1">
				<br><h4><b>Get In Touch</b></h4><br>
				<form id="contact_form" name="contact_form" method="post" action="/Contact/postContact">
					<div class="medium-12 columns">
						<input name="fname" id="fname" type="text" value="" placeholder="First Name" required="">
					</div>
					<div class="medium-12 columns">
						<input name="lname" id="lname" type="text" value="" placeholder="Last Name" required="">
					</div>
					<div class="medium-12 columns">
						<input name="email" id="email" type="email" value="" placeholder="Email Address" required="">
					</div>
					<div class="medium-12 columns">
						<input name="subject" id="subject" type="text" value="" placeholder="Subject" required="">
					</div>
					<div class="medium-12 columns">
						<textarea name="message" rows="10" id="message" value="" placeholder="Message" required=""></textarea>
					</div>
					<div class="row" style="padding-left: 0.9375rem;">
						<div class="medium-4 small-12 columns">
							<p id="captchaBox"><span><img id="captchaPuzzle" src="<?php echo $builder->inline(); ?>" /><span><span onclick="resetCaptcha()" style="color: #384661;vertical-align: middle;margin-left: 10px;font-size: 0.92rem;cursor: pointer;">refresh</span></p>
							<label>Enter the text as shown in above image</label>
							<input type="text" id="captcha" name="captcha" required/>
						</div>
					</div>

					<div class="medium-4 columns">
						<button type="submit" class="success button"><i class="fa fa-envelope fa-fw"></i> Send Message</button>
					</div>
				</form>	
			</div>
			<div class="medium-5 columns">
				
				<div class="contact-info">
					<br><h4><b>Contact Information</b></h4><br>
					<ul>
						<li>Post Box No. 29, At - Jahangirpura, <br>PO - Gopalpura, Vadod- 388 370, <br>Hadgud, District - Anand, <br>Gujarat, INDIA</li>
						<br>
						<li><a href="mail:anchor&amp;curator@fes.org.in" target="_blank"><i class="fi-mail" style="margin-right: 6px;"></i> info@indiaobservatory.org.in</a></li>
						<li><a href="https://www.indiaobservatory.org.in" target="_blank"><i class="fi-web" style="margin-right: 6px;"></i> www.indiaobservatory.org.in</a></li>
					</ul>
				</div>

			</div>
			<br>
		</div>
		
		<br>

		<!-- contact JSLPS / OLM -->
		<!--
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
		-->
			
<?php include __DIR__."/../../footer.php" ?>