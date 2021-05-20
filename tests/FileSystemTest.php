<?php

namespace Tsc\CatStorageSystem\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Tsc\CatStorageSystem\File;
use Tsc\CatStorageSystem\FileSystem;
use Tsc\CatStorageSystem\Directory;

class FileSystemTest extends TestCase {

    private \org\bovigo\vfs\vfsStreamDirectory $root;
    private FileSystem $fileSystem;

    protected function setUp(): void
    {
        $this->fileSystem = new FileSystem();
        $this->root = vfsStream::setup('root', 444);
    }

    public function create_sample_file(string $fileName)
    {
        $fileDir = new Directory();
        $fileDir->setPath("..\\");
        $fileDir->setName("images");
        $file = new File();
        $file->setParentDirectory($fileDir);
        $file->setName($fileName);
        return $file;
    }

    public function create_save_directory(string $saveDirName)
    {
        $saveDir = new Directory();
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
        self::assertEquals($time, $file->getModifiedTime());
        self::assertEquals(200, $file->getSize());

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
        $dir->setName("/cat2/");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/test/");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/test2/");
        $dir->setPath($this->root->url() . "\\cat3\\base");
        $this->fileSystem->createDirectory($dir);
        $dir->setPath($this->root->url());
        $dir->setName("/");
        self::assertEquals(6, $this->fileSystem->getDirectoryCount($dir));
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
        self::assertEquals(3, $this->fileSystem->getFileCount($dir));
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
        self::assertEquals(21230642, $this->fileSystem->getDirectorySize($dir));
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
        $arrayValues = $this->fileSystem->getFiles($dir);
        self::assertEquals("cat_1.gif", $arrayValues[0]->getName());
        self::assertEquals("cat_2.gif", $arrayValues[1]->getName());
        self::assertEquals("cat_3.gif", $arrayValues[2]->getName());
    }

    public function test_get_directories()
    {
        $dir = $this->create_save_directory("cat");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("\\cat2\\");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("\\test\\");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("\\test2\\");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/");
        $arrayValues = $this->fileSystem->getDirectories($dir);
        self::assertEquals("cat\\", $arrayValues[0]->getName());
        self::assertEquals("cat2\\", $arrayValues[1]->getName());
        self::assertEquals("test\\", $arrayValues[2]->getName());
        self::assertEquals("test2\\", $arrayValues[3]->getName());
    }

    public function test_delete_directory()
    {
        $dir = $this->create_save_directory("cat");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/cat2/");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/test/");
        $this->fileSystem->createDirectory($dir);
        $dir->setName("/test2/");
        $dir->setPath($this->root->url() . "\\cat3\\base");
        $this->fileSystem->createDirectory($dir);
        $dir->setPath($this->root->url());
        $dir->setName("/");
        $this->fileSystem->deleteDirectory($dir);
        $this->assertFileDoesNotExist($this->root->url() . "cat");
    }
}
