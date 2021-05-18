<?php

namespace Tsc\CatStorageSystem;

require_once("../classes/File.class.php");
require_once("../classes/Folder.class.php");
require_once("../classes/FileSystem.class.php");

use File;
use FileSystem;
use Folder;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase {

    private $root;

    private $fileSystem;

    protected function setUp(): void
    {
        $this->fileSystem = new FileSystem();
        $this->root = vfsStream::setup('root', 444);
    }

    public function create_sample_file(string $fileName)
    {
        $fileDir = new Folder();
        $fileDir->setPath("..\\");
        $fileDir->setName("images");

        $file = new File();
        $file->setParentDirectory($fileDir);
        $file->setName($fileName);

        return $file;
    }

    public function create_save_directory(string $saveDirName)
    {
        $saveDir = new Folder();
        $saveDir->setPath($this->root->url());
        $saveDir->setName(DIRECTORY_SEPARATOR . $saveDirName . DIRECTORY_SEPARATOR);
        return $saveDir;
    }

    public function test_file_create()
    {
        $file = $this->create_sample_file("cat_1.gif");
        $this->fileSystem->createFile($file, $this->create_save_directory("cats"));
        $this->assertTrue($this->root->hasChild('cats/cat_1.gif'));
    }

    public function test_file_update(){

        $file = $this->create_sample_file("cat_1.gif");
        $this->fileSystem->createFile($file, $this->create_save_directory("cats"));

        $file->setSize(200);
        $time = new \DateTime();
        $file->setModifiedTime($time);
        $this->fileSystem->updateFile($file);
        self::assertEquals($file->getModifiedTime(), $time);
        self::assertEquals($file->getSize(), 200);

    }

    public function test_file_rename()
    {
        $file = $this->create_sample_file("cat_1.gif");
        $this->fileSystem->createFile($file, $this->create_save_directory("cats"));
        $this->fileSystem->renameFile($file, "new_name.gif");
        $this->assertTrue($this->root->hasChild('cats/new_name.gif'));
    }

    public function test_file_delete()
    {
        $file = $this->create_sample_file("cat_1.gif");
        $this->fileSystem->createFile($file, $this->create_save_directory("cats"));
        $fileDeletion = $this->fileSystem->deleteFile($file);
        self::assertEquals(1, $fileDeletion);
        $this->assertTrue(!$this->root->hasChild('cats/cat_1.gif'));
    }

    public function test_create_root_directory()
    {
        $dir = $this->create_save_directory("cat");
        $dir->setPath($this->root->url() . "/cat");
        echo $dir->getPath();
        $this->fileSystem->createRootDirectory($dir);
        $this->assertTrue($this->root->hasChild('cat'));
    }

    public function test_create_directory()
    {
        $dir = $this->create_save_directory("cat");
        $this->fileSystem->createDirectory($dir);
        $this->assertTrue($this->root->hasChild('cat'));
    }

    public function test_rename_directory()
    {
        $dir = $this->create_save_directory("cat");
        $this->fileSystem->createDirectory($dir);
        $this->fileSystem->renameDirectory($dir, "/rename_test");
        $this->assertTrue($this->root->hasChild('rename_test'));
    }

    public function test_directory_count()
    {
        $dir = $this->create_save_directory("cat");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/cat2");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/test");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/");
        self::assertEquals($this->fileSystem->getDirectoryCount($dir), 3);
    }

    public function test_file_count()
    {
        $file = $this->create_sample_file("cat_1.gif");
        $dir = $this->create_save_directory("cats");
        $this->fileSystem->createFile($file, $dir);
        $file = $this->create_sample_file("cat_2.gif");
        $this->fileSystem->createFile($file, $dir);
        $file = $this->create_sample_file("cat_3.gif");
        $this->fileSystem->createFile($file, $dir);
        self::assertEquals($this->fileSystem->getFileCount($dir), 3);
    }

    public function test_get_directory_size()
    {
        $file = $this->create_sample_file("cat_1.gif");
        $dir = $this->create_save_directory("cats");
        $this->fileSystem->createFile($file, $dir);
        $file = $this->create_sample_file("cat_2.gif");
        $this->fileSystem->createFile($file, $dir);
        $file = $this->create_sample_file("cat_3.gif");
        $this->fileSystem->createFile($file, $dir);
        self::assertEquals($this->fileSystem->getDirectorySize($dir), 21230642);
    }

    public function test_get_directories()
    {
        $dir = $this->create_save_directory("cat\\main");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/cat2");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/test");
        $this->fileSystem->createDirectory($dir);
        self::assertContains("/test", $this->fileSystem->getDirectories($dir));
    }

    public function test_get_files()
    {
        $file = $this->create_sample_file("cat_1.gif");
        $dir = $this->create_save_directory("cats");
        $this->fileSystem->createFile($file, $dir);
        $file = $this->create_sample_file("cat_2.gif");
        $this->fileSystem->createFile($file, $dir);
        $file = $this->create_sample_file("cat_3.gif");
        $this->fileSystem->createFile($file, $dir);
        self::assertContains("vfs://root\\cats\\cat_1.gif", $this->fileSystem->getFiles($dir));
        self::assertContains("vfs://root\\cats\\cat_2.gif", $this->fileSystem->getFiles($dir));
        self::assertContains("vfs://root\\cats\\cat_3.gif", $this->fileSystem->getFiles($dir));
    }

    public function test_delete_directory()
    {
        $dir = $this->create_save_directory("/cat");
        $this->fileSystem->createDirectory($dir);
        $this->fileSystem->deleteDirectory($dir);
        $this->assertTrue(!$this->root->hasChild('/cat'));
    }
}
