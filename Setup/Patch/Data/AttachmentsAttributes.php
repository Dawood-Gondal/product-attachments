<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_ProductAttachments
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\ProductAttachments\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Validator\ValidateException;

/**
 * Attributes creator class
 */
class AttachmentsAttributes implements DataPatchInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $setup;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $setup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $setup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->setup = $setup;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException|ValidateException
     */
    public function apply()
    {
        $this->setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);

        $attributes = [
            'm2c_attachment_file' => [
                'label' => 'Attachment File',
                'input' => 'file',
                'backend' => 'M2Commerce\ProductAttachments\Model\Product\Attribute\Backend\File',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            ],
            'm2c_attachment_label' => [
                'label' => 'Attachment Label',
                'input' => 'text',
                'backend' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
            ],
        ];

        foreach ($attributes as $code => $attribute)
        {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $code,
                [
                    'group' => 'Attachments',
                    'type' => 'varchar',
                    'label' => $attribute['label'],
                    'input' => $attribute['input'],
                    'backend' => $attribute['backend'],
                    'global' => $attribute['global'],
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'unique' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                ]
            );
        }
        $this->setup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
