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
</style>

	<div class="small-12 large-12 columns">
		<h6 class="titleBar">FAQs</h6>
		<div class="element-divider"></div>
		<br />
	</div>
	<div class="columns small-12 medium-10 large-10 large-offset-1 medium-offset-1">
		<?php
			if($faqs['responseType'] == '1'){
				foreach ($faqs['text'] as $key) {
		?>			
						<div class="question columns small-12 medium-12 large-12"><h4>Q.</h4><?php echo $key['question'] ?></div>
						<div class="answer columns small-12 medium-12 large-12">
							<h4>A.</h4>
							<div style="display: inline-block;width: 94%;">
								<?php echo $key['answer'] ?>
							</div>
						</div>
		<?php
				}
			} else{
		?>
				<p>No FAQs found.</p>
		<?php
			}
		?>
		<div></div>
	</div>


				
			
			
<?php include __DIR__."/../../footer.php" ?>