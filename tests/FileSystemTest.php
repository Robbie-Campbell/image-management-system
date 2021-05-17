<?php

namespace Tsc\CatStorageSystem;

require_once("../classes/File.class.php");
require_once("../classes/Folder.class.php");
require_once("../classes/FileSystem.class.php");

use File;
use FileSystem;
use Folder;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase {

    public function test_create_file(){

        $fileDir = new Folder();
        $fileDir->setPath("..\\");
        $fileDir->setName("images");

        $saveDir = new Folder();
        $saveDir->setPath("..\\test\\");
        $saveDir->setName("cats\\");

        $file = new File();
        $file->setParentDirectory($fileDir);
        $file->setName("cat_1.gif");

        # Create a file
        $fileSystem = new FileSystem();
        $fileSystem->createFile($file, $saveDir);
        self::assertTrue(is_file("..\\test\\cats\\cat_1.gif"));

        # Update a file
        $file->setSize(200);
        $time = new \DateTime();
        $file->setModifiedTime($time);
        $fileSystem->updateFile($file);
        self::assertEquals($file->getModifiedTime(), $time);
        self::assertEquals($file->getSize(), 200);

        # Rename a file
        $fileSystem->renameFile($file, "cute_cat.gif");
        self::assertTrue(is_file("..\\test\\cats\\cute_cat.gif"));

//        # Delete a file
//        $fileSystem->deleteFile($file);
//        self::assertTrue(!is_file("..\\test\\cats\\cute_cat.gif"));
    }

    public function test_create_root_directory()
    {
        $dir = new Folder();
        $dir->setPath("..\\test\\test_root_dir");
        $fileSystem = new FileSystem();
        $fileSystem->createRootDirectory($dir);
        self::assertTrue(is_dir("..\\test\\test_root_dir\\"));
    }

    public function test_create_directory()
    {
        $dir = new Folder();
        $dir->setPath("..\\test\\test_root_dir\\");
        $dir->setName("end");
        $fileSystem = new FileSystem();
        $fileSystem->createDirectory($dir);
        self::assertTrue(is_dir("..\\test\\test_root_dir\\end\\"));
    }

    public function test_rename_directory()
    {
        $dir = new Folder();
        $dir->setPath("..\\test\\test_root_dir\\");
        $dir->setName("end");
        $fileSystem = new FileSystem();
        $fileSystem->renameDirectory($dir, "rename_test");
        self::assertTrue(is_dir("..\\test\\test_root_dir\\rename_test\\"));
    }

    public function test_directory_count()
    {
        $dir = new Folder();
        $dir->setPath("..\\");
        $dir->setName("test");
        $fileSystem = new FileSystem();
        self::assertEquals($fileSystem->getDirectoryCount($dir), 3);
    }

    public function test_file_count()
    {
        $dir = new Folder();
        $dir->setPath("..\\");
        $dir->setName("test");
        $fileSystem = new FileSystem();
        self::assertEquals($fileSystem->getFileCount($dir), 1);
    }

    public function test_get_directory_size()
    {
        $dir = new Folder();
        $dir->setPath("..\\");
        $dir->setName("test");
        $fileSystem = new FileSystem();
        self::assertEquals($fileSystem->getDirectorySize($dir), 17529);
    }

    public function test_get_directories()
    {
        $dir = new Folder();
        $dir->setPath("..\\");
        $dir->setName("test");
        $fileSystem = new FileSystem();
        self::assertContains("..\\test", $fileSystem->getDirectories($dir));
    }

    public function test_get_files()
    {
        $dir = new Folder();
        $dir->setPath("..\\");
        $dir->setName("test");
        $fileSystem = new FileSystem();
        self::assertContains("..\\test\\cats\\cute_cat.gif", $fileSystem->getFiles($dir));
    }

//    public function test_delete_directory()
//    {
//        $dir = new Folder();
//        $dir->setPath("..\\");
//        $dir->setName("test");
//        $fileSystem = new FileSystem();
//        $fileSystem->deleteDirectory($dir);
//        self::assertTrue(!is_dir("../test"));
//    }
}
