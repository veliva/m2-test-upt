# Veliva_ConfigurableLogger module

The purpose of this module is to create a logger with configurable filename, that can be modified from the admin configuration.

The configurable filename is set for the logger handler during the class initialization.

## Admin configuration

Admin configuration location: **Stores -> (Settings) Configuration -> Veliva -> Logger**

This module does create a new configuration tab `Veliva` and a `Logger` section under it. There are two fields that can be configured: 
- `Enabled` - can be used to turn the logging functionality on and off
- `Filename` - text field, which declares the file name for the logging. The log file is created to the `magento_root/var/log/` directory

Both fields are configurable per store view. There's also an `acl` configuration created for the `Logger` section. That allows the section's accessibility to be modified through the user roles.
