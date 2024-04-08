# Veliva_CustomerLogger module

The purpose of this module is to log with the custom logger solution, once the frontend customer or backend user logs in, logs out or fails to log in.

### Frontend customer related logging
Customer login and logout logging relies on Magento's customer related events:
- `customer_login` event is used to log customer login entries
- `customer_logout` event is used to log customer logout entries

The logged row includes date, time, customer ID and the event name.

The customer failing to log in detection relies on an after plugin for the `\Magento\Customer\Controller\Account\LoginPost::execute`. The original method is used for the login page post request. The plugin checks, if after the authentication process the user is logged in. If they are not logged in, the plugin logs according row and includes the email, which was used for the login attempt.

### Backend user related logging
Backend login and login failed logging relies on Magento's backend user related events:
- `backend_auth_user_login_success` event is used to log admin user login entries
- `backend_auth_user_login_failed` event is used to log admin user logout entries

The logged row includes date, time, admin user ID and the event name.

The admin user logging out detection relies on a before plugin for the `Magento\Backend\Model\Auth\Session::processLogout`. Since that method logs admin user out, there is no additional logic involved. It just gets the admin user ID and logs the according row.

## Admin configuration

This module does not have any admin configuration, the functionality starts working once the module is enabled.

## Dependencies

This module needs the `Veliva_ConfigurableLogger` module in order to work.

## Log output example

Log output example with all previously pointed out situations:
```
[2024-04-08T17:28:00.673478+00:00] ConfigurableLogger.INFO: Backend User ID: 1 - backend_auth_user_login_success [] []
[2024-04-08T17:28:05.236242+00:00] ConfigurableLogger.INFO: Backend User ID: 1 - backend_auth_user_logout [] []
[2024-04-08T17:28:13.074143+00:00] ConfigurableLogger.INFO: Backend User ID: veli - backend_auth_user_login_failed [] []
[2024-04-08T17:28:16.330152+00:00] ConfigurableLogger.INFO: Backend User ID: veli2 - backend_auth_user_login_failed [] []
[2024-04-08T17:28:31.989957+00:00] ConfigurableLogger.INFO: Frontend Customer ID: 2 - customer_login [] []
[2024-04-08T17:28:36.560916+00:00] ConfigurableLogger.INFO: Frontend Customer ID: 2 - customer_logout [] []
[2024-04-08T17:28:49.403829+00:00] ConfigurableLogger.INFO: Frontend Customer login failed, email: vv@vv.com [] []
[2024-04-08T17:28:55.727116+00:00] ConfigurableLogger.INFO: Frontend Customer login failed, email: vv2@vv.com [] []
```
