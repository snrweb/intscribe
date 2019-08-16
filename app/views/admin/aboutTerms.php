<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Admin Area') ?>
    <?php $this->start('head') ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?=PROOT?>public/css/admin.css" />
    <?php $this->end() ?>

    <?php $this->start('body') ?>

        <?php if($this->urlParams == 'about') { ?>
            <form class="form" method="post" action="<?=PROOT?>admin223/editAbout">
                <p class="formTitle">About Orjah</p>
                <?= CSRF::input($this->csrf_token_error); ?>
                <div class="input-wrapper">
                    <textarea type="url" class="textarea" placeholder="About store"
                            name="about" rows="10"><?=$this->about?></textarea>
                </div>
                <div class="input-wrapper">
                    <input type="submit" value="Submit" class="btn s-submitbtn">
                </div>
            </form>
        <?php } ?>

            <form class="form" method="post" action="<?=PROOT?>admin223/editTerms">
                <p class="formTitle">Orjah Terms and Condition</p>
                <?= CSRF::input($this->csrf_token_error); ?>
                <div class="input-wrapper">
                    <textarea type="url" class="textarea" placeholder="About store"
                            name="terms" rows="10"><?=$this->terms?></textarea>
                </div>
                <div class="input-wrapper">
                    <input type="submit" value="Submit" class="btn s-submitbtn">
                </div>
            </form>

    
    <?php $this->end() ?>
