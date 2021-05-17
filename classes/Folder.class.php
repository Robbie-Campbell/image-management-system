<?php

require_once("../src/DirectoryInterface.php");

use Tsc\CatStorageSystem\DirectoryInterface;

/**
 * Class Folder
 *
 * The class that handles the directory methods
 */
class Folder implements DirectoryInterface
{

    private string $name;
    private DateTimeInterface $created;
    private string $path;

    /**
     * Folder constructor.
     */
    public function __construct()
    {
        $this->setCreatedTime(new DateTime());
    }

    /**
     * Get the final sub-directory.
     *
     * @return string The last directory
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the final sub-directory.
     *
     * @param string $name The final sub-directory
     * @return Folder|void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the time that the folder was created.
     *
     * @return DateTimeInterface|false|string The string value of the created time of the folder
     */
    public function getCreatedTime()
    {
        return $this->created;
    }

    /**
     * Set the time that the folder was created.
     *
     * @param DateTimeInterface $created The time of creation
     * @return Folder|void
     */
    public function setCreatedTime(DateTimeInterface $created)
    {
        $this->created = $created;
    }

    /**
     * Get the path of the directory.
     *
     * @return string The path of the directory
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path of the directory.
     *
     * @param string $path The path of the directory
     * @return Folder|void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}

