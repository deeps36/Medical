<?php 
/*

General category is excluded in this form.
Basic fields are covered in "BASIC" category.

*/

	include __DIR__."/../../header.php";

?>
<link rel="stylesheet" href="../../css/faq.css?<?php echo time(); ?>" type="text/css" />
<style>
	.tabs-content{
		border-bottom: 0;
	}
	a p{
		font-size: 1.03rem;
    	color: #0058b7;
	}
</style>

	<div class="small-12 large-12 columns">
		<h6 class="titleBar">Download</h6>
		<div class="element-divider"></div>
		<br />
	</div>
	<div class="columns small-12 medium-10 large-10 large-offset-2 medium-offset-1">
		<div class="large-3 medium-4 small-6 columns" data-equalizer-watch="foo" style="padding-left: 0;">
			<div class="card" style="box-shadow: -3px 2px 6px 0px rgba(0, 0, 0, 0.15);" data-equalizer-watch>
				<div class="card-divider"><h5>GEET Public</h5></div>
				<div class="card-section">
					<a href="/mobileapp/public/GEET_PUBLIC.apk"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_public'];?> app (apk)</p></a>
					<a href="/manuals/public/GEETMobile-Public_App_User_Manual.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_public'];?> manual for mobile app (English)</p></a>
					<a href="/manuals/public/GEETMobile-Public_App_User_Manual_hindi.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_public'];?> manual for mobile app (Hindi)</p></a>
					<a href="/manuals/public/Website_GEET_User_Manual_Public.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_public'];?> manual for website (English)</p></a>
				</div>
			</div>
		</div>

		<div class="large-3 medium-4 small-6 columns" data-equalizer-watch="foo" style="padding-left: 0;">
			<div class="card" style="box-shadow: -3px 2px 6px 0px rgba(0, 0, 0, 0.15);" data-equalizer-watch>
				<div class="card-divider"><h5>GEET Enumerator</h5></div>
				<div class="card-section">
					<a href="/mobileapp/admin/GEET_ENUM_1.5.2.apk"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_enumerator'];?> app (apk)</p></a>
					<a href="/manuals/enumerator/GEETMobile-Enumerator_App_User_Manual.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_public'];?> manual for mobile app (English)</p></a>
					<a href="/manuals/enumerator/GEETMobile-Enumerator_App_User_Manual_hindi.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_public'];?> manual for mobile app (Hindi)</p></a>
					<a href="/manuals/enumerator/Website_GEET_User_Manual_Enumerator.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> <?php echo $GLOBALS['lang_enumerator'];?> manual for website</p></a>
				</div>
			</div>
		</div>

		<div class="large-3 medium-4 small-6 columns" data-equalizer-watch="foo" style="padding-left: 0;">
			<div class="card" style="box-shadow: -3px 2px 6px 0px rgba(0, 0, 0, 0.15);" data-equalizer-watch>
				<div class="card-divider"><h5>GEET Monitor</h5></div>
				<div class="card-section">
					<a href="/manuals/monitor/Website_GEET_User_Manual_Monitoring.pdf" target="_blank"><p><?php echo $GLOBALS['lang_download'];?> Monitor manual for website </p></a>
				</div>
			</div>
		</div>
	</div>

	<div class="columns small-12 medium-10 large-10 large-offset-2 medium-offset-1" style="margin-bottom: 30px;"></div>

	<!--div class="small-12 large-12 columns" style="margin-top: 30px">
		<h6 class="titleBar">Training videos</h6>
		<div class="element-divider"></div>
		<br />
	</div>
	<div class="columns small-12 medium-10 large-10 medium-offset-1" style="text-align: center;"><p>Coming soon</p></div>

	<br><br>

	<div class="small-12 large-12 columns" style="margin-top: 30px">
		<h6 class="titleBar">Case Studies</h6>
		<div class="element-divider"></div>
		<br />
	</div>
	<div class="columns small-12 medium-10 large-10 medium-offset-1" style="text-align: center;"><p>Coming soon</p></div -->
			
			
<?php include __DIR__."/../../footer.php" ?>