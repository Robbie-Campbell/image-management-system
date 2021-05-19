<?php

namespace Tsc\CatStorageSystem;

/**
 * Class Folder
 *
 * The class that handles the directory methods
 */
class Directory implements DirectoryInterface
{

    private string $name;
    private \DateTimeInterface $created;
    private string $path;

    /**
     * Folder constructor.
     */
    public function __construct()
    {
        $this->setCreatedTime(new \DateTime());
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
     * @return Directory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the time that the folder was created.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedTime()
    {
        return $this->created;
    }

    /**
     * Set the time that the folder was created.
     *
     * @param \DateTimeInterface $created The time of creation
     * @return Directory
     */
    public function setCreatedTime(\DateTimeInterface $created)
    {
        $this->created = $created;
        return $this;
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
     * @return Directory
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Gets the path and name of the directory
     *
     * @return string path and name of dir.
     */
    public function getFullPath()
    {
        return $this->getPath() . $this->getName();
    }
}

