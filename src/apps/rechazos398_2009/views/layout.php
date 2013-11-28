<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title><?php echo $title ?></title>
    <link href="<?php echo glue("route")->url("/css/bootstrap-min.css") ?>" rel="stylesheet">
    <link href="<?php echo glue("route")->url("/css/bootstrap-responsive-min.css") ?>" rel="stylesheet">
    <link href="<?php echo glue("route")->url("/css/styles.css") ?>" rel="stylesheet">
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo glue("route")->url('/js/bootstrap-min.js') ?>'></script>
</head>
<body>
        <div class="container">
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
          <?php echo $content_for_layout;?>
        </div>

</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('input, select, textarea').tooltip({trigger: 'hover focus'});
    });
</script>