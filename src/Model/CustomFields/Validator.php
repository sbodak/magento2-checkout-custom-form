<?php

/**
 * @package   Bodak\CheckoutCustomForm
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\CheckoutCustomForm\Model\CustomFields;

use Bodak\CheckoutCustomForm\Api\Data\CustomFieldsInterface;
use Bodak\CheckoutCustomForm\Helper\Config;
use InvalidArgumentException;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Magento\Framework\Validator\AbstractValidator;

class Validator extends AbstractValidator
{
    private ?CustomFieldsInterface $value = null;

    public function __construct(
        private readonly Config $config,
        private readonly UnderscoreToCamelCase $underscoreToCamelCase
    ) {}

    /**
     * @param mixed $value
     * @throws InvalidArgumentException
     */
    public function isValid($value): bool
    {
        if (!($value instanceof CustomFieldsInterface)) {
            throw new InvalidArgumentException(
                'Expected value to be instance of ' . CustomFieldsInterface::class
            );
        }

        $this->value = $value;
        $this->_clearMessages();
        $valid = true;

        if (!$this->emailIsValid($value->getCheckoutBuyerEmail())) {
            $valid = false;
            $this->_addMessages([(string)__('Buyer email address is not valid.')]);
        }

        foreach (CustomFieldsInterface::ATTRIBUTES as $attribute) {
            if (!$this->lengthIsValid($attribute)) {
                $valid = false;
                $this->_addMessages([sprintf('Field "%s" exceeds the maximum allowed length.', $attribute)]);
            }
        }

        return $valid;
    }

    private function emailIsValid(?string $email): bool
    {
        if ($email === null || $email === '') {
            return true;
        }

        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function lengthIsValid(string $attribute): bool
    {
        $allowedLength = $this->config->getAllowedLength($attribute);

        if ($allowedLength === Config::LIMIT_NOT_SET) {
            return true;
        }

        $getter = $this->underscoreToCamelCase->filter('get_' . $attribute);
        $fieldValue = (string)call_user_func([$this->value, $getter]);

        return mb_strlen($fieldValue) <= $allowedLength;
    }
}
