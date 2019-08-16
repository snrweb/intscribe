<?php use Core\Session; ?>

<?php $this->setSiteTitle($this->post->post_title) ?>

<?php $this->start('body') ?>
    <?= $this->component('column_one') ?>

    <div class="post-page-column">
        <!-- post -->
        <div class="pg-post">
            <?php if($this->post->post_type == "Article") : ?>
                <p class="pg-post-title"><?= $this->post->post_title ?> </p>
                <div class="pg-post-head">

                    <?php if(empty($this->post->profile_image)): ?>
                        <div class="pg-poster-image img"
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                            <img class="img-decoy" alt="<?= $this->post->username ?>" />
                        </div>
                    <?php endif ?>

                    <?php if(!empty($this->post->profile_image)): ?>
                        <div class="pg-poster-image img" 
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $this->post->profile_image ?>)">
                            <img class="img-decoy" alt="<?= $this->post->username ?>" />
                        </div>
                    <?php endif ?>
                    
                    <div class="pg-poster-profile">
                        <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->post->username) ?>-<?= $this->post->user_id ?>">
                            <p class="pg-poster-username"><?= $this->post->username ?></p>
                        </a>
                        <p class="pg-post-time"><?= setTime($this->post->created_at) ?></p>
                    </div>

                    <a href="interest/<?= str_replace(" ", "-", $this->post->post_int) ?>">
                        <p class="pg-interest-name"><?= $this->post->post_int ?></p>
                    </a>
                    
                    <?php if(Session::get(USER_SESSION_NAME) != $this->post->user_id): ?>
                        <a href="<?=PROOT?>follow/pfollow/<?= $this->post->post_id ?>/<?= $this->post->user_id ?>">
                            <p class="pg-follow-poster-btn" style="<?= setFollowColor($this->post->follow_id) ?>">
                                <?= file_get_contents(ROOT.'/public/images/svg/follow.svg') ?>
                            </p>
                        </a>
                    <?php endif ?>
                    <div class="clear-float"></div>
                </div>
                <div class="pg-post-body">
                    <p class="pg-post-in"><?= $this->post->main_post ?></p>
                </div>
            <?php endif ?>

            <?php if($this->post->post_type == "Question") : ?>
                <p class="pg-post-title"><?= $this->post->post_title ?> <a
                    href="<?= $this->post->question_link ?>"
                    target="_blank"
                    style="font-size: 0.5em; color: grey;"> <?= $this->post->question_link ?> </a> </p>
                <span class="pg-post-q-span">Asked by <?= $this->post->username ?> - <?= setTime($this->post->created_at) ?></span>
            <?php endif ?>

            <div class="pg-post-ft-2">
                <?php if($this->post->user_id == Session::get(USER_SESSION_NAME)): ?>
                    <a href="<?=PROOT?>post/delete/<?= $this->post->post_id ?>">
                        <p class="pg-p-comment-delete">
                            <?= file_get_contents(ROOT.'/public/images/svg/delete.svg') ?> <span>Delete</span>
                        </p>
                    </a>
                <?php endif ?>

                <a href="<?=PROOT?>report/report/Post/<?= $this->post->post_id ?>">
                    <p class="pg-p-comment-report">Report</p>
                </a>

                <?php if($this->post->user_id == Session::get(USER_SESSION_NAME) && $this->post->post_type == 'Article'): ?>
                    <a href="<?=PROOT?>post/editArticle/<?= $this->post->post_id ?>">
                        <p class="pg-p-comment-edit">
                            <?= file_get_contents(ROOT.'/public/images/svg/edit.svg') ?> <span>Edit</span>
                        </p>
                    </a>
                <?php endif ?>

                <?php if($this->post->user_id == Session::get(USER_SESSION_NAME) && $this->post->post_type == 'Question'): ?>
                    <a href="<?=PROOT?>post/editQuestion/<?= $this->post->post_id ?>">
                        <p class="pg-p-comment-edit">
                            <?= file_get_contents(ROOT.'/public/images/svg/edit.svg') ?> <span>Edit</span>
                        </p>
                    </a>
                <?php endif ?>
            </div>
            <div class="clear-float"></div>

            <div class="pg-post-ft">
                <a href="<?=PROOT?>comment/add/<?= $this->post->post_id ?>">
                    <p class="pg-p-comment-add">Add comment</p>
                </a>

                <p class="pg-p-upvote-count">
                    <a href="<?=PROOT?>post/promote/<?= $this->post->post_id ?>" 
                        style="<?= setColor($this->post->status) ?>">
                        <?= file_get_contents(ROOT.'/public/images/svg/arrow-up.svg') ?>  
                    </a>
                    <?= setCount($this->post->post_promotes) ?> 
                    <a href="<?=PROOT?>post/demote/<?= $this->post->post_id ?>" 
                        style="<?= setColor($this->post->status, 'tag') ?>">
                        <?= file_get_contents(ROOT.'/public/images/svg/arrow-down.svg') ?>  
                    </a>
                </p>

                <a href="<?=PROOT?>bookmark/add/<?= $this->post->post_id ?>/<?= $this->page_name ?>">
                    <p class="pg-p-bookmark" 
                        style="<?php if(isset($this->post->bookmark_id)) echo setColor($this->post->bookmark_id) ?>">
                        <?= file_get_contents(ROOT.'/public/images/svg/bookmark.svg') ?>  
                    </p>
                </a>
            </div>
        </div>
        
        <!-- comment -->
        <?php foreach($this->comments as $c) : ?>
            <div class="pg-comment">
                <div class="pg-comment-head">

                    <?php if(empty($c->profile_image)): ?>
                        <div class="pg-commenter-image img"
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                            <img class="img-decoy" alt="<?= $c->username ?>" />
                        </div>
                    <?php endif ?>

                    <?php if(!empty($c->profile_image)): ?>
                        <div class="pg-commenter-image img" 
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $c->profile_image ?>)">
                            <img class="img-decoy" alt="<?= $c->username ?>" />
                        </div>
                    <?php endif ?>

                    <div class="pg-commenter-profile">
                        <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $c->username) ?>-<?= $c->user_id ?>">
                            <p class="pg-commenter-username"><?= $c->username ?></p>
                        </a>
                        <p class="pg-comment-time"><?= setTime($c->created_at) ?></p>
                    </div>
                    <div class="clear-float"></div>
                </div>
                <div class="pg-comment-body">
                    <p class="pg-comment-in"><?= $c->comment ?></p>
                </div>

                <div class="pg-comment-ft-2">
                    <?php if($c->user_id == Session::get(USER_SESSION_NAME)): ?>
                        <a href="<?=PROOT?>comment/delete/<?= $this->post->post_id ?>/<?= $c->comment_id ?>">
                            <p class="pg-c-comment-delete">
                                <?= file_get_contents(ROOT.'/public/images/svg/delete.svg') ?> <span>Delete</span>
                            </p>
                        </a>
                    <?php endif ?>

                    <?php if($c->user_id == Session::get(USER_SESSION_NAME)): ?>
                        <a href="<?=PROOT?>comment/edit/<?= $c->comment_id ?>">
                            <p class="pg-c-comment-edit">
                                <?= file_get_contents(ROOT.'/public/images/svg/edit.svg') ?> <span>Edit</span>
                            </p>
                        </a>
                    <?php endif ?>
                    <div class="clear-float"></div>
                </div>

                <div class="pg-comment-ft">
                    <a href="<?=PROOT?>subComment/add/<?= $this->post->post_id ?>/<?= $c->comment_id ?>">
                        <p class="pg-c-comment-reply">
                            <?= file_get_contents(ROOT.'/public/images/svg/reply.svg') ?>  
                            <span>Reply</span>
                        </p>
                    </a>

                    <p class="pg-c-upvote-count">
                        <a href="<?=PROOT?>comment/promote/<?= $this->post->post_id ?>/<?= $c->comment_id ?>" 
                            style="<?= setColor($c->status) ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/arrow-up.svg') ?>  
                        </a>
                        <?= setCount($c->comment_promotes) ?> 
                        <a href="<?=PROOT?>comment/demote/<?= $this->post->post_id ?>/<?= $c->comment_id ?>" 
                            style="<?= setColor($c->status, 'tag') ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/arrow-down.svg') ?>  
                        </a>
                    </p>
                    
                    <a href="<?=PROOT?>report/report/Comment/<?= $this->post->post_id ?>/<?= $c->comment_id ?>">
                        <p class="pg-c-comment-report">Report</p>
                    </a>
                </div>

                <!-- sub-comment -->
                <?php foreach($this->sub_comments as $sc) : ?>
                    <?php if($sc->comment_id == $c->comment_id) : ?>
                        <div class="pg-sub-comment">
                            <div class="pg-sub-comment-head">

                                <?php if(empty($sc->profile_image)): ?>
                                    <div class="pg-sub-commenter-image img"
                                        style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                                        <img class="img-decoy" alt="<?= $sc->username ?>" />
                                    </div>
                                <?php endif ?>

                                <?php if(!empty($sc->profile_image)): ?>
                                    <div class="pg-sub-commenter-image img" 
                                        style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $sc->profile_image ?>)">
                                        <img class="img-decoy" alt="<?= $sc->username ?>" />
                                    </div>
                                <?php endif ?>

                                <div class="pg-sub-commenter-profile">
                                <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $sc->username) ?>-<?= $sc->user_id ?>">
                                    <p class="pg-sub-commenter-username"><?= $sc->username ?></p>
                                </a>
                                    <p class="pg-sub-comment-time"><?= setTime($sc->created_at) ?></p>
                                </div>
                                <div class="clear-float"></div>
                            </div>
                            <div class="pg-sub-comment-body">
                                <p class="pg-comment-in"><?= $sc->sub_comment ?></p>
                            </div>
                            <div class="pg-sub-comment-ft">
                                <?php if($sc->user_id == Session::get(USER_SESSION_NAME)): ?>
                                    <a href="<?=PROOT?>subComment/edit/<?= $sc->sub_comment_id ?>">
                                        <p class="pg-sc-comment-edit">
                                            <?= file_get_contents(ROOT.'/public/images/svg/edit.svg') ?> <span>Edit</span>
                                        </p>
                                    </a>
                                <?php endif ?>

                                <?php if($sc->user_id == Session::get(USER_SESSION_NAME)): ?>
                                    <a href="<?=PROOT?>subComment/delete/<?= $this->post->post_id ?>/<?= $sc->sub_comment_id ?>">
                                        <p class="pg-sc-comment-delete">
                                            <?= file_get_contents(ROOT.'/public/images/svg/delete.svg') ?> <span>Delete</span>
                                        </p>
                                    </a>
                                <?php endif ?>

                                <a href="<?=PROOT?>report/report/SubComment/<?= 
                                            $this->post->post_id ?>/<?= $c->comment_id ?>/<?= $sc->sub_comment_id ?>">
                                    <p class="pg-sub-comment-report">Report</p>
                                </a>

                                <div class="clear-float"></div>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>
    </div>
<?php $this->end() ?>
