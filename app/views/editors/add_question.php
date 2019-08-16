<?php 
    use Core\CSRF;
    $this->setSiteTitle('Ask Question') ?>

<?php $this->start('body') ?>

    <form class="editor shadow" action="<?=PROOT?>post/insertQuestion" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>

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
            <small class="errorDisplay"><?=$this->post_title_error?></small>
            <textarea name="post_title" rows="5" placeholder="Question..."><?=$this->post_title?></textarea>
        </div>

        <div className="input-wrapper">
          <input
            class="option"
            type="text"
            name="question_link"
            maxLength="100"
            placeholder="Link (optional)"
            style="margin: 10px 0"
          />
        </div>

        <div class="input-wrapper">
            <button type="submit" class="btn">Submit Question</button>
        </div>

    </form>

<?php $this->end() ?>