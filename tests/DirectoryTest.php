<?php

namespace Tsc\CatStorageSystem;

require_once("../classes/Folder.class.php");

use Folder;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase {

    public function test_name(){
        $folder = new Folder();
        $folder->setName("test");
        $this->assertEquals("test", $folder->getName());
    }

    public function test_created_date_time(){
        $folder = new Folder();
        $time = new \DateTime();
        $folder->setCreatedTime($time);
        $this->assertEquals($time, $folder->getCreatedTime());
    }

    public function test_path(){
        $folder = new Folder();
        $folder->setPath("test\\test2\\");
        $this->assertEquals("test\\test2\\", $folder->getPath());
    }

    public function test_full_path(){
        $folder = new Folder();
        $folder->setPath("test\\test2\\");
        $folder->setName("test");
        $this->assertEquals("test\\test2\\test", $folder->getPath() . $folder->getName());
    }
}
