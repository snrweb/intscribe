
<?php 
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Message') ?>
<?php $this->start('head') ?>
<?php $this->end() ?>

<?php $this->start('body') ?>

<form method="post" class="contactForm" action="<?=PROOT?>/message/contactAdmin">
    <?= CSRF::input($this->csrf_token_error); ?>
    <small class="errorDisplay"><?=$this->message_error?></small>
    <small><?= $this->successMsg() ?></small>
    <p class="contactPageTitle">Send your enquiry/message</p>
    <input type="text" name="sender_name" placeholder="Full Name">
    <input type="email" name="sender_email" placeholder="Email Address">
    <input type="text" name="subject" placeholder="Subject">
    <textarea placeholder="Type your message here..." rows="7" name="message" 
                maxlength="1000" required></textarea>
    <button type="submit" class="btn">Send Message</button>
</form>
    
<?php $this->end() ?>

