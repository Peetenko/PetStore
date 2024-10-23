<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Models\PetFacade;
use PetDB;



final class PetsPresenter extends Nette\Application\UI\Presenter
{
    private $facade;
	private $pet;
	private $pets;

    public function __construct(PetFacade $facade){
        $this->facade = $facade;
    }

    public function renderAll(){
       $this->template->pets = $this->facade->getAllPets();
	   $this->template->categories = $this->getCategory();
	   $this->template->statuses = $this->getStatus();
	   
    }

	public function renderHome(PetDB | array | null $pet, null|array $pets){
		$this->template->pet = $pet;
		$this->template->pets = $pets;
		$this->template->categories = $this->getCategory();
	   	$this->template->statuses = $this->getStatus();
	}

	public function renderFind(null | array $pets){
		$this->template->pets = $pets;
		$this->template->categories = $this->getCategory();
	   	$this->template->statuses = $this->getStatus();
	}
	
	public function actionEdit($petid){
		$this->pet = $this->facade->editPet($petid);
	}

	public function actionDelete($petid){
	$this->template->pets = $this->facade->deletePet($petid); 
	$this->redirect('Pets:all');
	}

	public function getCategory(): array {
		$category = ['Choose Pet','Dog','Cat','Fish','Turtle','Horse'];
		return $category;
	}

	public function getStatus(): array {
		$status = ['Not Available','Available','Soon'];
		return $status;
	}

	public function getTags(): array {
		$tags = ['id','name','category','image','status'];
		return $tags;
	}
 

    protected function createComponentAddPet(): Form
	{
		$category = $this->getCategory();
		$status = $this->getStatus();
		$form = new Form;
		$form->addText('name', 'Name:')->setRequired('Prosim zadaj meno Zvierata');
		$form->addSelect('category', 'Category:',$category)
            ->setRequired('Prosim vyber kategoriu Zvierata')
            ->setDefaultValue(0);
        $form->addUpload('image', 'Obrazok:')
            ->addRule($form::Image, 'Avatar must be JPEG, PNG, GIF, WebP or AVIF')
            ->addRule($form::MaxFileSize, 'Maximum size is 1 MB', 1024 * 1024);
        $form->addSelect('status', 'Search status:', $status);
		//in case of new field like age uncomment this and change according to your needs
		//$form->addText('age','Age:');
		$form->addSubmit('send', 'Add Pet');
		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}

	protected function createComponentEditPet(): Form
	{
		$category = $this->getCategory();
		$status = $this->getStatus();
		$form = new Form;
		$form->addText('name', 'Name:')
			->setRequired('Prosim zadaj meno Zvierata');
		$form->addSelect('category', 'Category:',$category)
            ->setRequired('Prosim vyber kategoriu Zvierata')
            ->setDefaultValue(0);
        $form->addUpload('image', 'Obrazok:')
            ->addRule($form::Image, 'Avatar must be JPEG, PNG, GIF, WebP or AVIF')
            ->addRule($form::MaxFileSize, 'Maximum size is 1 MB', 1024 * 1024);
        $form->addSelect('status', 'Status:', $status);
		//in case of new field like age uncomment this and change according to your needs
		//$form->addText('age','Age:');
		$form->setDefaults($this->pet);
		$form->addSubmit('edit', 'Edit Pet');
		$form->onSuccess[] = [$this, 'updateFormSucceeded'];
	
		return $form;
	}

	protected function createComponentSearchPet(): Form {
		$form = new Form;
		$form->addSelect('searchBy','Search by:',['id','status'])
			->setHtmlAttribute('onChange', 'fnSearchToggle()');
		$form->addText('searchValue','Search Id:');
		$form->addSelect('status','Search status:',$this->getStatus());
		$form->addSubmit('search','Search Pet');
		$form->onSuccess[] = [$this, 'searchFormSucceeded'];

		return $form;
	}

	public function formSucceeded(Form $form, $data): void
	{
		$file = PetDB::getDB();
		$xmlPath = PetDB::getDBpath();
		//get last id and set new id for pet
		$lastPet = $file->lastElementChild->childNodes->length - 1;
		$newPetId = $file->lastElementChild->childNodes[$lastPet]->childNodes[0]->nodeValue + 1;
		$image = $data['image']->hasFile() ? rand(1,1000) . $data['image']->name : 'No image';
		$root = $file->documentElement;
		$newPet = $file->createElement('pet');
		$newPet->appendChild($file->createElement('id', strval($newPetId)));
		$newPet->appendChild($file->createElement('name', $data->name));
		$newPet->appendChild($file->createElement('category',strval($data->category)));
		$newPet->appendChild($file->createElement('image', $image));
		$newPet->appendChild($file->createElement('status', strval($data->status)));
		//in case of new field like age uncomment this and change according to your needs
		//$newPet->appendChild($file->createElement('age', strval($data->age)));
		$root->appendChild($newPet);
		$file->save($xmlPath);
		
		//save image if exists
		$data['image']->hasFile() ? $data['image']->move('../www/images/'. $image ) : '';
		
		$this->flashMessage('Pet ' . $data->name . ' Added');
		$this->redirect('Pets:all');
	}

	public function updateFormSucceeded(Form $form, array $data): void
	{
		$file = PetDB::getDB();
		$xmlPath = PetDB::getDBpath();
		$root = $file->documentElement;
		$image = $data['image']->hasFile() ? rand(1,1000) . $data['image']->name : 'No image';
		foreach($root->childNodes as $pet){
			if($pet->childNodes[0]->nodeValue == $this->pet->id){
				foreach($pet->childNodes as $attr){
					$attrName = $attr->nodeName;
					switch ($attrName) {
						case 'name':
							$attr->nodeValue = $data['name'];
							break;
						case 'category':
							$attr->nodeValue = strval($data['category']);
							break;
						case 'image':
							if($data['image']->hasFile()){
								$attr->nodeValue = $image;
								$data['image']->move('../www/images/'. $image);
							}
							break;
						case 'status':
							$attr->nodeValue = strval($data['status']);
							break;
						//uncomment case for age or any new field to save data
						/*case 'age':
							$attr->nodeValue = strval($data['age']);
							break;*/
						
					}
					$file->save($xmlPath);
				}
			}
		}
		$this->flashMessage('Successfully updated pet ' . $data['name']);
		$this->redirect('Pets:all');
	
	}

	public function searchFormSucceeded(Form $form, $data)
	{
		$searchBy = $data['searchBy'];
		$searchValue = $data['searchValue'];
		$status = $data['status'];
		switch ($searchBy) {
			case '0':
				# search by id
				$petId = $searchValue;
				$pet = $this->facade->editPet($petId);
				$this->pet = $pet;
				if(!$pet){
					$this->flashMessage('Pet with this id does not exist');
				}else{
					$this->flashMessage('Successfully showed pet');
				}
				
				$this->forward('Pets:home', $pet);
				break;
			
			case '1':
				# search by status
				$allPets = $this->facade->getAllPets();
				$pets = [];
				foreach($allPets as $pet){
					if($pet->status == $status){
						$pets[] = $pet;
					}
				}
				if(!$pets){
					$this->flashMessage('Pet with this status does not exist');
				}else{
					$this->flashMessage('Successfully showed pet');
				}

				$this->template->pets = $pets;
				$this->redirect('Pets:find',[$pets]);
				break;
		}
		
	}
        
}

