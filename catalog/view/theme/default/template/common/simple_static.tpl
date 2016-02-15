    <!-- Simple v <?php echo Simple::$version ?> -->
    <script type="text/javascript">
    var simple_comment_target = '<?php echo $simple->get_simple_comment_target(); ?>'; 
    var simple_route = '<?php echo htmlspecialchars_decode($simple->tpl_joomla_route()); ?>'; 
    var simple_path = '<?php echo $simple->tpl_joomla_path(); ?>'; 
    var simple_asap = <?php echo $simple->get_checkout_asap(); ?>; 
    var simple_googleapi = <?php echo $simple->check_googleapi_enabled(); ?>; 
    var simple_fix_onchange_and_click = false;
    var simple_steps = <?php echo $simple->get_simple_steps(); ?>; 
    var simple_steps_summary = <?php echo $simple->get_simple_steps_summary(); ?>; 
    </script>
