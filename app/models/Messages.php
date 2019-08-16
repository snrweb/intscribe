<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Messages extends Model {
        public $message_id, $sender_id, $sender_name, $viewed, $receiver_id, $sender_type, $message, $unique_id, $created_at;
        
        public function __construct() {
            parent::__construct('messages');
        }

        public function sendMessage() {
            $buyer_id; $store_id;
            if($this->sender_type == "buyer") {
                $buyer_id = Session::get(BUYER_SESSION_NAME);
                $store_id = $this->receiver_id;
            } else {
                $buyer_id = $this->receiver_id;
                $store_id = Session::get(STORE_SESSION_NAME);
            }

            return $this->insert([
                'sender_id' => $this->sender_id,
                'receiver_id' => $this->receiver_id,
                'sender_type' => $this->sender_type,
                'message' => $this->message,
                'unique_id' => $this->unique_id,
                'store_id' => $store_id,
                'buyer_id' => $buyer_id,
                'viewed' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        public function unReadMessages() {
            $user_id; $sender_type;
            if(Session::exists(BUYER_SESSION_NAME)) {
                $user_id = Session::get(BUYER_SESSION_NAME);
                $sender_type = 'store';
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $user_id = Session::get(STORE_SESSION_NAME);
                $sender_type = 'buyer';
            }
            $result = $this->query("SELECT count('receiver_id') AS msg 
                                    FROM messages 
                                    WHERE receiver_id = ? AND sender_type = ? AND viewed = ?", [$user_id, "$sender_type", 0]);
            return $result->getResult()[0]->msg;
        }

        public function fetchMessages() {
            $sql; $user_id;
            if(Session::exists(BUYER_SESSION_NAME)) {
                $user_id = Session::get(BUYER_SESSION_NAME);
                $sql = "SELECT messages.message_id, messages.sender_id, 
                        messages.receiver_id, messages.sender_type, messages.message, 
                        messages.unique_id, messages.viewed, messages.created_at, stores.store_name 
                        FROM messages
                        LEFT JOIN stores
                        ON messages.store_id = stores.store_id
                        WHERE messages.message_id IN (
                            SELECT MAX(message_id) FROM messages
                            WHERE (sender_id = ? AND sender_type = 'buyer') 
                            OR (receiver_id = ? AND sender_type = 'store')
                            GROUP BY unique_id ORDER BY message_id DESC)";
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $user_id = Session::get(STORE_SESSION_NAME);
                $sql = "SELECT messages.message_id, messages.sender_id, 
                        messages.receiver_id, messages.sender_type, messages.message, 
                        messages.unique_id, messages.viewed, messages.created_at, buyers.buyer_name 
                        FROM messages
                        LEFT JOIN buyers
                        ON messages.buyer_id = buyers.buyer_id
                        WHERE messages.message_id IN (
                            SELECT MAX(message_id) FROM messages
                            WHERE (sender_id = ? AND sender_type = 'store') 
                            OR (receiver_id = ? AND sender_type = 'buyer')
                            GROUP BY unique_id ORDER BY message_id DESC)";
            }

            return $this->query($sql, [$user_id, $user_id])->getResult();
        }

        //messages that appears in chat
        public function fetchUniqueMessages($params) {
            $sender_id; $sender_type;
            $idAry = explode('-', $params);
            if(Session::exists(BUYER_SESSION_NAME)) { 
                if($idAry[1] != Session::get(BUYER_SESSION_NAME)) return false;
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                if($idAry[0] != Session::get(STORE_SESSION_NAME)) return false;
            }
            $unique_id = $params;
            if($this->query("UPDATE messages SET viewed = 1 WHERE unique_id = ?", ["$unique_id"])) {
                $sql = "SELECT * FROM messages WHERE unique_id = ?";
                return $this->query($sql, ["$unique_id"])->getResult();
            }
        }

    }
    
?>