
<?php 
    use Core\CSRF;
    $this->setSiteTitle('Intscribe - Login') 
?>

<?php $this->start('body') ?>

    <form class="LoginFormWrapper shadow" action="<?=PROOT?>login" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>
        <div class="title">
            <span>Enter Your Space</span>
        </div>
        
        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->email_error?></small>
            <input type="email" placeholder="Email" name="email" value="<?=$this->email?>"/>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->password_error?></small>
            <input type="password" placeholder="Password" name="password"/>
        </div>

        <div class="input-wrapper">
            <div class="LoginFormCheckboxWrapper pull-left">
                <label>Remember me</label>
                <input type="checkbox" value="true" name="remember_me"/>
            </div>
            <div class="LoginFormForgotPwdWrapper pull-right">
                <a href="<?=PROOT?>password/forgot">Forgot Password?</a>
            </div>
            <div class="Clear-Float"></div>
        </div>

        <div class="input-wrapper">
            <button type="submit" class="LoginFormSubmitButton btn">Login</button>
        </div>

        <div class="LoginFormRegisterWrapper">
            <a href="<?=PROOT?>register">Click here to register</a>
        </div>
    </form>

<?php $this->end() ?>