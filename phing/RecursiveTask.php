<?php

use Nette\Utils\Finder;
use Nette\Utils\Strings;

require_once __DIR__ . '/../vendor/autoload.php';

class RecursiveTask extends Task
{

	/** Array of filesets */
	private $filesets = array();

	/**
	 * Nested creator, adds a set of files (nested fileset attribute).
	 */
	public function createFileSet()
	{
		$num = array_push($this->filesets, new FileSet());

		return $this->filesets[$num - 1];
	}

	/**
	 * Parse a Phing build file and copy the properties, tasks, data types and
	 * targets it defines into the current project.
	 *
	 * @return void
	 */
	public function main()
	{
		$targetName = $this->getOwningTarget()->getName();

		foreach ($this->filesets as $fs) {
			foreach (Finder::findDirectories('*')->in($fs->dir->getPath()) as $dir2) {
				foreach (Finder::findDirectories('*')->in($dir2) as $dir) {
					foreach (Finder::findFiles('venne-phing.xml')->in($dir) as $file) {

						$task = new ImportTask();
						$task->setProject($this->project);
						$task->init();
						$task->setFile($file->getPathname());
						$task->main();

						foreach ($this->getProject()->getTargets() as $name => $target) {
							if (Strings::endsWith($name, '.' . $targetName)) {
								$target->main();
							}
						}
					}
				}
			}
		}
	}

}
