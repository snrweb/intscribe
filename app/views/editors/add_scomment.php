<?php 
    use Core\CSRF;
    $this->setSiteTitle('Add comment') ?>

<?php $this->start('body') ?>

    <form class="editor shadow" 
        action="<?=PROOT?>subComment/add/<?= $this->post_id ?>/<?= $this->comment_id ?>" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>

        <div class="reference">
            <p><?= $this->ref ?></p>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->sub_comment_error?></small>
            <textarea name="sub_comment" rows="10" placeholder="Type your comment..."></textarea>
        </div>

        <div class="input-wrapper">
            <button type="submit" class="btn">Submit Comment</button>
        </div>

    </form>

<?php $this->end() ?>