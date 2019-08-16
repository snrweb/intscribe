<?php 
    use Core\CSRF;
    $this->setSiteTitle('Intscribe - Registration') ?>

<?php $this->start('body') ?>

    <form class="RegisterFormWrapper shadow" action="<?=PROOT?>register" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>

        <div class="title">
            <small class="errorDisplay"><?=$this->registrationError?></small>
            <span>One step away to share your thoughts</span>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->username_error?></small>
            <input type="text" placeholder="Firstname and Lastname" name="username" 
                    value="<?=$this->username?>" required maxlength="30" />
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->email_error?></small>
            <input type="email" placeholder="Email" name="email" value="<?=$this->email?>" required/>
        </div>

        <div class="input-wrapper">
            <small class="errorDisplay"><?=$this->password_error?></small>
            <input type="password" placeholder="Password" name="password" required/>
        </div>

        <div class="input-wrapper">
            <input type="password" placeholder="Confirm password" name="confirm_password" required/>
        </div>

        <div class="input-wrapper">
            <button type="submit" class="RegisterFormButton btn">Register</button>
        </div>

    </form>

<?php $this->end() ?>