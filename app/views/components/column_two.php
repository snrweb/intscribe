<?php use Core\Session; ?>

<div class="columnTwo">
    
    <?php if($this->page_name == 'notification'): ?>
        <div class="notification-title">Notifications</div>
        <?= $this->component('notification/notification_list') ?>
    <?php endif ?>

    <?php if($this->page_name != 'notification'): ?>
        <div class="c2-editor-buttons">
            <a href="<?=PROOT?>post/insertArticle" class="c2-article-button">
                <?= file_get_contents(ROOT.'/public/images/svg/article.svg') ?> 
                <span>Article</span>
            </a>

            <a href="<?=PROOT?>post/insertQuestion" class="c2-question-button">
                <?= file_get_contents(ROOT.'/public/images/svg/question.svg') ?> 
                <span>Question</span>
            </a>
            
            <a href="<?=PROOT?>post/insertPoll" class="c2-poll-button">
                <?= file_get_contents(ROOT.'/public/images/svg/poll.svg') ?> 
                <span>Poll</span>
            </a>
        </div>
        
        <?= $this->component('post/posts') ?>
    <?php endif ?>

</div>