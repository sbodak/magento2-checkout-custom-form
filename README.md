# Magento 2 - Checkout custom form
 
## Overview
Add a custom form fields to Magento 2 checkout. The form will appear in the first checkout step (shipping step).
The form is available for logged in customers and guests. After an order is placed all data are set in `sales_order` table.
Data are still in form after page refreshed, till cart is active.

Form data will be set in a `quota` table through independent API request:
- `/V1/carts/mine/set-order-custom-fields` (for logged in customer)
- `/V1/guest-carts/:cartId/set-order-custom-field`  (for guest)

## Compatibility
- Magento 2.1.x - 2.2.x

## Requirements
- PHP 7.0 or higher

## Installation details
1. Run `composer require sbodak/magento2-checkout-custom-form`
2. Run `php bin/magento module:enable Bodak_CheckoutCustomForm`
3. Run `php bin/magento setup:upgrade`

### Sample custom form fields
- buyer
- buyer email address
- purchase order no.
- goods mark
- comments

### Modify form fields
- You need to modify service contract data interface in `Api/Data/CustomFieldsInterfaces.php`
- You need to modify table schema in `Setup/InstallData.php`
- You need to add new fields to observer `Observer/AddCustomFieldsToOrder.php` which save data in quota and sales table
- You need to add new item in `view/frontend/layout/checkout_index_index.xml`
- You need to modify the methods in `Model/Data/CustomFields.php`
- You need to modify the methods in `Model/CustomFieldsRepository.php`

```
<item name="custom-checkout-form-fieldset" xsi:type="array">
    <item name="component" xsi:type="string">uiComponent</item>
    <item name="displayArea" xsi:type="string">custom-checkout-form-fields</item>
    <item name="children" xsi:type="array">
        [... place here new definition of your field]
    </item>
</item>
```

Check official documentation: http://devdocs.magento.com/guides/v2.0/howdoi/checkout/checkout_form.html

- You need to modify template views in `view/frontend/templates/order/view/custom_fields.phtml` (for customer account) and `view/adminhtml/templates/order/view/custom_fields.phtml (for admin panel)`.
- Checkout form view is generated automatically using Ui Components


### Checkout view - custom form
![Checkout frontend custom form](docs/frontened_checkout_custom_form.png)

### Custom account - Order history view
![Customer account - Order history view](docs/frontend_customer_account_orders.png)

### Admin panel - Order Edit
![Admin panel - order edit](docs/backend_order_custom_information.png)


## Uninstall
To remove this module run `php bin/magento module:uninstall Bodak_CheckoutCustomForm`.
It will remove all data and drop columns in `sales_order` and `quote` tables.

## License
[MIT License](LICENSE)