<div class="reportDiv">
    <div class="reportHeadDiv">
        <p>
            We appreciate you reporting this <?php echo $report_on ?> <?php echo $_SESSION['username'] ?>,
            your report will be reviewed and necessary actions
            will be taken.
        </p>
    </div>
    <textarea rows="10" id="report-text"></textarea>
    <button class="report-btn" id="<?php echo $report_on //post or comment or subcomment ?>"
        data-post="<?php echo $pid //post id ?>" data-c="<?php echo $cid //comment id ?>" data-sc="<?php echo $scid ?>">Submit</button>
</div>