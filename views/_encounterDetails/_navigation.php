<div class="w100p bg-white cb">
    <div>
        <div class="fl lh28"><a class="fwb" href="<?php echo HttpQueryManager::makeReportUrl($reportId); ?>">&lt; Encounter List</a></div>
        <div class="fr"><?php require_once('views/_reportIdForm.php'); ?></div>
        <div class="cb"></div>
    </div>
    <hr/>
    <div class="">
        <div class="fwb inline-block">Filter by : </div>
        <div class="inline-block">
            <?php require_once('views/_encounterDetails/_navigation/_filterByPlayerLinks.php'); ?>
        </div>
    </div>
    <hr/>
    <div>
        <?php require_once('views/_encounterDetails/_navigation/_pagination.php'); ?>
        <div class="cb"></div>
    </div>
    <div class="m5"></div>
</div>
