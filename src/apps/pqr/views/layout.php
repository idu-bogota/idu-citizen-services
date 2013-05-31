<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title><?php echo $title ?></title>
    <link href="css/bootstrap-min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive-min.css" rel="stylesheet">
    <link href="css/bootstrap-fileupload.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="navbar navbar-inverse hidden-phone">
        <div class="navbar-inner">
            <a class="brand" href="http://www.idu.gov.co/">IDU</a>
            <ul class="nav">
                <li <?php echo is_item_active($menu_item, 'list') ?>><a href="./">Inicio</a></li>
                <li <?php echo is_item_active($menu_item, 'new') ?>><a href="./new">Reporte un daño</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
    </div>
</body>
</html>
<?php
function is_item_active($selected, $item_id) {
    if($selected == $item_id) {
        return 'class = "active"';
    }
}
