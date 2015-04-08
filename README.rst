***********************
DAM compatibility layer
***********************

.. contents::

================
What does it do?
================
This extension continues the availability the ancient API (library files, classes and
methods) of the deprecated extension **dam**. Thereby the behaviour (eg. method
signatures) are still the same but it works with the new File Abstraction Layer
(**FAL**).
So if you have a bunch of dependencies to **dam** you should give it a try.

Furthermore it rewrites present TCA- and FlexForm-references to tx_dam and tx_dam_cat
so that the appropriate FAL or sys_category configuration is used. Thus it allows
you to simply keep those configurations without losing your references (which however
requires the tx_dam data to be imported to FAL with eg. nr_dam_falmigration_).

.. _nr_dam_falmigration: https://github.com/netresearch/t3x-nr_dam_falmigration

============
Installation
============
.. Note::

    It's mandatory that this extension is installed into ``typo3conf/ext/dam`` and
    **not** ``typo3conf/ext/dam_compat``.

When to install?
================
You should replace **dam** with this extension right after you updated the TYPO3
sources and prior to running the upgrade wizard.

Installation from github
========================
The easiest way to replace **dam** with this extension is to clone it from github_
into the same directory::

    rm -rf typo3conf/ext/dam
    git clone git@github.com:netresearch/t3x-dam_compat.git typo3conf/ext/dam

Installation from TER
=====================
If you for whatever reason can't install from github, you can import the t3x file
from TER_ after running the install tool upgrade wizard. Afterwards you should
uninstall (but not unlink) it, remove ``typo3conf/ext/dam`` and rename
``typo3conf/ext/dam_compat`` to ``typo3conf/ext/dam``. It might occur that the
extension manager automatically deactivated dam and dependent extensions because dam
is only compatible until 4.7 - just reactivate those extensions after you replaced
dam.

.. _TER: http://typo3.org/extensions/repository/view/dam_compat
.. _github: https://github.com/netresearch/t3x-dam_compat