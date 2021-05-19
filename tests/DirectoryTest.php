<?php

namespace Tsc\CatStorageSystem\Tests;

use PHPUnit\Framework\TestCase;
use Tsc\CatStorageSystem\Directory;

class DirectoryTest extends TestCase {

    public function test_name(){
        $folder = new Directory();
        $folder->setName("test");
        $this->assertEquals("test", $folder->getName());
    }

    public function test_created_date_time(){
        $folder = new Directory();
        $time = new \DateTime();
        $folder->setCreatedTime($time);
        $this->assertEquals($time, $folder->getCreatedTime());
    }

    public function test_path(){
        $folder = new Directory();
        $folder->setPath("test\\test2\\");
        $this->assertEquals("test\\test2\\", $folder->getPath());
    }

    public function test_full_path(){
        $folder = new Directory();
        $folder->setPath("test\\test2\\");
        $folder->setName("test");
        $this->assertEquals("test\\test2\\test", $folder->getPath() . $folder->getName());
    }
}
