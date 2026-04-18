# Changelog

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-04-18

### Breaking Changes
- Moved all module source code into `src/` directory; only `composer.json`, `LICENSE`, `README.md`,
  `docs/`, and `CHANGELOG.md` remain in the repository root.
- Replaced deprecated `Setup/InstallData.php` with `Setup/Patch/Data/AddCustomFields.php`
  (Magento 2.3+ declarative data-patch API).
- Removed `setup_version` from `etc/module.xml` (no longer required in Magento 2.4).
- Setter and getter signatures now use proper `?string` nullable types and return types.

### Added
- **Magento 2.4.8 compatibility** — tested against M2.4.7 / M2.4.8.
- **PHP 8.1 – 8.4 support** in `composer.json`.
- **Admin configuration** (`etc/adminhtml/system.xml` + `etc/config.xml`): enable/disable
  individual form fields per store view and optionally set per-field character limits.
- **`Helper/Config`** — centralises access to `bodak/checkout/*` store configuration.
- **`Model/Checkout/LayoutProcessor/Plugin`** — injects enabled fields into the checkout JS layout
  at runtime, replacing the hardcoded `checkout_index_index.xml` field definitions.
- **`Model/Config/Source/Option`** — multiselect source model for the admin field selector.
- **`Model/CustomFields/Validator`** — validates field lengths against configured limits before
  persisting to the quote; uses `Laminas\Filter\Word\UnderscoreToCamelCase`.
- **`Observer/Sales/OrderLoadAfter`** — populates Sales Order extension attributes after load
  so custom fields are available via the REST API response.
- **`etc/extension_attributes.xml`** — declares extension attributes on `OrderInterface` for all
  five custom fields.
- **`etc/events.xml`** — added `sales_order_load_after` observer.
- **`etc/di.xml`** — added `LayoutProcessor` plugin registration.
- **`i18n/de_DE.csv`** — German translations.
- **Custom textarea template** (`view/frontend/web/template/form/element/textarea.html`).
- **Unit test suite** (`tests/Unit/`) covering `CustomFields`, `Config`, `Validator`, and
  `AddCustomFieldsToOrder`.

### Changed
- `Observer/AddCustomFieldsToOrder` — only copies fields that are enabled in admin config
  (previously always copied all fields).
- Frontend and admin templates now conditionally render only enabled fields via
  `isCheckoutXxxEnabled()` methods.
- `checkout_index_index.xml` simplified: field definitions are now managed entirely by the
  `LayoutProcessor` plugin rather than XML items.
- Dependency declarations in `composer.json` now include `magento/module-checkout`,
  `magento/module-quote`, `magento/module-sales`, and `magento/module-store`.
- Templates updated from deprecated `/* @escapeNotVerified */` comments to `$block->escapeHtml()`
  short syntax (`<?= ?>`).
- Module sequence in `etc/module.xml` extended to include `Magento_Checkout`, `Magento_Quote`,
  and `Magento_Store`.

### Fixed
- `Model/Data/CustomFields` now correctly calls `parent::__construct()` on
  `AbstractExtensibleObject`.

---

## [1.2.1] - Previous release

- Magento 2.3 compatibility.

## [1.1.*] - Legacy

- Magento 2.1.x – 2.2.x support (no longer maintained).
