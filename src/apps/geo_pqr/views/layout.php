<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title><?php echo $title ?></title>
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>
    <script type='text/javascript' src='http://openlayers.org/api/2.12/OpenLayers.js'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/underscore-min.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/backbone-min.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/bootstrap-min.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/wizard.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/mixins.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/jPaginate.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/bootstrap-fileupload.min.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/ocs-public-portal.js') ?>'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/geo_pqr.js') ?>'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo glue("route")->url('/css/bootstrap-min.css') ?>" rel="stylesheet">
    <link href="<?php echo glue("route")->url('/css/bootstrap-responsive-min.css') ?>" rel="stylesheet">
    <link href="<?php echo glue("route")->url('/css/bootstrap-fileupload.min.css') ?>" rel="stylesheet">
    <link href="<?php echo glue("route")->url('/css/styles.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="navbar navbar-inverse hidden-phone">
        <div class="navbar-inner">
            <a class="brand" href="http://www.idu.gov.co/">IDU</a>
            <ul class="nav">
                <li <?php echo get_menu_item_class($menu_item, 'list') ?>><a href="./">Inicio</a></li>
                <li <?php echo get_menu_item_class($menu_item, 'new') ?>><a href="./new">Reporte un daño</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <?php if(!is_mobile()): ?>
            <h1 class="hidden-phone"><?php echo $title ?></h1>
        <?php else : ?>
            <strong class="visible-phone"><?php echo $title ?></strong>
        <?php endif ?>
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
