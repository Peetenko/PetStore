<?php
namespace App\Models;
use Nette\Database\Explorer;
use Nette\Utils\FileSystem;
use PetDB;
use Tracy\Debugger;


final class PetFacade{

    private $database;
    
    public function __construct(Explorer $database){
        $this->database = $database;

    }

    public function getAllPets(){
        
        $pets = $this->readDatabase(PetDB::getDBpath());
        return $pets;
    }

    public function editPet($petId){
        
        $pets = $this->getAllPets();
        foreach($pets as $pet){
            if($pet->id == $petId){
                return $pet;
            }
        }
        
    }

    public function deletePet($petId){
        $file = PetDB::getDB();
        $root = $file->documentElement;
		$pets = $file->getElementsByTagName('pet');
        foreach($pets as $pet){
            foreach($pet->getElementsByTagName('id') as $id){
                if($id->firstChild->nodeValue == $petId){
                    $nodeToDelete = $id->parentNode;
                    $root->removeChild($nodeToDelete);
                    $file->save(PetDB::getDBpath());
                }
            }
        }  
    }

    function readDatabase($filename){
        // read the XML database of Pets
        $data = file_get_contents($filename);
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $values, $tags);
        xml_parser_free($parser);
        
        // loop through the structures
        foreach ($tags as $key=>$val) {
            if ($key == "pet") {
                $petranges = $val;
                // each contiguous pair of array entries are the 
                // lower and upper range for each Pet definition
                for ($i=0; $i < count($petranges); $i+=2) {
                    $offset = $petranges[$i] + 1;
                    $len = $petranges[$i + 1] - $offset;
                    $tdb[] = $this->parsePets(array_slice($values, $offset, $len));
                }
            } else {
                continue;
            }
        }
        
        return $tdb;
    }

    function parsePets($pvalues){
        for ($i=0; $i < count($pvalues); $i++) {
            $pets[$pvalues[$i]["tag"]] = $pvalues[$i]["value"];
        }
        
        return new PetDB($pets);
        
    }
    

}