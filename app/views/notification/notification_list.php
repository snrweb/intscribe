
<ul class="notification-list">
    <?php 
        $i = 0;
        while($i < count($this->read)) : ?> 

            <?php if($i < count($this->read) && $this->read[$i]->type == 'Follow') {
                $fcount = 0;
                $f = $i;
                while(true) {
                    if($f < count($this->read) && $this->read[$f]->type == 'Follow') {
                        $fcount++;
                        $f++;
                        $i++;
                    } else {
                        if($fcount > 1) {
                            if($fcount-1 > 1) { ?>
                            
                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$f-1]->username) ?>-<?=
                                            $this->read[$f-1]->user_id ?>" >
                                        <span><?= $this->read[$f-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $fcount-1 ?></span> others followed you
                                    <small class="notification-time"><?= setTime($this->read[$f-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$f-1]->username) ?>-<?=
                                            $this->read[$f-1]->user_id ?>" >
                                        <span><?= $this->read[$f-1]->username ?> </span>
                                    </a> and one other person followed you
                                    <small class="notification-time"><?= setTime($this->read[$f-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>
                            
                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->read[$f-1]->username) ?>-<?=
                                        $this->read[$f-1]->user_id ?>" >
                                    <span><?= $this->read[$f-1]->username ?> </span>
                                </a> followed you
                                <small class="notification-time"><?= setTime($this->read[$f-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>
            
            <?php if($i < count($this->read) && $this->read[$i]->type == 'Bookmark') {
                $bcount = 0;
                $b = $i;
                while(true) {
                    if($b < count($this->read) && $this->read[$b]->type == 'Bookmark') {
                        $bcount++;
                        $b++;
                        $i++;
                    } else {
                        if($bcount > 1) {
                            if($bcount-1 > 1) { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$b-1]->username) ?>-<?=
                                            $this->read[$b-1]->user_id ?>" >
                                        <span><?= $this->read[$b-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $bcount-1 ?></span> others bookmarked your post 
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->read[$b-1]->post_title) ?>-<?= 
                                        $this->read[$b-1]->post_id ?>">
                                        <span><?= $this->read[$b-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->read[$b-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$b-1]->username) ?>-<?=
                                            $this->read[$b-1]->user_id ?>" >
                                        <span><?= $this->read[$b-1]->username ?> </span>
                                    </a> and one other person bookmarked your post
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->read[$b-1]->post_title) ?>-<?= 
                                        $this->read[$b-1]->post_id ?>">
                                        <span><?= $this->read[$b-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->read[$b-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>

                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->read[$b-1]->username) ?>-<?=
                                        $this->read[$b-1]->user_id ?>" >
                                    <span><?= $this->read[$b-1]->username ?> </span>
                                </a> bookmarked your post
                                <a href="<?=PROOT?>post/<?= 
                                    str_replace(["?", " "], ["", "-"], $this->read[$b-1]->post_title) ?>-<?= 
                                    $this->read[$b-1]->post_id ?>">
                                    <span><?= $this->read[$b-1]->post_title ?></span>
                                </a>
                                <small class="notification-time"><?= setTime($this->read[$b-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>
            
            <?php if($i < count($this->read) && $this->read[$i]->type == 'Comment') {
                $kcount = 0;
                $k = $i;
                while(true) {
                    if($k < count($this->read) && $this->read[$k]->type == 'Comment') {
                        $kcount++;
                        $k++;
                        $i++;
                    } else {
                        if($kcount > 1) {
                            if($kcount-1 > 1) { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$k-1]->username) ?>-<?=
                                            $this->read[$k-1]->user_id ?>" >
                                        <span><?= $this->read[$k-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $kcount-1 ?></span> others commented your post
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->read[$k-1]->post_title) ?>-<?= 
                                        $this->read[$k-1]->post_id ?>">
                                        <span><?= $this->read[$k-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->read[$k-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$k-1]->username) ?>-<?=
                                            $this->read[$k-1]->user_id ?>" >
                                        <span><?= $this->read[$k-1]->username ?> </span>
                                    </a> and one other person commented on your post
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->read[$k-1]->post_title) ?>-<?= 
                                        $this->read[$k-1]->post_id ?>">
                                        <span><?= $this->read[$k-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->read[$k-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>

                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->read[$k-1]->username) ?>-<?=
                                        $this->read[$k-1]->user_id ?>" >
                                    <span><?= $this->read[$k-1]->username ?> </span>
                                </a> commented on your post
                                <a href="<?=PROOT?>post/<?= 
                                    str_replace(["?", " "], ["", "-"], $this->read[$k-1]->post_title) ?>-<?= 
                                    $this->read[$k-1]->post_id ?>">
                                    <span><?= $this->read[$k-1]->post_title ?></span>
                                </a>
                                <small class="notification-time"><?= setTime($this->read[$k-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>

            <?php if($i < count($this->read) && $this->read[$i]->type == 'SubComment') {
                $skcount = 0;
                $sk = $i;
                while(true) {
                    if($sk < count($this->read) && $this->read[$sk]->type == 'SubComment') {
                        $skcount++;
                        $sk++;
                        $i++;
                    } else {
                        if($skcount > 1) {
                            if($skcount-1 > 1) { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$sk-1]->username) ?>-<?=
                                            $this->read[$sk-1]->user_id ?>" >
                                        <span><?= $this->read[$sk-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $skcount-1 ?></span> others commented your comment on 
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->read[$sk-1]->post_title) ?>-<?= 
                                        $this->read[$sk-1]->post_id ?>">
                                        <span><?= $this->read[$sk-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->read[$sk-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->read[$sk-1]->username) ?>-<?=
                                            $this->read[$sk-1]->user_id ?>" >
                                        <span><?= $this->read[$sk-1]->username ?> </span>
                                    </a> and one other person commented on your comment on 
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->read[$sk-1]->post_title) ?>-<?= 
                                        $this->read[$sk-1]->post_id ?>">
                                        <span><?= $this->read[$sk-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->read[$sk-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>

                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->read[$sk-1]->username) ?>-<?=
                                        $this->read[$sk-1]->user_id ?>" >
                                    <span><?= $this->read[$sk-1]->username ?> </span>
                                </a> commented on your comment on 
                                <a href="<?=PROOT?>post/<?= 
                                    str_replace(["?", " "], ["", "-"], $this->read[$sk-1]->post_title) ?>-<?= 
                                    $this->read[$sk-1]->post_id ?>">
                                    <span><?= $this->read[$sk-1]->post_title ?></span>
                                </a>
                                <small class="notification-time"><?= setTime($this->read[$sk-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>

        <?php endwhile ?>
</ul>

<ul class="notification-list-u">
    <?php 
        $u = 0;
        while($u < count($this->unread)) : ?>

            <?php if($u < count($this->unread) && $this->unread[$u]->type == 'Follow') {
                $fcount = 0;
                $f = $u;
                while(true) {
                    if($f < count($this->unread) && $this->unread[$f]->type == 'Follow') {
                        $fcount++;
                        $f++;
                        $u++;
                    } else {
                        if($fcount > 1) {
                            if($fcount-1 > 1) { ?>
                            
                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$f-1]->username) ?>-<?=
                                            $this->unread[$f-1]->user_id ?>" >
                                        <span><?= $this->unread[$f-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $fcount-1 ?></span> others followed you
                                    <small class="notification-time"><?= setTime($this->unread[$f-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$f-1]->username) ?>-<?=
                                            $this->unread[$f-1]->user_id ?>" >
                                        <span><?= $this->unread[$f-1]->username ?> </span>
                                    </a> and one other person followed you
                                    <small class="notification-time"><?= setTime($this->unread[$f-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>
                            
                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->unread[$f-1]->username) ?>-<?=
                                        $this->unread[$f-1]->user_id ?>" >
                                    <span><?= $this->unread[$f-1]->username ?> </span>
                                </a> followed you
                                <small class="notification-time"><?= setTime($this->unread[$f-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>
            
            <?php if($u < count($this->unread) && $this->unread[$u]->type == 'Bookmark') {
                $bcount = 0;
                $b = $u;
                while(true) {
                    if($b < count($this->unread) && $this->unread[$b]->type == 'Bookmark') {
                        $bcount++;
                        $b++;
                        $u++;
                    } else {
                        if($bcount > 1) {
                            if($bcount-1 > 1) { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$b-1]->username) ?>-<?=
                                            $this->unread[$b-1]->user_id ?>" >
                                        <span><?= $this->unread[$b-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $bcount-1 ?></span> others bookmarked your post 
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->unread[$b-1]->post_title) ?>-<?= 
                                        $this->unread[$b-1]->post_id ?>">
                                        <span><?= $this->unread[$b-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->unread[$b-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$b-1]->username) ?>-<?=
                                            $this->unread[$b-1]->user_id ?>" >
                                        <span><?= $this->unread[$b-1]->username ?> </span>
                                    </a> and one other person bookmarked your post
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->unread[$b-1]->post_title) ?>-<?= 
                                        $this->unread[$b-1]->post_id ?>">
                                        <span><?= $this->unread[$b-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->unread[$b-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>

                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->unread[$b-1]->username) ?>-<?=
                                        $this->unread[$b-1]->user_id ?>" >
                                    <span><?= $this->unread[$b-1]->username ?> </span>
                                </a> bookmarked your post
                                <a href="<?=PROOT?>post/<?= 
                                    str_replace(["?", " "], ["", "-"], $this->unread[$b-1]->post_title) ?>-<?= 
                                    $this->unread[$b-1]->post_id ?>">
                                    <span><?= $this->unread[$b-1]->post_title ?></span>
                                </a>
                                <small class="notification-time"><?= setTime($this->unread[$b-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>
            
            <?php if($u < count($this->unread) && $this->unread[$u]->type == 'Comment') {
                $kcount = 0;
                $k = $u;
                while(true) {
                    if($k < count($this->unread) && $this->unread[$k]->type == 'Comment') {
                        $kcount++;
                        $k++;
                        $u++;
                    } else {
                        if($kcount > 1) {
                            if($kcount-1 > 1) { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$k-1]->username) ?>-<?=
                                            $this->unread[$k-1]->user_id ?>" >
                                        <span><?= $this->unread[$k-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $kcount-1 ?></span> others commented your post
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->unread[$k-1]->post_title) ?>-<?= 
                                        $this->unread[$k-1]->post_id ?>">
                                        <span><?= $this->unread[$k-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->unread[$k-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$k-1]->username) ?>-<?=
                                            $this->unread[$k-1]->user_id ?>" >
                                        <span><?= $this->unread[$k-1]->username ?> </span>
                                    </a> and one other person commented on your post
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->unread[$k-1]->post_title) ?>-<?= 
                                        $this->unread[$k-1]->post_id ?>">
                                        <span><?= $this->unread[$k-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->unread[$k-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>

                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->unread[$k-1]->username) ?>-<?=
                                        $this->unread[$k-1]->user_id ?>" >
                                    <span><?= $this->unread[$k-1]->username ?> </span>
                                </a> commented on your post
                                <a href="<?=PROOT?>post/<?= 
                                    str_replace(["?", " "], ["", "-"], $this->unread[$k-1]->post_title) ?>-<?= 
                                    $this->unread[$k-1]->post_id ?>">
                                    <span><?= $this->unread[$k-1]->post_title ?></span>
                                </a>
                                <small class="notification-time"><?= setTime($this->unread[$k-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>

            <?php if($u < count($this->unread) && $this->unread[$u]->type == 'SubComment') {
                $skcount = 0;
                $sk = $u;
                while(true) {
                    if($sk < count($this->unread) && $this->unread[$sk]->type == 'SubComment') {
                        $skcount++;
                        $sk++;
                        $u++;
                    } else {
                        if($skcount > 1) {
                            if($skcount-1 > 1) { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$sk-1]->username) ?>-<?=
                                            $this->unread[$sk-1]->user_id ?>" >
                                        <span><?= $this->unread[$sk-1]->username ?> </span>
                                    </a> and 
                                    <span><?= $skcount-1 ?></span> others commented your comment on 
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->unread[$sk-1]->post_title) ?>-<?= 
                                        $this->unread[$sk-1]->post_id ?>">
                                        <span><?= $this->unread[$sk-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->unread[$sk-1]->created_at) ?></small>
                                </li>

                            <?php } else { ?>

                                <li>
                                    <a href="<?=PROOT?>user/<?= 
                                            str_replace(" ", "-", $this->unread[$sk-1]->username) ?>-<?=
                                            $this->unread[$sk-1]->user_id ?>" >
                                        <span><?= $this->unread[$sk-1]->username ?> </span>
                                    </a> and one other person commented on your comment on 
                                    <a href="<?=PROOT?>post/<?= 
                                        str_replace(["?", " "], ["", "-"], $this->unread[$sk-1]->post_title) ?>-<?= 
                                        $this->unread[$sk-1]->post_id ?>">
                                        <span><?= $this->unread[$sk-1]->post_title ?></span>
                                    </a>
                                    <small class="notification-time"><?= setTime($this->unread[$sk-1]->created_at) ?></small>
                                </li>

                            <?php }
                        } else { ?>

                            <li>
                                <a href="<?=PROOT?>user/<?= 
                                        str_replace(" ", "-", $this->unread[$sk-1]->username) ?>-<?=
                                        $this->unread[$sk-1]->user_id ?>" >
                                    <span><?= $this->unread[$sk-1]->username ?> </span>
                                </a> commented on your comment on 
                                <a href="<?=PROOT?>post/<?= 
                                    str_replace(["?", " "], ["", "-"], $this->unread[$sk-1]->post_title) ?>-<?= 
                                    $this->unread[$sk-1]->post_id ?>">
                                    <span><?= $this->unread[$sk-1]->post_title ?></span>
                                </a>
                                <small class="notification-time"><?= setTime($this->unread[$sk-1]->created_at) ?></small>
                            </li>

            <?php } break; } } } ?>

        <?php endwhile ?>
</ul>
