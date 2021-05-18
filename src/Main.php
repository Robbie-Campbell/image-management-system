<?php

require_once("../classes/Folder.class.php");
require_once("../classes/File.class.php");
require_once("../classes/FileSystem.class.php");

create_file_group("very_cute\\");
//rename_file_group("cute\\");
//delete_file_group("cute\\");
update_file_group("cute\\");

$dir = instantiate_dir("..\\cat_storage\\", "");
$fileSystem = new FileSystem();
get_directory_information($dir, $fileSystem);

function instantiate_file(Folder $directory, string $name)
{
    $file = new File();
    $file->setParentDirectory($directory);
    $file->setName($name);
    return $file;
}

function create_file_group(string $location)
{
    $dir = instantiate_dir("..\\", "images\\");
    $saveTo = instantiate_dir("..\\cat_storage\\", $location);
    $fileSystem = new FileSystem();
    $file = instantiate_file($dir, "cat_1.gif");
    $fileSystem->createFile($file, $saveTo);
    $file = instantiate_file($dir, "cat_2.gif");
    $fileSystem->createFile($file, $saveTo);
    $file = instantiate_file($dir, "cat_3.gif");
    $fileSystem->createFile($file, $saveTo);
}

function rename_file_group(string $location)
{
    $dir = instantiate_dir("..\\cat_storage\\", $location);
    $file = instantiate_file($dir, "cat_1.gif");
    $fileSystem = new FileSystem();
    $fileSystem->renameFile($file, "cute.gif");
    $file = instantiate_file($dir, "cat_2.gif");
    $fileSystem->renameFile($file, "sweetie.gif");
    $file = instantiate_file($dir, "cat_3.gif");
    $fileSystem->renameFile($file, "adorable.gif");
}

function delete_file_group(string $location)
{
    $dir = instantiate_dir("..\\cat_storage\\", $location);
    $fileSystem = new FileSystem();
    $fileSystem->deleteDirectory($dir);
}

function update_file_group(string $location)
{
    $dir = instantiate_dir("..\\cat_storage\\", $location);
    $file = instantiate_file($dir, "cat_1.gif");
    $file->setModifiedTime(new DateTime());
    $file->setSize(200);
    $fileSystem = new FileSystem();
    $fileSystem->updateFile($file);
    $file = instantiate_file($dir, "cat_2.gif");
    $file->setModifiedTime(new DateTime());
    $file->setSize(200);
    $fileSystem->updateFile($file);
    $file = instantiate_file($dir, "cat_2.gif");
    $file->setModifiedTime(new DateTime());
    $file->setSize(200);
    $fileSystem->updateFile($file);
}

function instantiate_dir(string $base_path, string $name)
{
    $directory = new Folder();
    $directory->setPath($base_path);
    $directory->setName($name);
    return $directory;
}

function get_directory_information(Folder $directory, FileSystem $fileSystem)
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