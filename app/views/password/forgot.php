<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Forgot Password') ?>

    <?php $this->start('body') ?>
       
        <form class="editor" method="post" action="<?=PROOT?>password/forgot">
            <?= $this->errorMsg() ?>
            <?= $this->successMsg() ?>
            <p style="font-size: 1.1em;">Enter Email Address For Reset Link</p>
            <div class="input-wrapper">
                <input type="email" placeholder="Enter your email address" name="email" style="width: 100%;" required>
            </div>
            <button class="submitbtn btn" type="submit">Submit</button>
        </form>

        <style>
            .editor {margin-top: 100px; padding-bottom: 30px;}
        </style>

    <?php $this->end() ?>