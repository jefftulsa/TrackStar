<?php
$this->breadcrumbs=array(
	'Issues',
);

$this->menu=array(
	array('label'=>'Create Issue', 'url'=>array('create', 'pid'=>$this->getProject()->id)),
	array('label'=>'Manage Issue', 'url'=>array('admin', 'pid'=>$this->getProject()->id)),
);
?>

<h1>Issues</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
