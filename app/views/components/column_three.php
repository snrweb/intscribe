<?php 
    use Core\Session; 
?>

<div class="columnThree">
    <ul class="c3-interest-lists">
        <li>Interests</li>
            <?php if(Session::exists(USER_SESSION_NAME)) { ?>
                <?php foreach($this->interests as $int){ $count = 0; ?>
                    <?php foreach($this->user_interests as $uc): ?>
                        <?php if($uc->interest == $int) { ?>
                            <li>
                                <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $int) ?>">
                                    <p><?= $int ?></p>
                                </a>
                                <button style="background: green;">
                                    <a href="<?=PROOT?>interest/addInterest/<?= str_replace(" ", "-", $int) ?>">
                                        <?= file_get_contents(ROOT.'/public/images/svg/mark.svg') ?> 
                                    </a>
                                </button>  
                            </li>
                        <?php $count = 1; } ?>
                    <?php endforeach ?>
                    
                    <?php if($count == 0): ?>
                        <li>
                            <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $int) ?>">
                                <p><?= $int ?></p>
                            </a>
                            <button>
                                <a href="<?=PROOT?>interest/addInterest/<?= str_replace(" ", "-", $int) ?>">
                                    <?= file_get_contents(ROOT.'/public/images/svg/mark.svg') ?> 
                                </a>
                            </button>  
                        </li>
                    <?php endif ?>
                <?php } ?>
            <?php } else { ?>
                <?php foreach($this->interests as $int): ?>
                    <li>
                        <a href="<?=PROOT?>interest/<?= str_replace(" ", "-", $int) ?>">
                            <p><?= $int ?></p>
                        </a>
                    </li>
                <?php endforeach ?>
            <?php } ?>
    </ul>

</div>