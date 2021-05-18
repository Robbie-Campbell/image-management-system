<?php

require_once("../src/DirectoryInterface.php");
require_once("../src/FileSystemInterface.php");
require_once("../src/FileInterface.php");

use Tsc\CatStorageSystem\DirectoryInterface;
use Tsc\CatStorageSystem\FileSystemInterface;
use Tsc\CatStorageSystem\FileInterface;

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
     * @return FileInterface|void
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent)
    {
        $saveLocation = $parent->getPath() . $parent->getName();
        $this->createDirectory($parent);
        $cat = fopen($file->getPath() . $file->getName(), "r");
        if (file_exists($saveLocation . $file->getName())){
            echo "This file already exists" . "\n";
        }
        else{
            file_put_contents($saveLocation . $file->getName(), $cat);
            $file->setParentDirectory($parent);
            echo "File successfully created! Meow!" . "\n";
        }
    }

    /**
     * Takes an already saved file then updates both the "updated time" and the size of the image file.
     *
     * @param FileInterface $file Contains the changed file information
     * @return FileInterface|void
     */
    public function updateFile(FileInterface $file)
    {
        list($width, $height) = getimagesize($file->getPath() . $file->getName());
        $src = imagecreatefromgif($file->getPath() . $file->getName());
        $dst = imagecreatetruecolor($file->getSize(), $file->getSize());
        imagecolortransparent($dst);
        imagecopyresized($dst, $src, 0, 0, 0, 0, $file->getSize(), $file->getSize(), $width, $height);
        imagegif($dst, $file->getPath() . $file->getName());
        touch($file->getPath() . $file->getName(), $file->getModifiedTime()->getTimestamp());
    }

    /**
     * Changes the name of a file and updates the modified time
     * @param FileInterface $file
     * @param string $newName
     * @return FileInterface|void
     */
    public function renameFile(FileInterface $file, $newName)
    {
        $target = $file->getPath() . $file->getName();
        if(file_exists($file->getPath() . $newName))
        {
            echo "File with that name already exists." . "\n";
        }
        else if (file_exists($target)){
            rename($target, $file->getPath() . $newName);
            $file->setName($newName);
            $file->setModifiedTime(new DateTime());
            touch($file->getPath() . $file->getName(), $file->getModifiedTime()->getTimestamp());
            echo "File successfully renamed!" . "\n";
        }
        else{
            echo "File does not exist" . "\n";
        }
    }

    /**
     * Deletes a given file and return whether it was successful.
     *
     * @param FileInterface $file File to be deleted
     * @return bool|int Success status
     */
    public function deleteFile(FileInterface $file)
    {
        $target = $file->getPath() . $file->getName();
        if (file_exists($target))
            return unlink($target);
        else
            return 0;
    }

    /**
     * Separates the path into a list of individual directories then creates them if they do not exist.
     *
     * @param DirectoryInterface $directory Contains the path to be created
     * @return DirectoryInterface|void
     */
    public function createRootDirectory(DirectoryInterface $directory)
    {
        $base_dirs = explode("\\",$directory->getPath());
        $path = "";
        foreach($base_dirs as $base_dir) {
            $path .= $base_dir . "\\";
            if (!file_exists($path)) {
                mkdir($path);
            }
        }
    }

    /**
     * Creates the final sub directory for files to be saved into.
     *
     * @param DirectoryInterface $directory The path of the directory
     * @return DirectoryInterface|void
     */
    public function createDirectory(DirectoryInterface $directory)
    {
        $this->createRootDirectory($directory);
        if (!file_exists($directory->getPath() . $directory->getName()))
        {
            mkdir($directory->getPath() . $directory->getName());
            echo "Directory has been created" . "\n";
        }
        else
        {
            echo "Directory already exists" . "\n";
        }
    }

    /**
     * Deletes a given directory and returns whether or not it was successful.
     *
     * @param DirectoryInterface $directory Directory to be deleted
     * @return bool Success status
     */
    public function deleteDirectory(DirectoryInterface $directory)
    {
        $target = $directory->getPath() . $directory->getName();
        if (is_dir($target)) {
            $open_dir = opendir($target);
            while ($file = readdir($open_dir)) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($target . "\\" . $file))
                        unlink($target . "\\" . $file);
                    else
                        rmdir($target . "\\" . $file);
                }
            }
            closedir($open_dir);
            rmdir($target);
            return true;
        }
        else
            return false;
    }

    /**
     * Changes the name of a given directory.
     *
     * @param DirectoryInterface $directory The directory to change
     * @param string $newName The new name of the directory
     * @return DirectoryInterface|void
     */
    public function renameDirectory(DirectoryInterface $directory, $newName)
    {
        $target = $directory->getPath() . $directory->getName();
        $directory->setName($newName);
        if (file_exists($target)){
            rename($target, $directory->getPath() . $directory->getName());
            echo "Directory successfully renamed!" . "\n";
        }
        else{
            echo "Directory does not exist" . "\n";
        }
    }

    /**
     * Counts all of the sub-directories contained within a given directory recursively.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return int|mixed The total number of directories
     */
    public function getDirectoryCount(DirectoryInterface $directory)
    {
        $index = 0;
        $target = $directory->getPath() . $directory->getName();
        $dh = opendir($target);
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && is_dir($target . DIRECTORY_SEPARATOR . $file)) {
                $temp = new Folder();
                $temp->setPath($target . DIRECTORY_SEPARATOR);
                $temp->setName($file);
                $sub_num = $this->getDirectoryCount($temp);
                $index += (1 + $sub_num);
            }
        }
        return $index;
    }

    /**
     * Counts all of the files contained within a given directory recursively.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return int|mixed The total number of files
     */
    public function getFileCount(DirectoryInterface $directory)
    {
        $index = 0;
        $target = $directory->getPath() . $directory->getName();
        $dh = opendir($target);
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && is_dir($target . DIRECTORY_SEPARATOR . $file)) {
                $temp = new Folder();
                $temp->setPath($target . DIRECTORY_SEPARATOR);
                $temp->setName($file);
                $sub_num = $this->getFileCount($temp);
                $index += $sub_num;
            }
            else if ($file != "." && $file != ".." && !is_dir($target . DIRECTORY_SEPARATOR . $file)) {
                $index += 1;
            }
        }
        return $index;
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
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory->getPath() . $directory->getName())) as $file){
            $size+=$file->getSize();
        }
        return $size;
    }

    /**
     * This method gets all of the directory names in a given directory and returns them in an arr.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return array|false|DirectoryInterface[] All the sub-directory names
     */
    public function getDirectories(DirectoryInterface $directory)
    {
        $dirs = array_filter(glob($directory->getPath() . $directory->getName() . '*'), 'is_dir');
        foreach($dirs as $dir) {
            $temp = new Folder();
            $temp->setPath($dir);
            $temp->setName(DIRECTORY_SEPARATOR);
            $sub = $this->getDirectories($temp);
            array_push($dirs, ...$sub);
        }
        return $dirs;
    }

    /**
     * This method gets all of the file names in a given directory and returns them in an arr.
     *
     * @param DirectoryInterface $directory The directory to be checked
     * @return array|FileInterface[] All of the contained files
     */
    public function getFiles(DirectoryInterface $directory)
    {
        $path = $directory->getPath()  . $directory->getName();
        $files = scandir($path);
        $return_arr = [];
        foreach($files as $file) {
            if(is_dir($path . DIRECTORY_SEPARATOR . $file) && $file != "." && $file != "..") {
                $temp = new Folder();
                $temp->setPath($path . DIRECTORY_SEPARATOR);
                $temp->setName($file . DIRECTORY_SEPARATOR);
                $sub = $this->getFiles($temp);
                array_push($return_arr, ...$sub);
            }
            if (is_file($path . DIRECTORY_SEPARATOR . $file) && $file != "." && $file != "..") {
                array_push($return_arr, $path . $file);
            }
        }
        return $return_arr;
    }
}