<?php

namespace Tsc\CatStorageSystem;

/**
 * Class File
 *
 * This class handles all of the file methods
 */
class File implements FileInterface
{

    private string $name;
    private string $size;
    private \DateTimeInterface $created;
    private \DateTimeInterface $modified;
    private DirectoryInterface $parentDir;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->setCreatedTime(new \DateTime());
    }

    /**
     * Returns the name of this file.
     *
     * @return string The name of the file
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of this file
     *
     * @param string $name The new name of the file.
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the size of the image stored in the file.
     *
     * @return int The size of the image provided
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the size of the image in the file.
     *
     * @param int $size The new size of the image.
     * @return File|void
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Gets the time that the file was created.
     *
     * @return \DateTimeInterface Get the time that the file has been created.
     */
    public function getCreatedTime()
    {
        return $this->created;
    }

    /**
     * Set the time of creation for the file
     *
     * @param \DateTimeInterface $created The created time
     * @return File
     */
    public function setCreatedTime(\DateTimeInterface $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get the time that the file has been modified.
     *
     * @return \DateTimeInterface|false|string The modification time
     */
    public function getModifiedTime()
    {
        return $this->modified;
    }

    /**
     * When the file is updated or modified, set this to the current time.
     *
     * @param \DateTimeInterface $modified The time of modification
     * @return File
     */
    public function setModifiedTime(\DateTimeInterface $modified)
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * Get the directory value of this file.
     *
     * @return DirectoryInterface The directory of this file.
     */
    public function getParentDirectory()
    {
        return $this->parentDir;
    }

    /**
     * Set the directory of this file.
     *
     * @param DirectoryInterface $parent The parent directory of this file
     * @return File
     */
    public function setParentDirectory(DirectoryInterface $parent)
    {
        $this->parentDir = $parent;
        return $this;
    }

    /**
     * Gets the relative path to this file.
     *
     * @return string The path to this file
     */
    public function getPath()
    {
        return $this->parentDir->getPath() . $this->parentDir->getName() . "\\";
    }

    public function getFullName()
    {
        return $this->getPath() . $this->getName();
    }
}