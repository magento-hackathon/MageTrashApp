MageTrashApp
============

Clean uninstallation or deactivation of Magento modules

## Functionality  ##

Provides the capability to fully disable and uninstall Magento extensions

Uninstall features:

1. Will run a sql uninstall script for module (must be called `uninstall.php` and be in sql directory)
2. Will attempt to uninstall using PEAR packaging commands (as in Magento Connect)
3. If not package found then will use uninstall file specified in module `config.xml` (by default, it is `etc/uninstall.txt`)

Install script features:

1. Delete core_resource to force Magento to run install/upgrade scripts.
2. Rewind core_resource to force Magento to run some install/upgrade scripts.


## Instructions ##

1. Install module (modman file provided)
2. Refresh cache and re-sign into Magento Admin
3. Under `System > Configuration > Advanced` you will see MageTrashApp
4. For each module you have options to enable, disable, Uninstall
5. For each module you have options to delete or rewind `core_resource`



## For Developers of modules ##
Place a file `uninstall.txt` into the folder `etc/`  of your module to allow to be triggered by this module when you uninstall it.
If you wish to change the name of this `uninstall.txt` file to something different, just set into the `config.xml` file of your module, the following:

    <config>
        ...
        <uninstall>
            <filename>myuninstallfile.txt</filename>
        </uninstall>
    </config>

The format of the content should start from the Magento root path. For example: you want to uninstall the module `Namespace_Mymodule` placed into the community code pool.
Just add the following lines to the file:

    app/code/community/Namespace/Mymodule
    app/etc/modules/Namespace_Mymodule.xml
    js/mynamespace/
    skin/frontend/base/default/images/mynapespace

If you have modman, you can copy the modman file into the `etc` folder of your module (app/code/.../Mynamespace/Mymodule/etc/) and rename it to `uninstall.txt` file. In this case, the second part of each line will be taken to uninstall your module.
For example:

    src/app/code/community/Namespace/Mymodule 	app/code/community/Namespace/Mymodule
    src/app/etc/modules/Namespace_Mymodule.xml 	app/etc/modules/Namespace_Mymodule.xml

## Further Information

### Core Contributors

* Tom Kadwill
* Sylvain Rayé
* wsakaren
* Damian Luszczymak

### Current Status of Project

Complete and working.
