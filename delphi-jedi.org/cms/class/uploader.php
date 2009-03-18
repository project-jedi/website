<?php 
// $Id: uploader.php,v 1.8 2003/02/18 11:36:25 buennagel Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/*!
Example

  include_once 'uploader.php';
  $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
  $maxfilesize = 50000;
  $maxfilewidth = 120;
  $maxfileheight = 120;
  $uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
  if ($uploader->fetchMedia($HTTP_POST_VARS['uploade_file_name'])) {
    if (!$uploader->upload()) {
       echo $uploader->getErrors();
    } else {
       echo '<h4>File uploaded successfully!</h4>'
       echo 'Saved as: ' . $uploader->getSavedFileName() . '<br />';
       echo 'Full path: ' . $uploader->getSavedDestination();
    }
  } else {
    echo $uploader->getErrors();
  }

*/

/**
 * Upload Media files
 * 
 * Example of usage:
 * <code>
 * include_once 'uploader.php';
 * $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
 * $maxfilesize = 50000;
 * $maxfilewidth = 120;
 * $maxfileheight = 120;
 * $uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
 * if ($uploader->fetchMedia($HTTP_POST_VARS['uploade_file_name'])) {
 *    if (!$uploader->upload()) {
 *       echo $uploader->getErrors();
 *    } else {
 *       echo '<h4>File uploaded successfully!</h4>'
 *       echo 'Saved as: ' . $uploader->getSavedFileName() . '<br />';
 *       echo 'Full path: ' . $uploader->getSavedDestination();
 *    }
 * } else {
 *    echo $uploader->getErrors();
 * }
 * </code>
 * 
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org> 
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsMediaUploader
{
    var $mediaName;
    var $mediaType;
    var $mediaSize;
    var $mediaDimention;
    var $mediaTmpName;
    var $mediaError;

    var $uploadDir = '';

    var $allowedMimeTypes = array();

    var $maxFileSize = 0;
    var $maxWidth;
    var $maxHeight;
	var $cmodValue;
    
	var $targetFileName;

    var $prefix;

    var $errors = array();

    var $savedDestination;

    var $savedFileName;

    /**
     * Constructor
     * 
     * @param string $uploadDir 
     * @param array $allowedMimeTypes 
     * @param int $maxFileSize 
     * @param int $maxWidth 
     * @param int $maxHeight 
     */
    function XoopsMediaUploader( $uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth = null, $maxHeight = null, $cmodValue = '0644' )
    {
        if ( is_array( $allowedMimeTypes ) )
        {
            $this -> allowedMimeTypes = & $allowedMimeTypes;
        } 
        $this -> uploadDir = $uploadDir;
        $this -> maxFileSize = intval( $maxFileSize );
        if ( isset( $maxWidth ) )
        {
            $this -> maxWidth = intval( $maxWidth );
        } 
        if ( isset( $maxHeight ) )
        {
            $this -> maxHeight = intval( $maxHeight );
        }
		if ( isset( $cmodValue ) )
        {
            $this -> cmodValue = intval( $cmodValue );
        } 
    } 

    /**
     * Fetch the uploaded file
     * 
     * @param string $media_name Name of the file field
     * @param int $index Index of the file (if more than one uploaded under that name)
     * @global $HTTP_POST_FILES
     * @return bool 
     */
    function fetchMedia( $media_name, $index = null )
    {
        global $HTTP_POST_FILES;
        if ( !isset( $HTTP_POST_FILES[$media_name] ) )
        {
            $this -> setErrors( 'File not found' );
            return false;
        } elseif ( is_array( $HTTP_POST_FILES[$media_name]['name'] ) && isset( $index ) )
        {
            $index = intval( $index );
            $this -> mediaName = $HTTP_POST_FILES[$media_name]['name'][$index];
            $this -> mediaType = $HTTP_POST_FILES[$media_name]['type'][$index];
            $this -> mediaSize = $HTTP_POST_FILES[$media_name]['size'][$index];
            $this -> mediaTmpName = $HTTP_POST_FILES[$media_name]['tmp_name'][$index];
            $this -> mediaError = !empty( $HTTP_POST_FILES[$media_name]['error'][$index] ) ? $HTTP_POST_FILES[$media_name]['errir'][$index] : 0;
        } 
        else
        {
            $media_name = & $HTTP_POST_FILES[$media_name];
            $this -> mediaName = $media_name['name'];
            $this -> mediaType = $media_name['type'];
            $this -> mediaSize = $media_name['size'];
            $this -> mediaTmpName = $media_name['tmp_name'];
            $this -> mediaError = !empty( $media_name['error'] ) ? $media_name['error'] : 0;
        } 
        $this -> errors = array();
        if ( intval( $this -> mediaSize ) < 0 )
        {
            $this -> setErrors( 'Invalid media size' );
            return false;
        } 
        if ( $this -> mediaName == '' )
        {
            $this -> setErrors( 'Invalid media name' );
            return false;
        } 
        if ( $this -> mediaTmpName == 'none' || !is_uploaded_file( $this -> mediaTmpName ) )
        {
            $this -> setErrors( 'No file uploaded' );
            return false;
        } 
        if ( $this -> mediaError > 0 )
        {
            $this -> setErrors( 'Error occurred: Error #' . $this -> mediaError );
            return false;
        } 
        if ( in_array( $this -> mediaType, array( 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png' ) ) )
        {
            if ( isset( $this -> maxWidth ) || isset( $this -> maxHeight ) )
            {
                if ( false === ( $this -> mediaDimention = getimagesize( $this -> mediaTmpName ) ) )
                {
                    $this -> setErrors( 'Invalid image file' );
                    return false;
                } 
            } 
        } 
        return true;
    } 

    /**
     * Set the target filename
     * 
     * @param string $value 
     */
    function setTargetFileName( $value )
    {
        $this -> targetFileName = strval( trim( $value ) );
    } 

    /**
     * Set the prefix
     * 
     * @param string $value 
     */
    function setPrefix( $value )
    {
        $this -> prefix = strval( trim( $value ) );
    } 

    /**
     * Get the uploaded filename
     * 
     * @return string 
     */
    function getMediaName()
    {
        return $this -> mediaName;
    } 

    /**
     * Get the type of the uploaded file
     * 
     * @return string 
     */
    function getMediaType()
    {
        return $this -> mediaType;
    } 

    /**
     * Get the size of the uploaded file
     * 
     * @return int 
     */
    function getMediaSize()
    {
        return $this -> mediaSize;
    } 

    /**
     * Get the temporary name that the uploaded file was stored under
     * 
     * @return string 
     */
    function getMediaTmpName()
    {
        return $this -> mediaTmpName;
    } 

    /**
     * Get the Dimensions of the uploaded file
     * 
     * Returns an array with 4 elements. Index 0 contains the width of the image in pixels. Index 1 contains the height. Index 2 is a flag indicating the type of the image: 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF. These values correspond to the IMAGETYPE constants that were added in PHP 4.3. Index 3 is a text string with the correct height="yyy" width="xxx" string that can be used directly in an IMG tag.
     * 
     * @return array 
     */
    function getMediaDimension()
    {
        return $this -> mediaDimension;
    } 

    /**
     * Get the saved filename
     * 
     * @return string 
     */
    function getSavedFileName()
    {
        return $this -> savedFileName;
    } 

    /**
     * Get the destination the file is saved to
     * 
     * @return string 
     */
    function getSavedDestination()
    {
        return $this -> savedDestination;
    } 

    /**
     * Check the file and copy it to the destination
     * 
     * @return bool 
     */
    function upload()
    {
        if ( $this -> uploadDir == '' )
        {
            $this -> setErrors( 'Upload directory not set' );
            return false;
        } 
        if ( !is_dir( $this -> uploadDir ) )
        {
            $this -> setErrors( 'Failed opening directory: ' . $this -> uploadDir );
        } 
        if ( !is_writeable( $this -> uploadDir ) )
        {
            $this -> setErrors( 'Failed opening directory with write permission: ' . $this -> uploadDir );
        } 
        if ( !$this -> checkMaxFileSize() )
        {
            $this -> setErrors( 'File size too large: ' . $this -> mediaSize );
        } 
        if ( !$this -> checkMaxWidth() )
        {
            $this -> setErrors( 'File width too large: ' . $this -> mediaDimention[0] );
        } 
        if ( !$this -> checkMaxHeight() )
        {
            $this -> setErrors( 'File height too large: ' . $this -> mediaDimention[1] );
        } 
        if ( !$this -> checkMimeType() )
        {
            $this -> setErrors( 'MIME type not allowed: ' . $this -> mediaType );
        } 
        if ( count( $this -> errors ) > 0 )
        {
            return false;
        } 
        if ( !$this -> copyFile() )
        {
            $this -> setErrors( 'Failed uploading file: ' . $this -> mediaName );
            return false;
        } 
        return true;
    } 

    /**
     * Copy the file to its destination
     * 
     * @return bool 
     */
    function copyFile()
    {
        $matched = array();
        if ( !preg_match( "/\.([a-zA-Z0-9]+)$/", $this -> mediaName, $matched ) )
        {
            return false;
        } 
        if ( isset( $this -> targetFileName ) )
        {
            $this -> savedFileName = $this -> targetFileName;
        } elseif ( isset( $this -> prefix ) )
        {
            $this -> savedFileName = uniqid( $this -> prefix ) . '.' . strtolower( $matched[1] );
        } 
        else
        {
            $this -> savedFileName = strtolower( $this -> mediaName );
        } 
        $this -> savedDestination = $this -> uploadDir . '/' . $this -> savedFileName;
        if ( !move_uploaded_file( $this -> mediaTmpName, $this -> savedDestination ) )
        {
            return false;
        } 
        
		echo $this -> cmodValue;
		@chmod( $this -> savedDestination, $this -> cmodValue );
        return true;
    } 

    /**
     * Is the file the right size?
     * 
     * @return bool 
     */
    function checkMaxFileSize()
    {
        if ( $this -> mediaSize > $this -> maxFileSize )
        {
            return false;
        } 
        return true;
    } 

    /**
     * Is the picture the right width?
     * 
     * @return bool 
     */
    function checkMaxWidth()
    {
        if ( !isset( $this -> maxWidth ) )
        {
            return true;
        } 
        if ( $this -> mediaDimention[0] > $this -> maxWidth )
        {
            return false;
        } 
        return true;
    } 

    /**
     * Is the picture the right height?
     * 
     * @return bool 
     */
    function checkMaxHeight()
    {
        if ( !isset( $this -> maxHeight ) )
        {
            return true;
        } 
        if ( $this -> mediaDimention[1] > $this -> maxHeight )
        {
            return false;
        } 
        return true;
    } 

    /**
     * Is the file the right Mime type 
     * 
     * (is there a right type of mime? ;-)
     * 
     * @return bool 
     */
    function checkMimeType()
    {
        if ( count( $this -> allowedMimeTypes ) > 0 && !in_array( $this -> mediaType, $this -> allowedMimeTypes ) )
        {
            return false;
        } 
        else
        {
            return true;
        } 
    } 

    /**
     * Add an error
     * 
     * @param string $error 
     */
    function setErrors( $error )
    {
        $this -> errors[] = trim( $error );
    } 

    /**
     * Get generated errors
     * 
     * @param bool $ashtml Format using HTML?
     * @return array |string    Array of array messages OR HTML string
     */
    function & getErrors( $ashtml = true )
    {
        if ( !$ashtml )
        {
            return $this -> errors;
        } 
        else
        {
            $ret = '';
            if ( count( $this -> errors ) > 0 )
            {
                $ret = '<h4>Media Upload Errors</h4>';
                foreach ( $this -> errors as $error )
                {
                    $ret .= $error . '<br />';
                } 
            } 
            return $ret;
        } 
    } 
} 

?>