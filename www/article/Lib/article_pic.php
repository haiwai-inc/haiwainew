<?php
class article_pic{
    public function save_file($file){
        if(substr($file, 0, 4) === "data"){
            $file = file_get_contents($file);
        }
        else {
            $file = base64_decode($file);
        }
        $random_num = $this->random_string(10);
        $dir = DOCUROOT."/upload/article/pic/blog/".substr($random_num,-8,-6)."/".substr($random_num,-6,-4)."/".substr($random_num,-4,-2)."/".substr($random_num,-2);
        if( !is_dir($dir)) files::mkdirs($dir);
        
        while(file_exists($dir."/$random_num.jpg")){
            $random_num = $this->random_string(10);
            $dir = DOCUROOT."/upload/article/pic/blog/".substr($random_num,-8,-6)."/".substr($random_num,-6,-4)."/".substr($random_num,-4,-2)."/".substr($random_num,-2);
            if( !is_dir($dir)) files::mkdirs($dir);
        }

        file_put_contents($dir."/$random_num.jpg", $file);
        return $random_num;
    }

    public function getFileURL($random_num){
        return "/upload/article/pic/blog/".substr($random_num,-8,-6)."/".substr($random_num,-6,-4)."/".substr($random_num,-4,-2)."/".substr($random_num,-2)."/$random_num.jpg";
    }

    public function save_picture($file){
        $file_number = $this->save_file($file);
        return $this->getFileURL($file_number);
    }

    public function save_media($file){
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
        debug::d($mime_type);
    }


    


    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }
    
}