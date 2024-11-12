<footer>
    <center>&copy; Copyright <?php echo date('Y'); ?></center>
</footer>

<!-- JavaScript files -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/materialize.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/materialize.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/data-tables/data-tables-script.js"></script>

<!-- Plugin settings -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins.js"></script>

<script>
    $(".button-collapse").sideNav();

    $('#alert_close').click(function() {
        $("#alert_box").fadeOut("slow");
    });
</script>
</body>
</html>
