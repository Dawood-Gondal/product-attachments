<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_ProductAttachments
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\ProductAttachments\Model\Config\Source;

/**
 * Class FileType
 */
class FileType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var
     */
    protected $_options;

    /**
     * @param $withEmpty
     * @return mixed
     */
    public function getAllOptions($withEmpty = false)
    {
        if ($this->_options === null) {
            foreach ($this->getTypes() as $type) {
                $this->_options[] = ['value' => $type, 'label' => '.' . $type];
            }
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, ['value' => '', 'label' => '']);
        }
        return $options;
    }

    public function getTypes()
    {
        return ['pdf', 'doc', 'docx', 'ppt', 'txt', 'jpg', 'jpeg', 'gif', 'png',  'zip', 'tar', 'gz'];
    }

    /**
     * @param $withEmpty
     * @return array
     */
    public function getOptionsArray($withEmpty = true)
    {
        $options = [];
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * @param $value
     * @return false|mixed
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);
        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * @param $withEmpty
     * @return array
     */
    public function toOptionHash($withEmpty = true)
    {
        return $this->getOptionsArray($withEmpty);
    }
}
