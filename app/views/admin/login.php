<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Admin Login Form') ?>

    <?php $this->start('body') ?>
        <section class="">
            <form class="LoginFormWrapper" action="<?=PROOT?>/login/adminLogin" method="post">
                <div class="FormTitle">
                    <span>Admin Login Form</span>
                </div>
                <?= CSRF::input($this->csrf_token_error); ?>
                <div class="LoginFormInputWrapper">
                    <input type="text" placeholder="" name="admin_username" />
                </div>

                <div class="LoginFormInputWrapper">
                    <input type="password" placeholder="Password" name="admin_password"/>
                </div>

                <div class="LoginFormButtonWrapper">
                    <button type="submit" class="LoginFormSubmitButton">Login</button>
                </div>
            </form>
        </section>

    <?php $this->end() ?>