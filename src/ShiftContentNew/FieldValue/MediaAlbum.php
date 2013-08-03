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
 * @subpackage  FieldValue
 */

/**
 * @namespace
 */
namespace ShiftContentNew\FieldValue;
use Doctrine\ORM\Mapping as ORM;
use ShiftContentNew\FieldValue\AbstractFieldValue;
use ShiftContentNew\Exception\DomainException;
use ShiftMedia\Album\Album;

/**
 * MediaItem value
 *
 * @ORM\Entity
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  FieldValue
 */
class MediaAlbum extends AbstractFieldValue
{
    /**
     * Property MediaItem value
     *
     * @ORM\OneToOne(targetEntity="ShiftMedia\Album\Album")
     * @ORM\JoinColumn(name="mediaAlbum", referencedColumnName="id")
     * @var \ShiftMedia\Album\Album
     */
    protected $mediaAlbumValue;


    /**
     * Set value
     * Sets property value of MediaAlbum type
     *
     * @param \ShiftMedia\Album\Album $value
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldValue\MediaAlbum
     */
    public function setValue($value)
    {
        //check value type
        if(!$value instanceof Album)
        {
            $error = 'Media item must be of \ShiftMedia\Album\Album type';
            throw new DomainException($error);
        }

        //check that value is a persisted entity
        if(null == $value->getId())
        {
            $error = 'Item must be persisted before it can be set as value';
            throw new DomainException($error);
        }

        $this->mediaAlbumValue = $value;
        return $this;
    }


    /**
     * Get value
     * Returns current value.
     *
     * @return \ShiftMedia\Album\Album|void
     */
    public function getValue()
    {
        return $this->mediaAlbumValue;
    }


    /**
     * To array
     * Returns an array representation of value.
     *
     * @return array
     */
    public function toArray()
    {
        $value = $this->getValue();
        if($value)
            $value = $value->toArray();

        $property = parent::toArray();
        $property['value'] = $value;
        return $property;
    }
} //class ends here