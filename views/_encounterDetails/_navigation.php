<div style="height:30px;position:fixed;width:100px;right:15;top:15;background-color:white;">
    <div style="padding:7px;"><a href="<?php echo $firstPageLink; ?>">First Page</a></div>
    <?php 
        if($nextPageLink){
            echo '<div style="padding:7px;"><a href="'.$nextPageLink.'">Next Page</a></div>';
        }
    ?>
    <hr>
    <?php require_once('views/_reportIdForm.php'); ?>
</div>
