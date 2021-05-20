<?php

use Tsc\CatStorageSystem\File;
use Tsc\CatStorageSystem\FileSystem;
use Tsc\CatStorageSystem\Directory;

/**
 * Create an instance of a file
 *
 * @param Directory $directory The path to the file
 * @param string $name The name of the file
 * @return File
 */
function instantiate_file(Directory $directory, string $name)
{
    $file = new File();
    $file->setParentDirectory($directory);
    $file->setName($name);
    return $file;
}

/**
 * Save the file into a new directory.
 *
 * @param string $location Name a directory to save the file into
 * @throws Exception
 */
function create_file_group(string $location)
{
    $dir = instantiate_dir("images\\", "");
    $saveTo = instantiate_dir("cat_storage\\", $location);
    $fileSystem = new FileSystem();
    $file = instantiate_file($dir, "cat_1.gif");
    try {
        $fileSystem->createFile($file, $saveTo);
        $file = instantiate_file($dir, "cat_2.gif");
        $fileSystem->createFile($file, $saveTo);
        $file = instantiate_file($dir, "cat_3.gif");
        $fileSystem->createFile($file, $saveTo);
    }catch (Exception $e) {
        $e->getMessage();
    }
}

/**
 * Rename all of the files in a given directory.
 *
 * @param string $location The name of the files' directory
 * @throws Exception
 */
function rename_file_group(string $location)
{
    $dir = instantiate_dir("cat_storage\\", $location);
    $file = instantiate_file($dir, "cat_1.gif");
    $fileSystem = new FileSystem();
    try {
        $fileSystem->renameFile($file, "cute.gif");
        $file = instantiate_file($dir, "cat_2.gif");
        $fileSystem->renameFile($file, "sweetie.gif");
        $file = instantiate_file($dir, "cat_3.gif");
        $fileSystem->renameFile($file, "adorable.gif");
    } catch (Exception $e) {
        $e->getMessage();
    }
}

/**
 * Delete a directory and the files contained within.
 *
 * @param string $location The location of files to be deleted
 */
function delete_file_group(string $location)
{
    $dir = instantiate_dir("cat_storage\\", $location);
    $fileSystem = new FileSystem();
    $fileSystem->deleteDirectory($dir);
}

/**
 * Update the size and modification time of all files in a folder.
 *
 * @param string $location The files to be updated
 */
function update_file_group(string $location)
{
    $dir = instantiate_dir("cat_storage\\", $location);
    $file = instantiate_file($dir, "cat_1.gif");
    $file->setModifiedTime(new DateTime());
    $fileSystem = new FileSystem();
    $fileSystem->updateFile($file);
    $file = instantiate_file($dir, "cat_2.gif");
    $file->setModifiedTime(new DateTime());
    $fileSystem->updateFile($file);
    $file = instantiate_file($dir, "cat_3.gif");
    $file->setModifiedTime(new DateTime());
    $fileSystem->updateFile($file);
}

/**
 * Create an instance of a directory.
 *
 * @param string $basePath The path to the directory.
 * @param string $name The name of the last directory in the path.
 * @return Directory
 */
function instantiate_dir(string $basePath, string $name)
{
    $directory = new Directory();
    $directory->setPath($basePath);
    $directory->setName($name);
    return $directory;
}

/**
 * Gets all of the information about a given directory
 * - Number of dirs
 * - Number of files
 * - Storage size of dir
 * - The names of all dirs
 * - The names of all files
 *
 * @param Directory $directory The directory to gather information on.
 * @param FileSystem $fileSystem The system which will gather all of the information.
 */
function get_directory_information(Directory $directory, FileSystem $fileSystem)
{
    echo "Total Number of sub directories:\n";
    echo $fileSystem->getDirectoryCount($directory) . "\n\n";
    echo "Total number of files in current directory and all sub-directories:\n";
    echo $fileSystem->getFileCount($directory) . "\n\n";
    echo "Get the storage size of the directory:\n";
    echo $fileSystem->getDirectorySize($directory) . "\n\n";
    echo "List all of the directories and sub-directories:\n";
    print_r($fileSystem->getDirectories($directory));
    echo "List all of the files in this directory and its sub-directories:\n";
    print_r($fileSystem->getFiles($directory));
}