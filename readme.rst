###################
About this fork
###################

Customized CodeIgniter web application framework used by some of my web
projects. To use this you will also need the `nv web project skeleton
<http://github.com/strackovski/app-skeleton>`_.

CodeIgniter is an Application Development Framework - a toolkit - for people
who build web sites using PHP. `Read more about it
<http://codeigniter.com/downloads/>`_.

******************
Features and goals
******************

- Installed in default vendor directory of a php composer project.
- The application MVC stack is placed out of CodeIgniter installation.
- Out-of-the-box integration with Twig, Doctrine, Assetic.
- Includes RESTful controller and i18n extensions.

**********************
Installation and usage
**********************

Modify your project's composer.json file to include data bellow:
::

    {
        "repositories": [
            {
                "type": "package",
                "package": {
                    "name": "nv/codeigniter",
                    "version": "2.2.0",
                    "dist": {
                        "url": "https://github.com/strackovski/CodeIgniter/archive/2.2-stable.zip",
                        "type": "zip"
                    },
                    "source": {
                        "url": "https://github.com/strackovski/CodeIgniter",
                        "type": "git",
                        "reference": "2.2-stable"
                    }
                }
            }
        ]
    }

Add the following package to the list of required packages in your project's composer.json file
::

    "require": {
        "nv/codeigniter": "2.2.*"
    }

After running composer install copy the index.dist.php from the package directory to
your project root and rename it to index.php (or whatever filename you set as the front
controller).

*******************
Release Information
*******************

This fork is based on the 2.2-stable release of CodeIgniter.

*******************
Server Requirements
*******************

-  PHP version 5.3.3 or newer.

*******
License
*******

This is a fork of the popular CodeIgniter web application framework. For the CodeIgniter
license please see the licese file provided with this project or read the `license agreement <http://ellislab.com/codeigniter/user-guide/license.html>`_
on their homepage.

All changes of the original project are released under the license specified in this
project's license file.

********************************
Additional CodeIgniter Resources
********************************

-  `User Guide <http://ellislab.com/codeigniter/user_guide/>`_
-  `Community Forums <http://ellislab.com/forums/>`_
-  `Community Wiki <https://github.com/EllisLab/CodeIgniter/wiki/>`_
-  `Community IRC <http://ellislab.com/codeigniter/irc>`_

