<div class="row-fluid">
    <?php echo $form->description->render($view); ?>
</div>
<div class="row-fluid">
  <div class="span6">
    <?php echo $form->damage_width_by_citizen->render($view); ?>
    <?php echo $form->damage_length_by_citizen->render($view); ?>
    <?php echo $form->damage_deep_by_citizen->render($view); ?>
  </div>
  <div class="span6">
    <?php echo $form->image->render($view); ?>
  </div>
</div>