<?php

require_once("../classes/Folder.class.php");
require_once("../classes/File.class.php");
require_once("../classes/FileSystem.class.php");

$dir = instantiate_dir("..\\images\\", "cute_cats\\");
$file = instantiate_file($dir, "cat_3.gif");
$fileSystem = new FileSystem();
$fileSystem->createFile($file, $dir);
$fileSystem->renameFile($file, "adorable.gif");

//$fileSystem->createDirectory($dir);
//$file->setSize(200);
//$fileSystem->updateFile($file);

get_directory_information($dir, $fileSystem);


//# Create a sample directory
//$directory = new DirectoryClass();
//$directory->setPath("..\\images\\");
//$directory->setName("the_cutest_cats\\");
//echo $directory->getPath() . $directory->getName() . "\n";
//
//# Create a nested directory
//$nested_dir = new DirectoryClass();
//$nested_dir->setPath("..\\images\\cute_cats\\");
//$nested_dir->setName("tabby\\");
//echo $nested_dir->getPath() . $nested_dir->getName() . "\n";
//
//# Copy one of the cat files
//$file = new File();
//$file->setParentDirectory($directory);
//$file->setName("sweetie.gif");
//print_r($file->getSize());

# Create a file

//$file_system->updateFile($file);
//$file_system->createFile($file, $directory);
//$file_system->renameFile($file, "sweetie.gif");
//$file->setName("cat_2.gif");
//$file_system->createFile($file, $directory);
//
//# Rename a file
//$file_system->renameFile($file, "cutie.gif");
//echo $file_system->deleteDirectory($directory);
//$file_system->renameDirectory($directory, "the_cutest_cats");
get_directory_information($dir, $fileSystem);

function instantiate_file(Folder $directory, string $name)
{
    $file = new File();
    $file->setParentDirectory($directory);
    $file->setName($name);
    return $file;
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