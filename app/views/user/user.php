<?php 
    use Core\Session;
    $this->setSiteTitle($this->user->username) ?>

<?php $this->start('body') ?>
    <section class="user-page-column">
        <div class="user-details-section">

            <?php if(empty($this->user->profile_image)): ?>
                <div class="user-profile-image img"
                    style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                    <img class="img-decoy" alt="<?= $this->user->username ?>" />
                </div>
            <?php endif ?>

            <?php if(!empty($this->user->profile_image)): ?>
                <div class="user-profile-image img" 
                    style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $this->user->profile_image ?>)">
                    <img class="img-decoy" alt="<?= $this->user->username ?>" />
                </div>
            <?php endif ?>

            <div class="user-username-div">
                <p class="user-username"><?= $this->user->username ?></p>
                <?php if($this->user->user_id != Session::get(USER_SESSION_NAME)): ?>
                    <a href="<?=PROOT?>follow/mfollow/<?= $this->user->user_id ?>">
                        <button class="btn user-follow-btn"><?= $this->isFollowing ? 'Following' : 'Follow' ?></button>
                    </a>
                <?php endif ?>
            </div>
            
            <?php if($this->user->user_id == Session::get(USER_SESSION_NAME)) : ?>
                <a href="<?=PROOT?>user/editProfile"><button class="user-edit-button btn">Edit</button> </a>
            <?php endif ?>
            <div class="clear-float"></div>
        </div>

        <div class="user-menus">
            <?php if($this->user->user_id == Session::get(USER_SESSION_NAME)) : ?>
                <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/interest">
                    <button>Interests <span><?= $this->interest_count ?></span></button>
                </a>
            <?php endif ?>
            
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/">
                <button>Posts <span><?= $this->post_count ?></span></button>
            </a>
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/follower">
                <button>Followers <span><?= $this->follower_count ?></span></button>
            </a>
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/following">
                <button>Following <span><?= $this->following_count ?></span></button>
            </a>

            <?php if($this->user->user_id == Session::get(USER_SESSION_NAME)) : ?>
                <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/bookmark">
                    <button>Bookmarks <span><?= $this->bookmark_count ?></span></button>
                </a>
            <?php endif ?>
        </div>

        <?php if($this->cmp == ""): ?>
            <div class="user-menu-posts">
                <?= $this->component('post/posts') ?>
            </div>
        <?php endif ?>

        <?php if($this->cmp == "interest"): ?>
            <div class="user-menu-details">
                <?php foreach($this->interests as $i): ?>
                    <div class="user-profile-interest">
                        <img src="<?=PROOT?>public/images/profile_pic/avatar.jpg">
                        <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $i->interest) ?>">
                            <span><?= $i->interest ?></span>
                        </a>
                        <button> 
                            <a href="<?=PROOT?>interest/addInterest/<?= 
                                str_replace(" ", "-", $i->interest) ?>/<?= $this->user->user_id ?>">
                                <?= file_get_contents(ROOT.'/public/images/svg/mark.svg') ?>
                            </a>
                        </button>
                    </div>
                <?php endforeach ?>
                <div class="clear-float"></div>
            </div>
        <?php endif ?>

        <?php if($this->cmp == "follower"): ?>
            <div class="user-menu-details">
                <?= $this->component('follow/follower') ?>
            </div>
        <?php endif ?>

        <?php if($this->cmp == "following"): ?>
            <div class="user-menu-details">
                <?= $this->component('follow/following') ?>
            </div>
        <?php endif ?>

        <?php if($this->cmp == "bookmark"): ?>
            <div class="user-menu-posts">
                <?= $this->component('post/posts') ?>
            </div>
        <?php endif ?>

    </section>
<?php $this->end() ?>
