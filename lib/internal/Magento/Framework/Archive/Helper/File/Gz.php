<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

/**
* Helper class that simplifies gz files stream reading and writing
*/
namespace Magento\Framework\Archive\Helper\File;

class Gz extends \Magento\Framework\Archive\Helper\File
{
    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    protected function _open($mode)
    {
        if (!extension_loaded('zlib')) {
            throw new \RuntimeException('PHP extension zlib is required.');
        }
        $this->_fileHandler = gzopen($this->_filePath, $mode);

        if (false === $this->_fileHandler) {
            throw new \Magento\Framework\Exception('Failed to open file ' . $this->_filePath);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _write($data)
    {
        $result = gzwrite($this->_fileHandler, $data);

        if (empty($result) && !empty($data)) {
            throw new \Magento\Framework\Exception('Failed to write data to ' . $this->_filePath);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _read($length)
    {
        return gzread($this->_fileHandler, $length);
    }

    /**
     * {@inheritdoc}
     */
    protected function _eof()
    {
        return gzeof($this->_fileHandler);
    }

    /**
     * {@inheritdoc}
     */
    protected function _close()
    {
        gzclose($this->_fileHandler);
    }
}
