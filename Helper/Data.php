<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_ProductAttachments
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\ProductAttachments\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Helper Class
 */
class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'product_attachments/general/enabled';
    const XML_PATH_ATTACHMENT_TAB_LABEL = 'product_attachments/general/tab_label';
    const XML_PATH_ATTACHMENT_ALLOWED_EXTENSIONS = 'product_attachments/general/allowed_extensions';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_WEB,
            true
        );
    }

    /**
     * @param $xmlPath
     * @param $storeId
     * @return bool
     */
    public function getConfigFlag($xmlPath, $storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $xmlPath
     * @param $storeId
     * @return string
     */
    public function getConfigValue($xmlPath, $storeId = null)
    {
        return (string)$this->scopeConfig->getValue(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return $this->getConfigFlag(self::XML_PATH_ENABLED, $storeId);
    }

    /**
     * @param $storeId
     * @return string
     */
    public function getAttachmentTabLabel($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_ATTACHMENT_TAB_LABEL, $storeId);
    }

    /**
     * @param $storeId
     * @return string[]
     */
    public function getAttachmentAllowedExtensions($storeId = null)
    {
        $value = $this->getConfigValue(self::XML_PATH_ATTACHMENT_ALLOWED_EXTENSIONS, $storeId);
        return explode(',', $value);
    }
}
