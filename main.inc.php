<?php
include dirname(__FILE__) . "/inc/head.php";
// Navbar
include dirname(__FILE__) . "/inc/top.php";
?>
<div class="maincontent d-flex justify-content-center align-items-center">
    <?php
        include dirname(__FILE__) . "/inc/content.php";
    ?>
</div>
<?php
ob_end_flush();