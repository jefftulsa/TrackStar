<?php //$this->beginContent('application.views.layouts.main'); 
$this->beginContent('/layouts/main');?>
<div class="container">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>