<?php

namespace Tsc\CatStorageSystem;

/**
 * Class FileSystem
 *
 * This class handles all of the interactions with the files and directories.
 */
class FileSystem implements FileSystemInterface
{
    /**
     * Gets an image of a cat in the $file parameter, then saves it in the path of the $parent.
     *
     * @param FileInterface $file The file containing the image of the cat
     * @param DirectoryInterface $parent The place where the file will be saved
     * @return FileInterface
     * @throws \Exception The file already exists
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent)
    {
        $saveLocation = $parent->getFullPath();
        $this->createDirectory($parent);
        $cat = fopen($file->getFullName(), "r");
        if (file_exists($saveLocation . $file->getName())) {
            throw new \Exception("This file already exists");
        } else {
            file_put_contents($saveLocation . $file->getName(), $cat);
            $file->setParentDirectory($parent);
        }
        return $file;
    }

    /**
     * Takes an already saved file then updates the modified time
     *
     * @param FileInterface $file Contains the changed file information
     * @return FileInterface
     */
    public function updateFile(FileInterface $file)
    {
        touch($file->getFullName(), $file->getModifiedTime()->getTimestamp());
        return $file;
    }

    /**
     * Changes the name of a file and updates the modified time
     * @param FileInterface $file
     * @param string $newName
     * @return FileInterface
     * @throws \Exception The file doesn't exist
     */
    public function renameFile(FileInterface $file, $newName)
    {
        $target = $file->getFullName();
        if(file_exists($file->getPath() . $newName)) {
            throw new \Exception("File with that name already exists.");
        } else if (file_exists($target)) {
            rename($target, $file->getPath() . $newName);
            $file->setName($newName);
            $file->setModifiedTime(new \DateTime());
            touch($file->getFullName(), $file->getModifiedTime()->getTimestamp());
        } else {
            throw new \Exception("File does not exist");
        }
        return $file;
    }

    /**
     * Deletes a given file and return whether it was successful.
     *
     * @param FileInterface $file File to be deleted
     * @return bool Success status
     */
    public function deleteFile(FileInterface $file)
    {
        $target = $file->getFullName();
        if (file_exists($target)) {
            return unlink($target);
        } else {
            return false;
        }
    }

    /**
     * Separates the path into a list of individual directories then creates them if they do not exist.
     *
     * @param DirectoryInterface $directory Contains the path to be created
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory)
    {
        $baseDirs = explode("\\",$directory->getPath());
        $path = "";
        foreach($baseDirs as $baseDir) {
            $path .= $baseDir . "\\";
            if (!file_exists($path)) {
                mkdir($path);
            }
        }
        return $directory;
    }

    /**
     * Creates the final sub directory for files to be saved into.
     *
     * @param DirectoryInterface $directory The path of the directory
     * @return DirectoryInterface
     * @throws \Exception
     */
    public function createDirectory(DirectoryInterface $directory)
    {
        $this->createRootDirectory($directory);
        if (!file_exists($directory->getFullPath())) {
            mkdir($directory->getFullPath());
        }
        return $directory;
    }

    /**
     * Deletes a given directory and returns whether or not it was successful.
     *
     * @param DirectoryInterface $directory Directory to be deleted
     * @return bool Success status
     */
    public function deleteDirectory(DirectoryInterface $directory)
    {
        $dirs = $this->getDirectories($directory);
        if (count($dirs) < 1) {
            $files = $this->getFiles($directory);
            foreach ($files as $file) {
                $this->deleteFile($file);
            }
            rmdir($directory->getFullPath());
            return true;
        }
        foreach ($dirs as $dir) {
            $files = $this->getFiles($dir);
            foreach ($files as $file) {
                $this->deleteFile($file);
            }
            rmdir($dir->getFullPath());
        }
        rmdir($directory->getFullPath());
        return true;
    }

    /**
     * Changes the name of a given directory.
     *
     * @param DirectoryInterface $directory The directory to change
     * @param string $newName The new name of the directory
     * @return DirectoryInterface
     * @throws \Exception The directory doesn't exist
     */
    public function renameDirectory(DirectoryInterface $directory, $newName)
    {
        $target = $directory->getFullPath();
        $directory->setName($newName);
        if (file_exists($target)){
            rename($target, $directory->getFullPath());
        } else {
            throw new \Exception("Directory does not exist");
        }
        return $directory;
    }

    /**
     * Counts all of the sub-directories contained within a given directory.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return int The total number of directories
     */
    public function getDirectoryCount(DirectoryInterface $directory)
    {
        return count($this->getDirectories($directory));
    }

    /**
     * Counts all of the files contained within a given directory.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return int The total number of files
     */
    public function getFileCount(DirectoryInterface $directory)
    {
        return count($this->getFiles($directory));
    }

    /**
     * Returns the storage size of a given directory.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return int The size of the directory
     */
    public function getDirectorySize(DirectoryInterface $directory)
    {
        $size = 0;
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory->getFullPath())) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    /**
     * This method gets all of the directory names in a given directory and returns them in an arr.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return DirectoryInterface[] All the sub-directory names
     */
    public function getDirectories(DirectoryInterface $directory)
    {
        $files = scandir($directory->getFullPath());
        $files = array_diff($files, [".", ".."]);
        $returnArr = [];
        foreach($files as $file) {
            if (is_dir($directory->getFullPath() . $file)) {
                $temp = new Directory();
                $temp->setPath($directory->getFullPath());
                $temp->setName($file . DIRECTORY_SEPARATOR);
                $val = $this->getDirectories($temp);
                if (count($val) > 0) {
                    array_push($returnArr, ...$val);
                }
                array_push($returnArr, $temp);
            }
        }
        return $returnArr;
    }

    /**
     * This method gets all of the file names in a given directory and returns them in an arr.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return FileInterface[] All of the contained files
     */
    public function getFiles(DirectoryInterface $directory)
    {
        $files = scandir($directory->getFullPath());
        $returnArr = [];
        foreach($files as $file) {
            if(is_dir($directory->getFullPath() . DIRECTORY_SEPARATOR . $file) && $file != "." && $file != "..") {
                $temp = new Directory();
                $temp->setPath($directory->getFullPath() . DIRECTORY_SEPARATOR);
                $temp->setName($file . DIRECTORY_SEPARATOR);
            }
            if (is_file($directory->getFullPath() . DIRECTORY_SEPARATOR . $file) && $file != "." && $file != "..") {
                $dir = new Directory();
                $dir->setPath($directory->getPath());
                $dir->setName($directory->getName());
                $storeFile = new File();
                $storeFile->setParentDirectory($dir);
                $storeFile->setName($file);
                array_push($returnArr, $storeFile);
            }
        }
        return $returnArr;
    }
}