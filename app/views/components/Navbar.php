<?php
    use Core\Session;
    if(Session::exists(USER_SESSION_NAME)) $userp = new App\Models\Users(Session::get(USER_SESSION_NAME));
?>
 
<nav class="navBar">
    <a href="<?=PROOT?>">
        <div class="navBarLogo" style="background-image: url(<?=PROOT?>public/images/logo/logo.jpg);"></div>
    </a>

    <div class="navBarSearch">
        <input type="text" placeholder="Search users, posts..." />
    </div>
    
    <div class="navBarSearch-icon">
            <?php echo file_get_contents(ROOT.'/public/images/svg/search.svg') ?> 
    </div>

    <?php if(Session::exists(USER_SESSION_NAME)): ?>
        <a href="<?=PROOT?>user/<?= str_replace(" ", "-", $userp->username) ?>-<?= $userp->user_id ?>/">
            <div class="navBarProfile">
                <?php if(empty($userp->profile_image)): ?>
                    <div class="img"
                        style="background-image: url(<?=PROOT?>public/images/profile_pic/avatar.jpg)">
                        <img class="img-decoy" alt="<?= $userp->username ?>" />
                    </div>
                <?php endif ?>

                <?php if(!empty($userp->profile_image)): ?>
                    <div class="img" 
                        style="background-image: url(<?=PROOT?>public/images/profile_pic/<?= $userp->profile_image ?>)">
                        <img class="img-decoy" alt="<?= $userp->username ?>" />
                    </div>
                <?php endif ?>
                <p><?= explode(" ", $userp->username)[0] ?></p>
            </div>
        </a>

        <a href="<?=PROOT?>notification">
            <div class="navBarNotif">
                <?php echo file_get_contents(ROOT.'/public/images/svg/notif.svg') ?> 
                <span class="navBarNotifCount">
                    <?= Session::exists('notif_count') ? Session::get('notif_count') : getNotifCount() ?>
                </span>
            </div>
        </a>
    <?php endif ?>

    <?php if(!Session::exists(USER_SESSION_NAME)): ?>
        <a href="<?=PROOT?>register">
            <div class="navBarRegister">
                <button class="btn">Get started</button>
            </div>
        </a>

        <a href="<?=PROOT?>login">
            <div class="navBarLogin">
                <button class="btn">Login</button>
            </div>
        </a>
    <?php endif ?>

</nav>