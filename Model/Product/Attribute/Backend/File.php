<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_ProductAttachments
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\ProductAttachments\Model\Product\Attribute\Backend;

use M2Commerce\ProductAttachments\Helper\Data;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;

/**
 * Model Class File
 */
class File extends AbstractBackend
{
    /**
     * @var Filesystem\Driver\File
     */
    protected $file;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @param Filesystem\Driver\File $file
     * @param UploaderFactory $fileUploaderFactory
     * @param Data $helper
     */
    public function __construct(
        LoggerInterface $logger,
        Filesystem $filesystem,
        Filesystem\Driver\File $file,
        UploaderFactory $fileUploaderFactory,
        Data $helper
    ) {
        $this->file = $file;
        $this->filesystem = $filesystem;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    public function afterSave($object)
    {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'catalog/product/attachment/'
        );
        $delete = $object->getData($this->getAttribute()->getName() . '_delete');

        if ($delete) {
            $fileName = $object->getData($this->getAttribute()->getName());
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
            if ($this->file->isExists($path . $fileName)) {
                $this->file->deleteFile($path . $fileName);
            }
        }

        if (empty($_FILES['product']['tmp_name'][$this->getAttribute()->getName()])) {
            return $this;
        }

        try {
            $uploader = $this->fileUploaderFactory->create([
                'fileId' => 'product[' . $this->getAttribute()->getName() . ']'
            ]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowedExtensions($this->helper->getAttachmentAllowedExtensions());
            $result = $uploader->save($path);
            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (\Exception $e) {
            if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                $this->logger->critical($e);
            }
        }

        return $this;
    }
}
