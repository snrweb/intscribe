<?php 
    namespace Core;
    use Core\DB;
    use Core\Sanitise;
    
    class Model {
        protected $db, $table, $modelName, $softDelete = false;

        public function __construct($table) {
            $this->db = DB::getInstance();
            $this->table = $table;
            $this->modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table)));
        }

        public function find($params= []) {
            $resultQuery = $this->db->find($this->table, $params, get_class($this));
            if(!$resultQuery) return [];
            return $resultQuery;
        }

        public function findFirst($params= []) {
            $resultQuery = $this->db->findFirst($this->table, $params, get_class($this));
            if(!$resultQuery) return [];
            return $resultQuery;
        }

        public function insert($fields) {
            if(empty($fields)) return false;
            return $this->db->insert($this->table, $fields);
        }

        public function update($conditions, $condition_bind, $fields) {
            if(empty($fields) || empty($condition_bind) ) return false;
            return $this->db->update($this->table, $conditions, $condition_bind, $fields);
        }

        public function delete($conditions, $condition_bind = []) {
            if(empty($condition_bind)) return false;
            return $this->db->delete($this->table, $conditions, $condition_bind);
        }

        public function query($sql, $bind = []) {
            return $this->db->query($sql, $bind);
        }

        public function findById($idName, $id) {
            return $this->findFirst(['conditions' => "{$idName} = ?", 'bind' => [$id]]);
        }

        public function assign($params) {
            if(!empty($params)) {
                foreach($params as $key => $value) {
                    if(property_exists($this, $key)) {
                        $this->$key = Sanitise::input($value);
                    }
                }
                return true;
            }
            return false;
        }

    }

?>