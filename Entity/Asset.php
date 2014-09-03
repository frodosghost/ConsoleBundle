<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Entity;

use Manhattan\Bundle\ConsoleBundle\Entity\Publish;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * Manhattan\Bundle\ConsoleBundle\Entity\Asset
 */
abstract class Asset extends Publish
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $mime_type
     */
    protected $mime_type;

    /**
     * @var string $filename
     */
    protected $filename;

    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    protected $file;

    /**
     * Variable for holding delete variable
     */
    private $remove_filename;

    public function __toString()
    {
        return $this->getFilename();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mime_type
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;
    }

    /**
     * Get mime_type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Set filename
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    public function hasAsset()
    {
        return ($this->filename != null || $this->filename != '');
    }

    public function hasFile()
    {
        return ($this->file != null || $this->file != '');
    }


    public function getAbsolutePath()
    {
        return $this->hasAsset() ? $this->getUploadRootDir().'/'.$this->getFilename() : null;
    }

    public function getWebPath()
    {
        return $this->hasAsset() ? $this->getUploadDir().'/'.$this->getFilename() : null;
    }

    private function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    abstract public function getUploadDir();

    /**
     * Cleans a filename for saving in the database
     *
     * @link(Sanitizing strings to make them URL and filename safe? , http://stackoverflow.com/q/2668854/174148)
     * @param  string $filename
     *
     * @return string
     */
    public function sanitise($filename)
    {
        // Remove extension from uploaded file
        $extension = preg_replace('/^.*\./', '', $filename);
        $filename = preg_replace('/\.[^.]*$/', '', $filename);

        $filename = preg_replace('/[^a-zA-Z0-9]/', '-', $filename);
        // Replace all weird characters with dashes
        $filename = preg_replace('/[^\w\-~_\.]+/u', '-', $filename);

        // Only allow one dash separator at a time (and make string lowercase)
        return mb_strtolower(preg_replace('/--+/u', '-', $filename), 'UTF-8') .'.'. $extension;
    }

    /**
     * Returns file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return preg_replace('/^.*\./', '', $this->getFilename());;
    }

    /**
     * PrePersist()
     */
    public function preUpload()
    {
        if (null === $this->getFile()) {
            return;
        }

        try {
            $this->setMimeType($this->file->getMimetype());
        } catch (FileNotFoundException $e) {
            $this->setMimeType($this->file->getClientMimeType());
        }

        // set the path property to the filename where you'ved saved the file
        $filename = $this->sanitise($this->file->getClientOriginalName());
        $this->setFilename($filename);
    }

    /**
     * PreUpdate()
     */
    public function preUpdateAsset()
    {
        if (null === $this->getFile()) {
            return;
        }

        // Store path for removal
        $this->storeFilenameForRemove();

        // Run upload to reset properties for new file
        $this->preUpload();
    }

    /**
     * PostPersist()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        try {
            $this->file->move($this->getUploadRootDir(), $this->getFilename());
        }
        catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Assest is updated and requires image replacement this will replace the current image while retaining
     * the field identifier.
     *
     * PostUpdate()
     */
    public function replace()
    {
        if (null === $this->getFile()) {
            return;
        }

        // Remove existing image
        $this->removeUpload();

        // Upload new image
        $this->upload();

        return true;
    }

    /**
     * PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->remove_filename = $this->getAbsolutePath();
    }

    /**
     * PostRemove()
     */
    public function removeUpload()
    {
        if ($this->remove_filename && is_file($this->remove_filename)) {
            unlink($this->remove_filename);
        }
    }

}
