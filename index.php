<?php
include "ImageTools.class.php";
include "GIFEncoder.class.php";

if (!$_POST) {
?>

<!doctype>
<html>
    <head>
        <title>
            VIDGIF
        </title>
    </head>
    <body>
    
        <form method='POST' name='uploadform' enctype='multipart/form-data' action=''>
            <input type='file' name='file'><br>
            <input type="hidden" name="fd" value="1">
            <input type="hidden" name="fps" value="15">
            <input type='submit' name='cmdSubmit' value='Upload'>
        </form>
    
    </body>
</html>

<?php
} else {
    
    echo "<p><a href='?'>&laquo; Back</a></p>";
    
    $FRAME_DELAY = $_POST['fd'];
    $FPS= $_POST['fps'];
    
    $allowedExts = array("flv", "mp4", "m3u8", "ts", "3gp", "mov", "avi", "wmv");
    $extension = end(explode(".", $_FILES["file"]["name"]));
    if (
        (
            ($_FILES["file"]["type"] == "video/x-flv")
            || ($_FILES["file"]["type"] == "video/mp4")
            || ($_FILES["file"]["type"] == "application/x-mpegURL")
            || ($_FILES["file"]["type"] == "video/MP2T")
            || ($_FILES["file"]["type"] == "video/3gpp")
            || ($_FILES["file"]["type"] == "video/quicktime")
            || ($_FILES["file"]["type"] == "video/x-msvideo")
            || ($_FILES["file"]["type"] == "video/avi")
            || ($_FILES["file"]["type"] == "video/x-ms-wmv")
        )
    && in_array($extension, $allowedExts)
    ) {
        
        $err = false;
        
        switch( $_FILES['file']['error'] ) {
            case UPLOAD_ERR_OK:
                $message = false;
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message .= ' - file too large (limit of '.ini_get('post_max_size').' bytes).';
                $err = true;
                break;
            case UPLOAD_ERR_PARTIAL:
                $message .= ' - file upload was not completed.';
                $err = true;
                break;
            case UPLOAD_ERR_NO_FILE:
                $message .= ' - zero-length file uploaded.';
                $err = true;
                break;
            default:
                $message .= ' - internal error #'.$_FILES['file']['error'];
                $err = true;
                break;
        }
        
        if ($err) {
            echo "<pre>";
            var_dump($_FILES["file"]);
            echo "</pre>";
            
            echo "Message: " . $message . "<br />";
        } else {

            $file = md5(time()).".".$extension;
            $dir = getcwd();
            $session_id = sha1($file);
            
            $session_path = "$dir/upload/$session_id";
            
            $gif_path = "$session_path/$session_id.gif";
            $stored_name = "$session_path/$file";
            
            mkdir($session_path);
            move_uploaded_file($_FILES["file"]["tmp_name"], $stored_name);
                        
            $comm1 = "/usr/bin/convert $stored_name $gif_path";
            $comm2 = "/usr/bin/convert $gif_path  -layers OptimizeTransparency +map opt_$gif_path";
                        
            exec($comm1, $ret1);
            exec($comm2, $ret2);

            //unlink($stored_name);
            
            echo $comm1."<br>";
            echo $comm2."<br>";
            
            echo "<hr>";
            
            foreach ($ret1 as $val){
                echo "<br/>".$val;
            }
            
            echo "<br><br>";
            
            foreach ($ret2 as $val){
               echo "<br/>".$val;
            }
            
            echo "<hr>";

            echo "<p><img src='/g/$session_id.gif'></p>";
            echo "<br><br>";
        }
    } else {
        var_dump($_FILES);
        echo "Invalid file";
    }
  
}