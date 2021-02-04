<?php
class article_pic{
    public function save_file($file){
        // $encodedData = str_replace(' ','+',$file);
        // $decodedData = base64_decode($encodedData);
        // echo(var_dump($decodedData));
        // echo(file_get_contents($file));
        // echo(DOCUROOT);
        // file_put_contents(DOCUROOT."/data/test.jpg", $decodedData);
        // file_put_contents(DOCUROOT."/data/test1.jpg", $file);
        $random_num = $this->random_string(15);
        while(file_exists(DOCUROOT."/upload/$random_num.jpg")){
            $random_num = $this->random_string(15);
        }
        file_put_contents(DOCUROOT."/upload/$random_num.jpg", file_get_contents($file));
        return $random_num;
    }

    public function getFileURL($random_num){
        return "/upload/$random_num.jpg";
    }

    public function save_avatar($file){
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