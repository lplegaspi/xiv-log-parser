<div style="position:fixed;width:100px;right:15;top:15;background-color:white;">
    <a class="fwb" href="<?php echo HttpQueryManager::makeReportUrl($reportId); ?>">&lt; Encounter List</a>
    <hr/>
    <?php
        if($firstPageLink){
            echo '<div style="padding:7px;"><a href="'.$firstPageLink.'">First Page</a></div>';
        }
        if($nextPageLink){
            echo '<div style="padding:7px;"><a href="'.$nextPageLink.'">Next Page</a></div>';
        }
    ?>
    <hr/>
    <?php require_once('views/_reportIdForm.php'); ?>
</div>
