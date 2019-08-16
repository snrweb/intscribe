<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Validation;
    use Core\CSRF;
    use Core\Resize;

    class ImageUploadController extends Controller {
        private $image_name, $image_dir;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();
        }

        private function checkImageName() {
            if(isset($_FILES['post_image'])) {
                $this->image_name = 'post_image';
                $this->image_dir = 'post_pic';
                return;
            }

            if(isset($_FILES['comment_image'])) {
                $this->image_name = 'comment_image';
                $this->image_dir = 'comment_pic';
                return;
            }

            if(isset($_FILES['scomment_image'])) {
                $this->image_name = 'scomment_image';
                $this->image_dir = 'scomment_pic';
                return;
            }
        }

        public function indexAction() {
            if($_FILES) {
                $this->checkImageName();
                $validate = new Validation();
                $validate->check('$_POST', [$this->image_name => 
                                                ['display' => 'Image', 'isImage' =>  true, 'size' => 4.5]
                                            ], false);

                if($validate->passed()) {
                    $resize = new Resize();
                    
                    $image = $_FILES[$this->image_name]["name"];
                    $ext = pathinfo($_FILES[$this->image_name]["name"], PATHINFO_EXTENSION);
                    $image = time().random_int(100, 1000000000).".".$ext;

                    $resize::changeSize(//temporary image image location
                        $_FILES[$this->image_name]["tmp_name"], 
                        //location to upload resized image
                        ROOT . DS . 'public' . DS . 'images' . DS . $this->image_dir . DS . $image,
                        //Maximum width of the new resized image
                        400, 
                        //Maximum height of the new resized image
                        300,
                        //File extension of the new resized image
                        $ext,
                        //Quality of the image
                        85 );
                    echo json_encode(['status' => true, 'imageName' => $image]);
                    return;
                }
                echo json_encode(['status' => false]);
            }
        }

    }

?>