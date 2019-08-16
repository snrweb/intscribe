
<?php use Core\Session; ?>

<?php foreach($this->following as $f): ?>
    <div class="int-follow">

        <?php if(empty($f->profile_image)): ?>
            <div class="f-profile-image img"
                style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                <img class="img-decoy" alt="<?= $f->username ?>" />
            </div>
        <?php endif ?>

        <?php if(!empty($f->profile_image)): ?>
            <div class="f-profile-image img" 
                style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $f->profile_image ?>)">
                <img class="img-decoy" alt="<?= $f->username ?>" />
            </div>
        <?php endif ?>

        <span>
            <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $f->username) ?>-<?= $f->user_id ?>">
                <?= $f->username ?>
            </a>
        </span>

        <?php if($f->user_id != Session::get(USER_SESSION_NAME)) : ?>
            <a href="<?=PROOT?>follow/ufollow/<?= $f->user_id ?>/following">
                <button>Following</button>
            </a>
        <?php endif ?>
    </div>
<?php endforeach ?>
    
<div class="clear-float"></div>