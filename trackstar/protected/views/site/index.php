<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php if(!Yii::app()->user->isGuest):?>
<p>
   You last logged in on <?php echo date( 'l, F d, Y, g:i a', Yii::app()->user->lastLoginTime ); ?>.	
</p>
<?php endif;?>

