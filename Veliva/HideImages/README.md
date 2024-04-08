# Veliva_HideImages module

The purpose of this module is to replace catalog product images with placeholder images for users, who are not logged in.

The functionality relies on an after plugin for the `Magento\Catalog\Model\View\Asset\Image::getUrl`. The plugin replaces product images with placeholders only for the front-end area, images are visible on the admin panel.

## Admin configuration

This module does not have any admin configuration, the functionality starts working once the module is enabled.
