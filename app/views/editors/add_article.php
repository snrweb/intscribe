<?php 
    use Core\CSRF;
    $this->setSiteTitle('Add Article') ?>

<?php $this->start('body') ?>

    <form class="editor shadow" action="<?=PROOT?>post/insertArticle" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->post_title_error?></small>
            <input class="title" type="text" name="post_title" maxLength="200" 
                    placeholder="Title..." value="<?=$this->post_title?>">
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->post_int_error?></small>
            <select name="post_int" class="select">
                <option> Pick an interest </option>
                <?php foreach($this->user_interests as $u) : ?>
                    <option> <?= $u->interest ?> </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->main_post_error?></small>
            <textarea name="main_post" rows="10" placeholder="Article..."><?=$this->main_post?></textarea>
        </div>
        
        <input type="hidden" name="isJSeditor" value="0">

        <div class="input-wrapper">
            <button type="submit" class="btn">Submit Article</button>
        </div>

    </form>

<?php $this->end() ?>