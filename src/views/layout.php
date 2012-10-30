<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title><?php echo $title ?></title>
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>
    <script type='text/javascript' src='http://openlayers.org/api/2.12/OpenLayers.js'></script>
    <script type='text/javascript' src='js/underscore-min.js'></script>
    <script type='text/javascript' src='js/backbone-min.js'></script>
    <script type='text/javascript' src='js/ocs-public-portal.js'></script>
</head>
<body>
    <h1><?php echo $title ?></h1>
    <?php echo $content_for_layout;?>
</body>
</html>