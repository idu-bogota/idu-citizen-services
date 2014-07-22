<style>
  .action { cursor: pointer }
  .nav-pills > li > a {
    text-decoration:none;
  }
  .nav-pills > li > a:hover, .nav-pills > li:hover {
    background-color: white;
    color: #003366;
    text-decoration:none;
    cursor: default
  }
  .nav-pills .active > a {
    background-color: #999;
  }
  .nav-pills .active > a:hover {
    background-color: #999;
  }
</style>
<form id="claim_form" enctype="multipart/form-data" action="submit" method="post">
<div id="wizard">
    <div class="row hidden-phone">
      <div class="span12">
        <ul class="nav nav-pills pull-right" id="progress_indicator"></ul>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h2 id="step_title"></h2>
        <div class="well" id="step_instructions"></div>
        <div id="step_error"></div>
      </div>
    </div>
    <div id="current_step_container"></div>
    <div class="form-actions">
      <div class="pull-right">
        <img src="<?php echo glue("route")->url('/img/ajax-loader.gif') ?>" id="wizard_loader" class="loader" style="display:none">
        <button type="button" id="prev_step_button" class="btn" href="#">Anterior</button>
        <button type="button" id="next_step_button" class="btn btn-primary" href="#">Siguiente</button>
      </div>
    </div>
</div>
</form>
<script type='text/javascript'>
    $(document).ready(function(){
        window.Wizard = new Ocs.Wizard.Wizard({el: $('#wizard')});
    });
</script>


<!-- TEMPLATES -->
<script type="text/template" id="wizard_next_button_tmpl">
    <%= title %> &gt;&gt;
</script>
<script type="text/template" id="wizard_prev_button_tmpl">
    &lt;&lt; <%= title %>
</script>
<script type="text/template" id="wizard_progress_indicator_tmpl">
    <li class="<%= css_class %>" ><a href="#"><%= title %></a></li>
</script>
<script type="text/template" id="wizard_tipo_problema">
    <?php include __DIR__ . '/wizard_tipo_problema.php'?>
</script>

