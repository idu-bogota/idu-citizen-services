<div class="row-fluid">
    <?php echo $form->description->render($view); ?>
</div>
<div class="row-fluid">
  <div class="span6">
    <?php echo $form->ancho->render($view); ?>
    <?php echo $form->largo->render($view); ?>
    <?php echo $form->profundidad->render($view); ?>
  </div>
  <div class="span6">
    <?php echo $form->image->render($view); ?>
  </div>
</div>