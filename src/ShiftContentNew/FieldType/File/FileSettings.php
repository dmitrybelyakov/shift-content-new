<?php
/**
 * Projectshift
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file license/projectshift.mit.txt
 * It is also available through the world-wide-web at this URL:
 * http://projectshift.eu/license/mit
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@projectshift.eu so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010 Webcomplex LLC (http://www.projectshift.eu)
 * @license    http://projectshift.eu/license/mit     MIT License
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */

/**
 * @namespace
 */
namespace ShiftContentNew\FieldType\File;

use Doctrine\ORM\Mapping as ORM;
use ShiftContentNew\FieldType\AbstractSettings;

/**
 * File field settings
 * This entity stores settings for a file field.
 *
 * @ORM\Entity
 * @ORM\Table(name="shiftcontentnew_field_settings_file")
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FileSettings extends AbstractSettings
{
    /**
     * Destination
     * Uploaded files will be moved to this location.
     *
     * @ORM\Column(type="string", nullable=false, unique=false)
     * @var string
     */
    protected $destination;


    /**
     * Upload mode
     * Can be either 'uploadToDestination' or 'createFolder'
     *
     * @ORM\Column(type="string", nullable=false, unique=false)
     * @var string
     */
    protected $mode = 'uploadToDestination';


    /**
     * Set destination
     * Sets destination for the uploaded file.
     *
     * @param $destination
     * @return \ShiftContentNew\FieldType\File\FileSettings
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }


    /**
     * Returns destination for the uploaded file.
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }


    /**
     * Set mode
     * Sets upload mode that can be either 'uploadToDestination'
     * or 'createFolder'
     *
     * @param string $mode
     * @return \ShiftContentNew\FieldType\File\FileSettings
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }


    /**
     * Get mode
     * Returns currently set mode
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }


    /**
     * Upload to destination
     * A boolean method to check whether the file should be uploaded to
     * destination.
     *
     * @return bool
     */
    public function uploadToDestination()
    {
        return ($this->mode == 'uploadToDestination');
    }


    /**
     * Create subfolder
     * A boolean method to check whether a directory should be created under
     * destination for the upload.
     *
     * @return bool
     */
    public function createFolder()
    {
        return ($this->mode == 'createFolder');
    }


    /**
     * To array
     * Returns an array representation of settings.
     *
     * @return array
     */
    public function toArray()
    {
        $settings = parent::toArray();
        $settings['destination'] = $this->destination;
        $settings['mode'] = $this->mode;
        return $settings;
    }


} //class ends here