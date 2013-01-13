MageTrashApp
============

Clean uninstallation or deactivation of Magento modules

## Functionality  ##

Provides the capability to fully disable and uninstall Magento extensions

Uninstall features:

1. Will run a sql uninstall script for module (must be called uninstall.php and be in sql directory)
2. Will attempt to uninstall using PEAR packaging commands (as in Magento Connect)
3. If not package found then will use uninstall file specified in module config.xml


## Instructions ##

1. Install module (modman file provided)
2. Refresh cache and re-sign into Magento Admin
3. Under System->Configuration->Advanced you will see MageTrashApp
4. For each module you have options to enable, disable, Uninstall




