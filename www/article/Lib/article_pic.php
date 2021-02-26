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
        $first = substr($random_num, 2, 2 );
        $second = substr($random_num, 0, 2 );
        $dir = DOCUROOT."/upload/article/pic/group/$first/$second/";
        if( !is_dir($dir)) files::mkdirs($dir);
        
        while(file_exists($dir."$random_num.jpg")){
            $random_num = $this->random_string(10);
        }

        file_put_contents($dir."$random_num.jpg", $file);
        return $random_num;
    }

    public function getFileURL($random_num){
        $first = substr($random_num, 2, 2 );
        $second = substr($random_num, 0, 2 );
        return "/upload/article/pic/group/$first/$second/$random_num.jpg";
    }

    public function save_picture($file){
        $file_number = $this->save_file($file);
        return $this->getFileURL($file_number);
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