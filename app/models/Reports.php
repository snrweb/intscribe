<?php
    namespace App\Models;
    use Core\Model;
    use Core\Cookie;
    use Core\Session;
    
    class Reports extends Model {
        public $report_id, $report, $report_tag, $post_id, $comment_id, $subcomment_id, $user_id, $created_at;

        public function __construct() {
            parent::__construct('reports');
        }

        public function insertReport() {
            return $this->insert([
                'report' => $this->report, 
                'report_tag' => $this->report_tag, 
                'post_id' => $this->post_id, 
                'comment_id' => $this->comment_id, 
                'subcomment_id' => $this->subcomment_id, 
                'user_id' => Session::get(USER_SESSION_NAME), 
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

    }
?>