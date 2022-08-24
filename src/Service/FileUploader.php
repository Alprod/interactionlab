<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

	public function __construct( private $targetDirectory, private SluggerInterface $slugger) {}

	public function upload( UploadedFile $file)
	{
		$originalFilname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$saveFilename = $this->slugger->slug($originalFilname);
		$filename = $saveFilename.'_'.uniqid('Av',false).'.'.$file->guessExtension();

		try {
			$file->move(
				$this->getTargetDirectory(),
				$filename
			);
		}catch ( FileException $e) {

		}
		return $filename;
	}

	/**
	 * @return mixed
	 */
	public function getTargetDirectory(): mixed {
		return $this->targetDirectory;
	}

}