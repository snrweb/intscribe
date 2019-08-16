
<?php $this->setSiteTitle('Reset Password') ?>

    <?php $this->start('body') ?>
        
        <form class="form" method="post" 
              action="<?=PROOT?>/password/reset/<?= $this->type ?>/<?= $this->code ?>/<?= $this->email ?>">
            <?= $this->errorMsg() ?>
            <?= $this->successMsg() ?>
            <p class="formTitle">Enter New Password</p>
            <div style="margin-bottom: 10px;">
                <input type="password" placeholder="Enter new password" name="password_one">
            </div>
            <div style="margin-bottom: 10px;">
                <input type="password" placeholder="Confirm new password" name="password_two">
            </div>
            <button class="submitbtn btn" type="submit">Submit</button>
        </form>

        <style>
            .form {width: 50%; margin-top: 100px;}
            .Footer{display: none;}
        </style>

    <?php $this->end() ?>