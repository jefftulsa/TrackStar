<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>2000)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type_id'); ?>
		<?php echo $form->dropDownList($model,'type_id', $model->getTypeOptions()); ?>
		<?php echo $form->error($model,'type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status_id'); ?>
		<?php echo $form->dropDownList($model,'status_id', $model->getStatusOptions()); ?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'owner_id'); ?>
		<?php //echo $form->textField($model,'owner_id'); ?>
		<?php echo $form->dropDownList($model,'owner_id', $this->getProject()->getUserOptions()); ?>
		<?php echo $form->error($model,'owner_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'requester_id'); ?>
		<?php //echo $form->textField($model,'requester_id'); ?>
		<?php echo $form->dropDownList($model,'requester_id', $this->getProject()->getUserOptions()); ?>
		<?php echo $form->error($model,'requester_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->