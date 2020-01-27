<?php

namespace Bodak\CheckoutCustomForm\Model\CustomFields;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;
use Magento\Framework\Validator\AbstractValidator;
use Zend_Validate_Exception;
use Zend\Filter\Word\UnderscoreToCamelCase;


class Validator extends AbstractValidator
{

    /**
     * @var CustomFieldsInterface
     */
    private $value;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var UnderscoreToCamelCase
     */
    private $underscoreToCamelCase;

    /**
     * Validator constructor.
     *
     * @param Config $config
     * @param UnderscoreToCamelCase $underscoreToCamelCase
     */
    public function __construct(Config $config, UnderscoreToCamelCase $underscoreToCamelCase)
    {
        $this->config = $config;
        $this->underscoreToCamelCase = $underscoreToCamelCase;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value
     *
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        $valid = true;
        if (!($value instanceof CustomFieldsInterface)) {
            throw new Zend_Validate_Exception('Expected value to be instance of \Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface');
        }

        $this->setValue($value);

        foreach (CustomFieldsInterface::ATTRIBUTES as $attribute) {
            if (!$this->lengthIsValid($attribute)) {
                $valid = false;
                $this->_addMessages([sprintf('Field %s is to long.', $attribute)]);
            }
        }

        return $valid;
    }

    /**
     * @param $value
     */
    private function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param $attribute
     *
     * @return bool
     */
    private function lengthIsValid($attribute)
    {
        $allowedLength = $this->config->getAllowedLength($attribute);

        if ($allowedLength === Config::LIMIT_NOT_SET) {
            return true;
        }

        $function = $this->convertSnakeToCamelCase('get_' . $attribute);
        $value = call_user_func([$this->value, $function]);

        return mb_strlen($value) <= $allowedLength;
    }

    /**
     * @param $string
     *
     * @return string
     */
    private function convertSnakeToCamelCase($string)
    {
        return $this->underscoreToCamelCase->filter($string);
    }
}