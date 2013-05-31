<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title><?php echo $title ?></title>
    <link href="<?php echo RELATIVE_ROOT_URI ?>/css/bootstrap-min.css" rel="stylesheet">
    <link href="<?php echo RELATIVE_ROOT_URI ?>/css/bootstrap-responsive-min.css" rel="stylesheet">
    <link href="<?php echo RELATIVE_ROOT_URI ?>/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <ul class="nav nav-tabs">
            <li <?php echo get_menu_item_class($menu_item, 'new') ?>><a href="./">Envie un requerimiento</a></li>
            <li <?php echo get_menu_item_class($menu_item, 'search') ?>><a href="./search">Consulte su requerimiento</a></li>
        </ul>
        <?php if(isset($warning_message)): ?>
            <div class="alert">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Atención!</strong> <?php echo $warning_message ?>
            </div>
        <?php endif ?>
        <?php if(isset($error_message)): ?>
            <div class="alert  alert-error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Error!</strong> <?php echo $error_message ?>
            </div>
        <?php endif ?>
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $success_message ?>
            </div>
        <?php endif ?>
    </div>
    <div class="container">
        <?php echo $content_for_layout;?>
    </div>

</body>
</html>
