<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_ProductAttachments
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\ProductAttachments\Block\Product\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use M2Commerce\ProductAttachments\Model\AttachmentResolver;

/**
 * Block Class Attachment
 */
class Attachment extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var AttachmentResolver
     */
    private $attachmentResolver;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param AttachmentResolver $attachmentResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AttachmentResolver $attachmentResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->attachmentResolver = $attachmentResolver;
    }

    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachmentResolver->getAttachments($this->getProduct());
    }

    /**
     * @return mixed|null
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }
}
