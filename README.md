<a href="http://www.ffuenf.de" title="ffuenf - code • design • e-commerce"><img src="https://github.com/ffuenf/Ffuenf_Common/blob/master/skin/adminhtml/default/default/ffuenf/ffuenf.png" alt="ffuenf - code • design • e-commerce" /></a>

Ffuenf_MageTrashApp
===================
[![GitHub tag](https://img.shields.io/github/tag/ffuenf/Ffuenf_MageTrashApp.svg)][tag]
[![Build Status](https://img.shields.io/travis/ffuenf/Ffuenf_MageTrashApp.svg)][travis]
[![Code Quality](https://scrutinizer-ci.com/g/ffuenf/Ffuenf_MageTrashApp/badges/quality-score.png)][code_quality]
[![Code Coverage](https://scrutinizer-ci.com/g/ffuenf/Ffuenf_MageTrashApp/badges/coverage.png)][code_coverage]
[![Code Climate](https://codeclimate.com/github/ffuenf/Ffuenf_MageTrashApp/badges/gpa.svg)][codeclimate_gpa]
[![PayPal Donate](https://img.shields.io/badge/paypal-donate-blue.svg)][paypal_donate]
[tag]: https://github.com/ffuenf/Ffuenf_MageTrashApp
[travis]: https://travis-ci.org/ffuenf/Ffuenf_MageTrashApp
[code_quality]: https://scrutinizer-ci.com/g/ffuenf/Ffuenf_MageTrashApp
[code_coverage]: https://scrutinizer-ci.com/g/ffuenf/Ffuenf_MageTrashApp
[codeclimate_gpa]: https://codeclimate.com/github/ffuenf/Ffuenf_MageTrashApp
[paypal_donate]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J2PQS2WLT2Y8W&item_name=Magento%20Extension%3a%20Ffuenf_MageTrashApp&item_number=Ffuenf_MageTrashApp&currency_code=EUR

This is a extension for Magento Community Edition that adds a clean uninstallation routine for extensions.

Functionality
-------------

Uninstall features:

* Will run a sql uninstall script for module (must be called `uninstall.php` and be in sql directory)
* Will attempt to uninstall using PEAR packaging commands (as in Magento Connect)
* If not package found then will use uninstall file specified in module `config.xml` (by default, it is `etc/uninstall.txt`)

Install script features:

* Delete core_resource to force Magento to run install/upgrade scripts.
* Rewind core_resource to force Magento to run some install/upgrade scripts.

Instructions
------------

* Install module (modman file provided)
* Refresh cache and re-sign into Magento Admin
* Under `System > Configuration > Advanced` you will see MageTrashApp
* For each module you have options to enable, disable, Uninstall
* For each module you have options to delete or rewind `core_resource`

Info for Developers of extensions
---------------------------------

Place a file `uninstall.txt` into the folder `etc/`  of your module to allow to be triggered by this module when you uninstall it.
If you wish to change the name of this `uninstall.txt` file to something different, just set into the `config.xml` file of your module, the following:

```
<config>
    ...
    <uninstall>
        <filename>myuninstallfile.txt</filename>
    </uninstall>
</config>
```

The format of the content should start from the Magento root path. For example: you want to uninstall the module `Namespace_Mymodule` placed into the community code pool.
Just add the following lines to the file:

```
app/code/community/Namespace/Mymodule
app/etc/modules/Namespace_Mymodule.xml
js/mynamespace/
skin/frontend/base/default/images/mynapespace
```

If you have modman, you can copy the modman file into the `etc` folder of your module (app/code/.../Mynamespace/Mymodule/etc/) and rename it to `uninstall.txt` file. In this case, the second part of each line will be taken to uninstall your module.
For example:
```
src/app/code/community/Namespace/Mymodule  app/code/community/Namespace/Mymodule
src/app/etc/modules/Namespace_Mymodule.xml app/etc/modules/Namespace_Mymodule.xml
```

Platform
--------

The following versions are supported and tested:

* Magento Community Edition 1.9.2.2
* Magento Community Edition 1.9.2.1
* Magento Community Edition 1.9.2.0
* Magento Community Edition 1.8.1.0
* Magento Community Edition 1.7.0.2
* Magento Community Edition 1.6.2.0

Other versions are assumed to work.

Requirements
------------

|                                                                     | PHP 5.3        | PHP 5.4        | PHP 5.5           | PHP 5.6       | PHP 7.0       |
| ------------------------------------------------------------------- | -------------- | -------------- | ----------------- | ------------- | ------------- |
| [EOL](https://secure.php.net/supported-versions.php) / STABLE / RC  | EOL            | EOL            | STABLE            | **STABLE**    | RC            |
| automated tests on [travis]                                         | allow failure  | allow failure  | **required pass** | allow failure | allow failure |

Magento Community Edition officially supports PHP 5.4 and PHP 5.5.

Non-official compatibility to PHP 5.6 may be reached by following the tips on [Use of iconv.internal_encoding is deprecated](https://magento.stackexchange.com/questions/34015/magento-1-9-php-5-6-use-of-iconv-internal-encoding-is-deprecated).

Installation
------------

Use [modman](https://github.com/colinmollenhour/modman) to install:
```
modman init
modman clone https://github.com/ffuenf/Ffuenf_Common
modman clone https://github.com/ffuenf/Ffuenf_MageTrashApp
```

Deinstallation
--------------

Use [modman](https://github.com/colinmollenhour/modman) to clear all files and symlinks:
```
modman clean Ffuenf_MageTrashApp
```

Development
-----------
1. Fork the repository from GitHub.
2. Clone your fork to your local machine:

        $ git clone https://github.com/USER/Ffuenf_MageTrashApp

3. Create a git branch

        $ git checkout -b my_bug_fix

4. Make your changes/patches/fixes, committing appropriately
5. Push your changes to GitHub
6. Open a Pull Request

Credits
-------

* Tom Kadwill
* Sylvain Rayé
* wsakaren
* Damian Luszczymak

License and Author
------------------

- Author:: Achim Rosenhagen (<a.rosenhagen@ffuenf.de>)
- Copyright:: 2015, ffuenf

The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
