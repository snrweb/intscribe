<?php
    namespace App\Models;
    use Core\Model;

    class Admin extends Model {
        public $terms, $about;

        public function __construct() {
            parent::__construct('admin');
            
        }
        
        public function updateTerms() {
            return $this->update('id', 1, [
                'terms' => $this->terms
                ]
            );
        }

        public function getTerms() {
            return $this->findById('id', 1)->terms;
        }


        public function updateAbout() {
            return $this->update('id', 1, [
                'about' => $this->about
                ]
            );
        }

        public function getAbout() {
            return $this->findById('id', 1)->about;
        }
        
    }

?>