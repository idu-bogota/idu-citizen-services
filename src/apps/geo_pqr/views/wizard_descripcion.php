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
    <?php if(!is_mobile()): ?>
        <?php
            $form_errors = $form->getMessages();
            $image_errors_msg = false;
            if(isset($form_errors['image'])) {
                $image_errors = $form_errors['image'];
                $image_errors_msg = join('. ', array_values($image_errors));
            }
        ?>
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
              <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=Sin+imagen" />
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
            <div>
                <?php if($image_errors_msg): ?>
                    <span class="error"><?php echo $image_errors_msg ?></span>
                <?php endif ?>
                <span class="btn btn-file">
                    <span class="fileupload-new">Adjuntar una imagen</span>
                    <span class="fileupload-exists">Cambiar</span>
                    <input id="MAX_FILE_SIZE" type="hidden" value="2097152" name="MAX_FILE_SIZE">
                    <input id="image" type="file" name="image" />
                </span>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Eliminar</a>
            </div>
        </div>
    <?php else : ?>
        <?php echo $form->image->render($view); ?>
    <?php endif ?>
  </div>
</div>