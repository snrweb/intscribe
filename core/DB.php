<?php 
    namespace Core;
    use \PDO;
    use \PDOException;

    class DB {

        private static $_instance = null;
        private $PDO, $query, $result, $error = false, $lastInsertID = null, $count = 0;

        private function __construct() {
            try {
                $this->PDO = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME, DBUSER, DBPASSWORD);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        public static function getInstance() {
            if(!isset(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function query($sql, $params = [], $class = false) {
            $this->error = false;
            if ($this->query = $this->PDO->prepare($sql)) {            
                $x = 1;

                if(is_array($params)) {
                    if(count($params)) {
                        foreach($params as $param) {
                            $this->query->bindValue($x, $param);
                            $x++;
                        }
                    }
                }
                
                if ($this->query->execute()) {
                    if($class) {
                        $this->result = $this->query->fetchALL(PDO::FETCH_CLASS, $class);
                    } else {
                        $this->result = $this->query->fetchALL(PDO::FETCH_OBJ);
                    }
                    $this->lastInsertID = $this->PDO->lastInsertID();
                } else {
                    $this->error = true;
                }
            }
            return $this;
        }

        private function read($table, $params = [], $class) {

            $conditionString = '';
            $bindValues = [];
            $orderValues = '';
            $limitValues = '';

            if (isset($params['conditions'])) {

                if(is_array($params['conditions'])) {
                    foreach($params['conditions'] as $condition) {
                        $conditionString .= ' ' . $condition . ' AND';
                    }
                    
                    $conditionString = trim($conditionString);
                    $conditionString = rtrim($conditionString, ' AND');
                } else {
                    $conditionString = $params['conditions'];
                }

                if($conditionString !== '') {
                    $conditionString = ' WHERE ' . $conditionString;
                }
                
            }

            if (array_key_exists('bind', $params)) {
                $bindValues = $params['bind'];
            }

            if (array_key_exists('order', $params)) {
                $orderValues = ' ORDER BY ' . $params['order'];
            }

            if (array_key_exists('limit', $params)) {
                $limitValues = ' LIMIT ' . $params['limit'];
            }

            $sql = "SELECT * FROM {$table}{$conditionString}{$orderValues}{$limitValues}";

            if($this->query($sql, $bindValues, $class)) {
                if(!count($this->result)) return false;
                return true;
            }

            return false;
        }

        public function find($table, $params = [], $class = false) {
            if($this->read($table, $params, $class)) {
                return $this->getResult();
            }
            return false;
        }

        public function findFirst($table, $params = [], $class = false) {
            if($this->read($table, $params, $class)) {
                return $this->getFirstResult();
            }
            return false;
        }

        public function insert($table, $fields = []) {
            $fieldStrings = '';
            $valueStrings = '';
            $values = [];

            foreach ($fields as $field => $value) {
                $fieldStrings .= '`'. $field . '`,';
                $valueStrings .= '?,';
                $values[] = $value;
            }

            $fieldStrings = rtrim($fieldStrings, ',');
            $valueStrings = rtrim($valueStrings, ',');
            
            $sql = "INSERT INTO {$table} ({$fieldStrings}) VALUES ({$valueStrings})";
            if(!$this->query($sql, $values)->error()) {
                return true;
            } else {
                return false;
            }

        }

        public function update($table, $conditions, $condition_bind = [], $fields = []) {
            $fieldStrings = '';
            $values = [];

            foreach($fields as $field => $value) {
                $fieldStrings .= ' '.$field.' = ?,';
                $values[] = $value;
            }

            $fieldStrings = trim($fieldStrings);
            $fieldStrings = rtrim($fieldStrings, ',');

            $sql = "UPDATE {$table} SET {$fieldStrings} WHERE {$conditions}";
            foreach($condition_bind as $c) {
                array_push($values, $c);
            }

            if(!$this->query($sql, $values)->error()) {
                return true;
            }
            return false;
        }

        public function delete($table, $conditions, $condition_bind = []) {
            $sql = "DELETE FROM {$table} WHERE {$conditions}";
            if(!$this->query($sql, $condition_bind)->error()) {
                return true;
            }
            return false;
        }

        public function getResult() {
            return $this->result;
        }

        public function getFirstResult() {
            return (!empty($this->result)) ? $this->result[0] : [];
        }

        public function getlastId() {
            return $this->lastInsertID;
        }

        public function error() {
            $this->error;
        }
    }

?>