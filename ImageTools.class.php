<?php

class ImageTools {
    
    public static function toGif($source_file) {
        $extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
        
        if ($extension == "gif") {
            $gif_file = $source_file;
        } else {
            switch ($extension) {
              case 'png':
                $src = ImageCreateFromPng($source_file); // original image
              break;
              default:
                $src = ImageCreateFromJpeg($source_file); // original image
              break;
            }
          
            unlink($source_file);
            
            $gif_file = $source_file . '.gif';
            imagegif($src, $gif_file);
        }

        return $gif_file;
    }
    
}