<?php
class article_pic{
    public function save_file($file){
        if(substr($file, 0, 4) === "data"){
            $file = file_get_contents($file);
        }
        else {
            $file = base64_decode($file);
        }
        $random_num = $this->random_string(15);
        while(file_exists(DOCUROOT."/upload/$random_num.jpg")){
            $random_num = $this->random_string(15);
        }
        file_put_contents(DOCUROOT."/upload/$random_num.jpg", $file);
        return $random_num;
    }

    public function getFileURL($random_num){
        return "/upload/$random_num.jpg";
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