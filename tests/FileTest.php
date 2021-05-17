<?php

namespace Tsc\CatStorageSystem;

require_once("../classes/File.class.php");
require_once("../classes/Folder.class.php");

use File;
use Folder;
use PHPUnit\Framework\TestCase;

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
        $dir = new Folder();
        $dir->setPath("test\\test2\\");
        $dir->setName("end");
        $file->setParentDirectory($dir);
        $this->assertEquals("test\\test2\\end\\", $file->getPath());
        $this->assertEquals("test\\test2\\end\\file.gif", $file->getPath() . $file->getName());
    }
}
