<?php
	include __DIR__."/../../header.php";
?>
<style>
.orbit-container{
	height: 500px;
}
</style>

		<div class="row" id="loginRow" style="position: relative;max-width: 100%;margin:0;background: #004f75;" >
		<!-- Slider + Scheme search + Login Row-->
			<!-- slider -->
			<!-- Scheme search + Login Tabs-->
			<div id="searchSchemes" class="small-12 medium-9 columns">
				<ul class="tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-tabs id="geetSSLTabPanel">
					<!--li class="tabs-title is-active" style="background-color: rgba(255, 255, 255, 0.79);"><a href="#schemeEligibilitySearch" aria-selected="true"><?php //echo $GLOBALS['lang_search_eligibility'];?></a></li-->
					<?php if(!isset($_SESSION['admin'])){ ?><li class="tabs-title is-active" style="background-color: rgba(255, 255, 255, 0.79);display: none;"><a href="#login"><?php echo $GLOBALS['lang_login'];?></a></li><?php } ?>
				</ul>
				<div class="tabs-content" data-tabs-content="geetSSLTabPanel" data-equalizer id="tabContainer">
					<!--div class="tabs-panel is-active" id="schemeEligibilitySearch" data-equalizer-watch>
						<br /><p><?php //echo $GLOBALS['lang_scheme_search_intro'];?></p><br />
						<form onsubmit="return submitAttributes(this);" id="schemesSearch" action="#" method="get" autocomplete="off">
							<?php //include __DIR__."/scheme-search-form.php"; ?>
							<div class="row">
								<div class="large-12 medium-12 small-12 columns">
									<input type="submit" class="button" value="<?php// echo $GLOBALS['lang_search'];?>" style="display:inline-block;font-size: 1rem;margin-top: 15px;"><label class="advanceSearch" id="advanceSSearch"><a href="/scheme/getSchemeSearch"><?php //echo $GLOBALS['lang_advanced_search'];?></a></label><label class="advanceSearch" id="searchByECID" data-open="ecSearchModal"><?php //echo $GLOBALS['lang_search_ec_card'];?></label>
								</div>
							</div>
						</form>
					</div -->
					<?php if(!isset($_SESSION['admin'])){?>
					<div class="tabs-panel" id="login" data-equalizer-watch>
						<h3 style="color: #333333;font-weight: bold;">Sign in</h3>
						<form method="post" action="/user/getLogin" autocomplete="off" onsubmit = "return encryptValue();">
							<br />
							<div class="row">
								<div class="medium-12 small-12 columns">
									<label><?php echo $GLOBALS['lang_user_id'];?>
										<input type="text" id="username" name="user_login[username]" placeholder="<?php echo str_replace('{attr}', $GLOBALS['lang_user_id'], $GLOBALS['lang_type_attr']);?>" required autofocus="autofocus">
									</label>
								</div>
							</div>
							<div class="row">
								<div class="medium-12 small-12 columns">
									<label><?php echo $GLOBALS['lang_password'];?>
										<input type="password" id="upassword" name="user_login[password]" placeholder="<?php echo str_replace('{attr}', $GLOBALS['lang_password'], $GLOBALS['lang_type_attr']);?>" required>
									</label>
								</div>
							</div>
							<div class="row">
								<div class="medium-12 small-12 columns">
									<p id="captchaBox"><span><img id="captchaPuzzle" src="<?php echo $builder->inline(); ?>" /><span><span onclick="resetCaptcha()" style="color: #384661;vertical-align: middle;margin-left: 10px;font-size: 0.92rem;cursor: pointer;">refresh</span></p>
									<label>Enter the text as shown in above image</label>
									<input type="text" id="loginCaptcha" name="loginCaptcha" required/>
								</div>
							</div>
							<input type="hidden" id="passsalt" name="user_login[passsalt]" value="<?php echo $_SESSION['salt']; ?>">
							<div class="row">
								<div class="medium-6 small-12 columns">
									<input type="submit" class="button" value="<?php echo $GLOBALS['lang_login'];?>">
								</div>
							</div>
						</form>
					</div>
					<?php } ?>

					<!--div class="large-12 medium-12 small-12 columns app-download">
						<a href="/mobileapp/public/GEET_Public.apk" style="color: #0c3e5f;">
							<div class="medium-6 small-12 columns left"><?php echo $GLOBALS['lang_download'];?> GEET <?php echo $GLOBALS['lang_public'];?> app (android)</div>
						</a>
						<a href="/mobileapp/admin/GEET_Enumerator.apk" style="color: #0c3e5f;">
							<div class="medium-6 small-12 columns right"><?php echo $GLOBALS['lang_download'];?> GEET <?php echo $GLOBALS['lang_enumerator'];?> app (android)</div>
						</a>
					</div -->
				</div>
			</div>
		</div>
		
		
		<!-- Footer -->
		<?php include("footer.php"); ?>
	<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
	<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
	<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>
	<script type="text/javascript" src="<?php echo "/js/vendor/jquery.counterup.min.js"; ?>"></script>