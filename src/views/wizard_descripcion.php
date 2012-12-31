<div class="row-fluid">
    <?php echo $form->description->render($view); ?>
</div>
<div class="row-fluid">
  <div class="span6">
    <?php echo $form->width->render($view); ?>
    <?php echo $form->length->render($view); ?>
    <?php echo $form->deep->render($view); ?>
  </div>
  <div class="span6">
    <?php echo $form->image->render($view); ?>
  </div>
</div>