<?php

class PetDB {
   
    function __construct ($aa) 
    {
        foreach ($aa as $k=>$v)
            $this->$k = $aa[$k];
    }

    public static function getDB(){
        $file = new DOMDocument;
		$file->preserveWhiteSpace = false;
		$file->formatOutput = true;
		$file->load(PetDB::getDBpath());

        return $file;
    }

    public static function getDBpath(){
        $xmlPath = '../app/DB/petstore.xml';
        return $xmlPath;
    }
}


