<?php 
    use Core\CSRF;
    $this->setSiteTitle('Intscribe - Report '.$this->report_tag) ?>

<?php $this->start('body') ?>

    <form class="editor shadow" 
        action="<?=PROOT?>report/report/<?= 
                        $this->report_tag ?>/<?= $this->post_id ?>/<?= 
                        $this->comment_id ?>/<?= $this->subcomment_id ?>" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>

        <div class="reference">
            <p><?= $this->ref ?></p>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->report_error?></small>
            <textarea name="report" rows="10" placeholder="Type your report..."></textarea>
        </div>

        <input type="hidden" name="report_tag" value="<?= $this->report_tag ?>" />
        <input type="hidden" name="post_id" value="<?= $this->post_id ?>" />

        <div class="input-wrapper">
            <button type="submit" class="btn">Submit Report</button>
        </div>

    </form>

<?php $this->end() ?>