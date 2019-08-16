<?php use Core\Session; ?>

<div class="columnOne">
    <?php if(Session::exists(USER_SESSION_NAME)) : ?>
        <div>
            <?php if(empty($this->user->profile_image)): ?>
                <div class="c1-profile-image img"
                    style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                    <img class="img-decoy" alt="<?= $this->user->username ?>" />
                </div>
            <?php endif ?>

            <?php if(!empty($this->user->profile_image)): ?>
                <div class="c1-profile-image img" 
                    style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $this->user->profile_image ?>)">
                    <img class="img-decoy" alt="<?= $this->user->username ?>" />
                </div>
            <?php endif ?>

            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>">
                <p class="c1-username pull-left"><?= $this->user->username ?></p>
            </a>
            <div class="clear-float"></div>
        </div>

        <div class="c1-user-options">
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/follower">
                <p>Followers <span><?= $this->follower_count ?></span></p>
            </a>
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/following">
                <p>Following <span><?= $this->following_count ?></span></p>
            </a>
        </div>

        <div class="c1-user-options">
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/">
                <p>Posts <span><?= $this->post_count ?></span></p>
            </a>
        </div>

        <div class="c1-user-options">
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/bookmark">
                <p>Bookmarks <span><?= $this->bookmark_count ?></span></p>
            </a>
        </div>

        <div class="c1-user-options">
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $this->user->username) ?>-<?= $this->user->user_id ?>/interest">
                <p>Interests <span><?= $this->interest_count ?></span></p>
            </a>
            <ul>
                <?php foreach($this->user_interests as $i): ?>
                    <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $i->interest) ?>">
                        <li><?= $i->interest ?></li>
                    </a>
                <?php endforeach ?>
            </ul>
        </div>

    <?php endif ?>

    <?php if(!Session::exists(USER_SESSION_NAME)) : ?>
        <h2 style="text-align: center; color: #525252; margin-top: 150px;">Please Login</h2>
    <?php endif ?>

    <footer>
        <p>intscribe.com &copy 2019</p>
        <a href="<?=PROOT?>privacy">Privacy</a>
        <a href="<?=PROOT?>terms">Terms</a>
        <a href="<?=PROOT?>logout">Logout</a>
    </footer>
</div>