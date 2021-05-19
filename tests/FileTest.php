<?php

namespace Tsc\CatStorageSystem\Tests;

use PHPUnit\Framework\TestCase;
use Tsc\CatStorageSystem\File;
use Tsc\CatStorageSystem\Directory;

class FileTest extends TestCase {

    public function test_name(){
        $file = new File();
        $file->setName("test.gif");
        $this->assertEquals("test.gif", $file->getName());
    }

    public function test_created_date_time(){
        $file = new File();
        $time = new \DateTime();
        $file->setCreatedTime($time);
        $this->assertEquals($time, $file->getCreatedTime());
    }

    public function test_modified_date_time(){
        $file = new File();
        $time = new \DateTime();
        $file->setModifiedTime($time);
        $this->assertEquals($time, $file->getModifiedTime());
    }

    public function test_parent_directory(){
        $file = new File();
        $file->setName("file.gif");
        $dir = new Directory();
        $dir->setPath("test\\test2\\");
        $dir->setName("end");
        $file->setParentDirectory($dir);
        $this->assertEquals("test\\test2\\end\\", $file->getPath());
        $this->assertEquals("test\\test2\\end\\file.gif", $file->getPath() . $file->getName());
    }
}
