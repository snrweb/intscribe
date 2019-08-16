<?php 
    use Core\CSRF;
    $this->setSiteTitle('Add Poll') ?>

<?php $this->start('body') ?>

    <form class="editor shadow" action="<?=PROOT?>post/insertPoll" method="post">
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
            <small class="errorDisplay"><?= $this->post_title_error ?></small>
            <textarea name="post_title" rows="5" placeholder="Question..." 
                maxLength="200"><?=$this->post_title?></textarea>
        </div>
        
        <div class="input-wrapper">
            <small class="errorDisplay"><?= $this->option_one_error ?></small>
            <input class="option" type="text" name="option_one" maxLength="100" 
                    placeholder="Option 1" value="<?= $this->option_one ?>">
        </div>
        
        <div class="input-wrapper">
            <small class="errorDisplay"><?= $this->option_two_error ?></small>
            <input class="option" type="text" name="option_two" maxLength="100" 
                    placeholder="Option 2" value="<?= $this->option_two ?>">
        </div>
        
        <?php if($this->count == 3 || $this->count == 4): ?>
            <div class="input-wrapper">
                <small class="errorDisplay"><?= $this->option_three_error ?></small>
                <input class="option" type="text" name="option_three" maxLength="100" 
                        placeholder="Option 3" value="<?= $this->option_three ?>">
            </div>
        <?php endif ?>
        
        <?php if($this->count == 4): ?>
            <div class="input-wrapper">
                <small class="errorDisplay"><?= $this->option_four_error ?></small>
                <input class="option" type="text" name="option_four" maxLength="100" 
                        placeholder="Option 4" value="<?= $this->option_four ?>">
            </div>
        <?php endif ?>

        <div class="counter">
            <a href="<?=PROOT?>post/insertPoll/<?= ($this->count == 4) ? $this->count + 0 : $this->count + 1 ?>" 
                class="btn"> + </a>
            <a href="<?=PROOT?>post/insertPoll/<?= ($this->count == 2) ? $this->count - 0 : $this->count - 1 ?>" 
                class="btn"> - </a>
        </div>

        <div class="poll-editor-duration">
            <div class="poll-editor-mins">
                <label>Mins</label>
                <select name="poll-editor-mins">
                    <?php for ($i = 0; $i < 60; $i++) { echo '<option>'.$i.'</option>'; } ?>
                </select>
            </div>
            
            <div class="poll-editor-hours">
                <label>Hours</label>
                <select name="poll-editor-hours">
                    <?php for ($i = 0; $i < 24; $i++) { echo '<option>'.$i.'</option>'; } ?>
                </select>
            </div>
            
            <div class="poll-editor-days">
                <label>Days</label>
                <select name="poll-editor-days">
                    <?php for ($i = 0; $i < 8; $i++) { echo '<option>'.$i.'</option>'; } ?>
                </select>
            </div>
        </div>

        <div class="input-wrapper">
            <button type="submit" class="btn">Start Poll</button>
        </div>

    </form>

<?php $this->end() ?>