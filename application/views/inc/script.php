<script type="text/javascript" src="<?php echo base_url(); ?>plugins/jquery/jquery-3.7.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-5.0.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/AdminLTE-3.2.0/js/adminlte.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/sweetalert2-11.10.3/sweetalert2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/qrcode/easy.qrcode.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/FlipClock-0.7.7/compiled/flipclock.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/html5-qrcode/html5-qrcode.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/fullcalendar/main.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/fullcalendar-daygrid/main.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/fullcalendar-timegrid/main.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/fullcalendar-interaction/main.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/fullcalendar-bootstrap/main.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/jquery-number/jquery.number.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/signature-pad/signature_pad.umd.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<?php
//if ($this->uri->segment(1) && !in_array($this->uri->segment(1), ['concept_1', 'concept_2']) && !in_array($this->input->get('head'), ['concept_1', 'concept_2'])) {
?>

<script type="text/javascript" src="<?= base_url('plugins/moment/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/moment/moment-with-locales.min.js') ?>"></script>

<?php //} 
?>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>plugins/barrating/jquery.barrating.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>dist/js/root.js?v=<?= time() ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/jquery.mask.min.js"></script>
<?php

$jsFilename = $this->uri->segment(1) . ".js";

if ($this->uri->total_segments() === 0) {
    echo "<script type='text/javascript' src='" . base_url('dist/js/menu.js?v=' . time()) . "'></script>" . PHP_EOL;
} else {
    if (file_exists(FCPATH . 'dist/js/' . $this->uri->segment(1) . ".js")) {
        //echo '<!-- Load JS Files Folder ' . $segment . '/' . $this->uri->segment(1) . ' -->' . PHP_EOL;
        echo "<script type='text/javascript' src='" . base_url('dist/js/' . $jsFilename . '?v=' . time()) . "'></script>" . PHP_EOL;
    }
}
?>