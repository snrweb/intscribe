<?php
    namespace Core;

    class Resize {

        public static function changeSize($orgfile, $newimage, $newwidth, $newheight, $ext, $quality) {

            list($orgwidth, $orgheight) = getimagesize($orgfile);

            $ratio = $orgwidth/$orgheight;

            if(($newwidth/$newheight) > $ratio) {
                $newwidth = $newheight * $ratio;
            } else {
                $newheight = $newwidth/$ratio;
            }

            $img = "";

            if ($ext == 'png' || $ext == 'PNG') {
                $img = imagecreatefrompng($orgfile);
            } else {
                $img = imagecreatefromjpeg($orgfile);
            }
            
            $newsize = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($newsize, $img, 0, 0, 0, 0, $newwidth, $newheight, $orgwidth, $orgheight);
            imagejpeg($newsize, $newimage, $quality);
            imagedestroy($img);

        }

    }
?>