<?php
 namespace App\Helpers;
 use \File;
 class FileHelper
 {
       
        /**
         * upload file 
         * param $file:file 
         * param $path:string 
         * param $allowedfileExtension:string 
         * @return response
         */ 
        public static  function upload($file,$path,$allowedfileExtension)
        {   
          $response = [];
    //        $path = public_path().'/uploads/students/'.$id;
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    //        $allowedfileExtension = ['jpg','png','jpeg','pdf','JPG','PNG','JPEG','PDF'];  
            $extension = $file->getClientOriginalExtension(); 
            if(in_array($extension,$allowedfileExtension)): 
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);  
                $fileNameToStore = $filename.'_'.date("Ymdhisa").'_'.rand().'.'.$extension;
                if($file->move($path,$fileNameToStore)): $response['file_name'] = $fileNameToStore; endif;
            endif;  
            return $response;
       }
   
  
       
    
  }