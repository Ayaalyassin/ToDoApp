<?php
namespace App\Traits;


Trait  FileTrait
{

    function saveFile($file,$folder)
    {
        $file_extension = $file -> getClientOriginalExtension();
        $file_name =  time().'.'.$file_extension;
        $file_path=public_path(). '/files/'.$folder;
        $file -> move($file_path,$file_name);
        return 'files/'.$folder.'/'. $file_name;
    }



    public function deleteFile($file)
    {

        if($file)
            unlink($file);
    }



}
