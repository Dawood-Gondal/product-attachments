<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_ProductAttachments
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\ProductAttachments\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Model Class AttachmentResolver
 */
class AttachmentResolver
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var File
     */
    private $file;

    /**
     * @param StoreManagerInterface $storeManager
     * @param File $file
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        File $file
    ) {
        $this->storeManager = $storeManager;
        $this->file = $file;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getAttachments(Product $product)
    {
        $attachments = [];
        if ($this->isValidFile($product->getData('m2c_attachment_file'))) {
            $attachments[] = $this->prepareAttachment(
                $product->getData('m2c_attachment_label'),
                $product->getData('m2c_attachment_file')
            );
        }
        return $attachments;
    }

    /**
     * @param $label
     * @param $file
     * @return array
     */
    private function prepareAttachment($label, $file)
    {
        return [
            'label' => $this->getAttachmentLabel($label),
            'link' => $this->getAttachmentUrl($file)
        ];
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param $file
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function isValidFile($file)
    {
        if (!strlen($file ?? '')) {
            return false;
        }
        return $this->file->isFile($this->getAttachmentPath($file));
    }

    /**
     * @param $file
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAttachmentPath($file)
    {
        return $this->storeManager->getStore()->getBaseMediaDir(). '/catalog/product/attachment' . $file;
    }

    /**
     * @param $file
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAttachmentUrl($file)
    {
        return $this->getMediaUrl() . 'catalog/product/attachment' . $file;
    }

    /**
     * @param $label
     * @return string
     */
    public function getAttachmentLabel($label)
    {
        return $label ?? 'Download';
    }
}
