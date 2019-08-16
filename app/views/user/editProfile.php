<?php 
    use Core\CSRF;
    $this->setSiteTitle('Edit Profile') ?>

<?php $this->start('body') ?>

    <form class="RegisterFormWrapper shadow" action="<?=PROOT?>user/editProfile" method="post" enctype="multipart/form-data">
        <?= CSRF::input($this->csrf_token_error); ?>

        <div class="title">
            <span>Edit Profile</span>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->username_error?></small>
            <input type="text" placeholder="Firstname and Lastname" name="username" 
                    value="<?=$this->user->username?>" required maxlength="30" />
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->profile_image_error?></small>
            <div style="text-align: center; margin-bottom: 15px;">
                <?php if(!empty($this->user->profile_image)): ?>
                    <img src="<?=PROOT?>/public/images/profile_pic/<?= $this->user->profile_image ?>" 
                        style="max-height: 90px; max-width: 90px;">
                <?php endif ?>

                <?php if(empty($this->user->profile_image)): ?>
                    <img src="<?=PROOT?>/public/images/profile_pic/avatar.jpg" 
                        style="max-height: 90px; max-width: 90px;">
                <?php endif ?>
            </div>
            <input type="file" name="profile_image" />
        </div>

        <div class="input-wrapper">
            <button type="submit" class="RegisterFormButton btn">Update Profile</button>
        </div>

    </form>

<?php $this->end() ?>