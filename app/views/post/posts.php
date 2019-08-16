<?php use Core\Session; ?>
<?php foreach($this->posts as $p): ?>
    <?php if($p->post_type == 'Article'): ?>
        <div class="c2-posts">
            <div class="c2-post">
                <div class="c2-post-head">
                    
                    <?php if(empty($p->profile_image)): ?>
                        <div class="c2-poster-image img"
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                            <img class="img-decoy" alt="<?= $p->username ?>" />
                        </div>
                    <?php endif ?>

                    <?php if(!empty($p->profile_image)): ?>
                        <div class="c2-poster-image img" 
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $p->profile_image ?>)">
                            <img class="img-decoy" alt="<?= $p->username ?>" />
                        </div>
                    <?php endif ?>

                    <div class="c2-poster-profile">
                        <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $p->username) ?>-<?= $p->user_id ?>">
                            <p class="c2-poster-username"><?= $p->username ?></p>
                        </a>
                        <p class="c2-post-time"><?= setTime($p->created_at) ?></p>
                    </div>
                    <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $p->post_int) ?>">
                        <p class="c2-interest-name"><?= $p->post_int ?></p>
                    </a>
                    <div class="clear-float"></div>
                </div>
                <div class="c2-post-in">
                    <a href="<?=PROOT?>post/<?= str_replace(["?", " "], ["", "-"], $p->post_title) ?>-<?= $p->post_id ?>">
                        <p class="c2-post-title"><?= $p->post_title ?></p>
                        <p class="c2-post-body">
                            <?= (strlen($p->main_post) > 200) ? substr($p->main_post, 0, strpos($p->main_post, ' ', 180)) : $p->main_post?>
                        </p>
                    </a>
                </div>
                <div class="c2-post-counts">
                    <p class="c2-comment-count" style="margin-top: -5px;">
                        <?= file_get_contents(ROOT.'/public/images/svg/comment.svg') ?>  
                        <?= setCount($p->post_comments) ?> 
                    </p>

                    <p class="c2-upvote-count">
                        <?php if(isset($p->status)): ?>
                            <?php if($p->status == 1): ?>
                                <span class="c2-upvote-status">upvoted</span>  
                            <?php endif ?>
                            
                            <?php if($p->status == -1): ?>
                                <span class="c2-upvote-status">downvoted</span>  
                            <?php endif ?>
                        <?php endif ?>
                        <span style="<?= setColor($p->post_promotes) ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/arrow-up.svg') ?>
                        </span>

                        <?= setCount($p->post_promotes) ?> 

                        <span style="<?= setColor($p->post_promotes, 'tag') ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/arrow-down.svg') ?>
                        </span>
                    </p>

                    <a href="<?=PROOT?>bookmark/add/<?= $p->post_id ?>/<?= $this->page_name ?>">
                        <p class="c2-bookmark" style="<?php if(isset($p->bookmark_id)) echo setColor($p->bookmark_id) ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/bookmark.svg') ?>
                        </p>
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>

    
    <?php if($p->post_type == 'Question'): ?>
        <div class="c2-posts">
            <div class="c2-post">
                <div class="c2-post-head" style="border-bottom: 1px solid #a09f9f; color: #3b3b3b;">
                    <p class="pull-left" style="margin: -4px 0 4px">Question</p>
                    <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $p->post_int) ?>">
                        <p class="pull-right" style="margin: -4px 0 4px"><?= $p->post_int ?></p>
                    </a>
                    <div class="clear-float"></div>
                </div>
                <div class="c2-post-in">
                    <a href="<?=PROOT?>post/<?= str_replace(["?", " "], ["", "-"], $p->post_title) ?>-<?= $p->post_id ?>">
                        <p class="c2-post-title"><?= $p->post_title ?></p>
                    </a>
                    <a href="<?= $p->question_link ?>" target="_blank"
                        style="display: block; font-size: 0.9em; color: grey; margin-top: -7px; 
                        margin-bottom: 10px;"> <?= $p->question_link ?> </a>
                </div>
                <div class="c2-post-counts">
                    <p class="c2-comment-count" style="margin-top: -5px;">
                        <?= file_get_contents(ROOT.'/public/images/svg/comment.svg') ?> 
                        <?= setCount($p->post_comments) ?>
                    </p>
                    <p class="c2-upvote-count">
                        <?php if(isset($p->status)): ?>
                            <?php if($p->status == 1): ?>
                                <span class="c2-upvote-status">upvoted</span>  
                            <?php endif ?>
                            
                            <?php if($p->status == -1): ?>
                                <span class="c2-upvote-status">downvoted</span>  
                            <?php endif ?>
                        <?php endif ?>
                        <span style="<?= setColor($p->post_promotes) ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/arrow-up.svg') ?>
                        </span>

                        <?= setCount($p->post_promotes) ?> 

                        <span style="<?= setColor($p->post_promotes, 'tag') ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/arrow-down.svg') ?>
                        </span>
                    </p>

                    <a href="<?=PROOT?>bookmark/add/<?= $p->post_id ?>/<?= $this->page_name ?>">
                        <p class="c2-bookmark" style="<?php if(isset($p->bookmark_id)) echo setColor($p->bookmark_id) ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/bookmark.svg') ?>
                        </p>
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if($p->post_type == 'Poll'): ?>
        <?php
            $sum = $p->option_one_count + $p->option_two_count + $p->option_three_count + $p->option_four_count;
        ?>
        <div class="c2-posts">
            <div class="c2-post">
                <div class="c2-post-head">

                    <?php if(empty($p->profile_image)): ?>
                        <div class="c2-poster-image img"
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                            <img class="img-decoy" alt="<?= $p->username ?>" />
                        </div>
                    <?php endif ?>

                    <?php if(!empty($p->profile_image)): ?>
                        <div class="c2-poster-image img" 
                            style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $p->profile_image ?>)">
                            <img class="img-decoy" alt="<?= $p->username ?>" />
                        </div>
                    <?php endif ?>

                    <div class="c2-poster-profile">
                        <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $p->username) ?>-<?= $p->user_id ?>">
                            <p class="c2-poster-username"><?= $p->username ?></p>
                        </a>
                        <p class="c2-post-time"><?= setTime($p->created_at) ?></p>
                    </div>
                    <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $p->post_int) ?>">
                        <p class="c2-interest-name"><?= $p->post_int ?></p>
                    </a>
                    <div class="clear-float"></div>
                </div>
                <div class="c2-post-in">
                    <p class="c2-post-title"><?= $p->post_title ?></p>
                    <div class="c2-poll-options">
                        <a href="<?=PROOT?>post/addVote/<?= $p->post_id ?>/1/<?= $this->page_name ?>" >
                            <div class="c2-poll-option">
                                <div class="c2-poll-option-active" 
                                        style="width: <?= $sum != 0 ? round(($p->option_one_count / $sum) * 100) : 0 ?>% "></div>
                                <p class="c2-poll-option-c"> 
                                    <?= $sum != 0 ? round(($p->option_one_count / $sum) * 100) : 0 ?>% 
                                </p>
                                <p class="c2-poll-option-o"><?= $p->option_one ?></p>
                                <div class="c2-poll-option-click"></div>
                            </div>
                        </a>
                        
                        <a href="<?=PROOT?>post/addVote/<?= $p->post_id ?>/2/<?= $this->page_name ?>" >
                            <div class="c2-poll-option">
                                <div class="c2-poll-option-active" 
                                        style="width: <?= $sum != 0 ? round(($p->option_two_count / $sum) * 100) : 0 ?>% "></div>
                                <p class="c2-poll-option-c">
                                    <?= $sum != 0 ? round(($p->option_two_count / $sum) * 100) : 0 ?>% 
                                </p>
                                <p class="c2-poll-option-o"><?= $p->option_two ?></p>
                                <div class="c2-poll-option-click"></div>
                            </div>
                        </a>
                        
                        <?php if(!empty($p->option_three)): ?>
                            <a href="<?=PROOT?>post/addVote/<?= $p->post_id ?>/3/<?= $this->page_name ?>" >
                                <div class="c2-poll-option">
                                    <div class="c2-poll-option-active" 
                                        style="width: <?= $sum != 0 ? round(($p->option_three_count / $sum) * 100) : 0 ?>% "></div>
                                    <p class="c2-poll-option-c"> 
                                        <?= $sum != 0 ? round(($p->option_three_count / $sum) * 100) : 0 ?>% 
                                    </p>
                                    <p class="c2-poll-option-o"><?= $p->option_three ?></p>
                                    <div class="c2-poll-option-click"></div>
                                </div>
                            </a>
                        <?php endif ?>
                        
                        <?php if(!empty($p->option_four)): ?>
                            <a href="<?=PROOT?>post/addVote/<?= $p->post_id ?>/4/<?= $this->page_name ?>" >
                                <div class="c2-poll-option">
                                    <div class="c2-poll-option-active" 
                                        style="width: <?= $sum != 0 ? round(($p->option_four_count / $sum) * 100) : 0 ?>% "></div>
                                    <p class="c2-poll-option-c"> 
                                        <?= $sum != 0 ? round(($p->option_four_count / $sum) * 100) : 0 ?>%  
                                    </p>
                                    <p class="c2-poll-option-o"><?= $p->option_four ?></p>
                                    <div class="c2-poll-option-click"></div>
                                </div>
                            </a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="c2-post-counts">
                    <p class="c2-comment-count">Total votes <?= $sum ?> </p>
                    <p class="c2-upvote-count">
                        <?= new DateTime(date('Y-m-d H:i:s', time())) > new DateTime($p->duration) ? 'Closed' : 'Ongoing' ?>
                    </p>

                    <?php if($p->user_id == Session::get(USER_SESSION_NAME)): ?>
                        <a href="<?=PROOT?>post/delete/<?= $p->post_id ?>">
                            <p class="c2-post-delete">Delete</p>
                        </a>
                    <?php endif ?>
                    
                    <a href="<?=PROOT?>bookmark/add/<?= $p->post_id ?>/<?= $this->page_name ?>">
                        <p class="c2-bookmark" style="<?php if(isset($p->bookmark_id)) echo setColor($p->bookmark_id) ?>">
                            <?= file_get_contents(ROOT.'/public/images/svg/bookmark.svg') ?>
                        </p>
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endforeach ?>