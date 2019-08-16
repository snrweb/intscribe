<?php 
    use Core\Session;

    /*******
     * This is for debugging
     * *********/
    function dnd ($data) {
        echo '<pre>';
            var_dump($data);
        echo '</pre>';
        die();
    }

    function getNotifCount() {
        if(!Session::exists('notif_count')) {
            $n = new App\Models\Notifications();
            $notif_count = $n->getNotificationCount();
            Session::set('notif_count', $notif_count);
            return $notif_count;
        }
        return $n->getNotificationCount();
    }

    function usersInfo($view) {
        $user = new App\Models\Users();
        $view->user = $user->findFirst(["conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]]);

        $interests = new App\Models\Interests();
        $view->user_interests = $interests->fetchInterests(Session::get(USER_SESSION_NAME));

        $view->interest_count = $interests->fetchInterestCount(Session::get(USER_SESSION_NAME));

        $bookmark = new App\Models\Bookmarks();
        $view->bookmark_count = $bookmark->getBookmarkCount(Session::get(USER_SESSION_NAME));

        $posts = new App\Models\Posts();
        $view->post_count = $posts->getPostCreatedByUserCount(Session::get(USER_SESSION_NAME));

        $follow = new App\Models\Follow();
        $view->follower_count = $follow->getFollowersCount(Session::get(USER_SESSION_NAME));

        $view->following_count = $follow->getFollowingCount(Session::get(USER_SESSION_NAME));
    }

    function setTime($dbtime) {
        $time = date('Y-m-d H:i:s', time());
        $time1 = new DateTime($time);
        $time2 = new DateTime($dbtime);
        $diff = $time2->diff($time1);
        $interval;
        
        if($diff->y > 0) {
            $strTime = strtotime($dbtime);
            $interval = date('j M Y', $strTime);
        }
        elseif($diff->m > 0) {
            $strTime = strtotime($dbtime);
            $y = (date('Y', $strTime) < date('Y', strtotime($time))) ? 'Y' : '';
            $interval = date('j M '.$y, $strTime);
        }
        elseif($diff->d > 0) {
            $interval = $diff->d.($diff->d == 1 ? " day ago" : " days ago");
        }
        elseif($diff->h > 0) {
            $interval = $diff->h.($diff->h == 1 ? " hr ago" : " hrs ago");
        }
        elseif($diff->i > 0) {
            $interval = $diff->i.($diff->i == 1 ? " min ago" : " mins ago");
        }
        elseif($diff->s >= 0) {
            $interval = "Just now";
        }
        return $interval;
    }

    function setColor($id, $tag = "") {
        if(Session::exists(USER_SESSION_NAME)) {
            if($id > 0 && $tag == '') {
                return 'color: green; fill: green;';
            } elseif($id < 0 && $tag == 'tag') {
                return 'color: red; fill: red;';
            }
            return 'color: rgba(0,0,0,0.5)';
        }
        return 'color: rgba(0,0,0,0.5)';
    }

    function setFollowColor($follow_id) {
        if(Session::exists(USER_SESSION_NAME)) {
            if($follow_id > 0) {
                return 'border: 1px solid green; fill: green;';
            }
        }
    }

    function setCount($value) {
        if ($value >= 1000) {
            $n = $value/1000;
            return $n.'k';
        } elseif($value < 1000) {
            return $value;
        }
    }
    
?>