<?php

namespace It121\AddressBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ImportPaf
 *
 */
class ImportPaf
{
    /**
	 * @Assert\File(
	 * 		maxSize = "500k",
	 * 		mimeTypes = {"text/csv", "text/plain"},
     *      mimeTypesMessage = "Please upload a valid file (*.csv)"
	 * )
	 */
    private $file;
	
    /**
     *
     */
    private $filePath;
    

	/**
	 * @param UploadedFile $file
	 */
	public function setFile(UploadedFile $file)
	{
		$this->file = $file;
	}
	/**
	 * @return UploadedFile
	 */
	public function getFile()
	{
		return $this->file;
	}
	
	public function uploadFile($uploadDir)
	{
		if (null === $this->file) {
			return;
		}
		
		$extension = $this->file->getClientOriginalExtension();
		$currentDate = new \DateTime('now');
		$fileName = $currentDate->format('YmdHis').'_'.uniqid().'.'.$extension;
		$this->file->move($uploadDir, $fileName);
		$this->setFilePath($fileName);
	}
	
	/**
	 * 
	 * @return \It121\AddressBundle\Entity\ImportPaf
	 */
	public function getFilePath() {
		return $this->filePath;
	}
	
	
	/**
	 * 
	 * @param $filePath
	 * @return \It121\AddressBundle\Entity\ImportPaf
	 */
	public function setFilePath($filePath) {
		$this->filePath = $filePath;
		return $this;
	}
}
