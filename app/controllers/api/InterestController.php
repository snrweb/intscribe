<?php 
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Interests;

    class InterestController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Interests');

            $this->APIheaders();
        }

        public function fetchAllInterestsAction() {
            $interests = Interests::getInterests();
            $u_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $user_interests = [];

            foreach($u_interests as $u) {
                array_push($user_interests, $u->interest);
            }

            echo json_encode([
                'interests' => $interests, 
                'userInterests' => $user_interests
            ]);
        }

        public function addInterestAction(string $interest_name) {
            if(Session::exists(USER_SESSION_NAME)) {
                $this->InterestsModel->interest = str_replace("-", " ", $interest_name);
                if($this->InterestsModel->insertInterest()) {
                    $user_interests = $this->InterestsModel->fetchInterestCount(Session::get(USER_SESSION_NAME));
                    echo json_encode(['status' => true, 'interestCount' => $user_interests]);
                }
            }
        }
    }
?>