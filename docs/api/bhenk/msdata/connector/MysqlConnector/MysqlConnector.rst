.. required styles !!
.. raw:: html

    <style> .block {color:lightgrey; font-size: 0.6em; display: block; align-items: center; background-color:black; width:8em; height:8em;padding-left:7px;} </style>
    <style> .tag0 {color:grey; font-size: 0.9em; font-family: "Courier New", monospace;} </style>
    <style> .tag3 {color:grey; font-size: 0.9em; display: inline-block; width:3.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag4 {color:grey; font-size: 0.9em; display: inline-block; width:4.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag5 {color:grey; font-size: 0.9em; display: inline-block; width:5.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag6 {color:grey; font-size: 0.9em; display: inline-block; width:6.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag7 {color:grey; font-size: 0.9em; display: inline-block; width:7.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag8 {color:grey; font-size: 0.9em; display: inline-block; width:8.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag9 {color:grey; font-size: 0.9em; display: inline-block; width:9.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag10 {color:grey; font-size: 0.9em; display: inline-block; width:10.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag11 {color:grey; font-size: 0.9em; display: inline-block; width:11.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tag12 {color:grey; font-size: 0.9em; display: inline-block; width:12.1ch; font-family: "Courier New", monospace;} </style>
    <style> .tagsign {color:grey; font-size: 0.9em; display: inline-block; width:3.2em;} </style>
    <style> .param {color:#005858; background-color:#F8F8F8; font-size: 0.8em; border:1px solid #D0D0D0;padding-left: 5px; padding-right: 5px;} </style>
    <style> .tech {color:#005858; background-color:#F8F8F8; font-size: 0.9em; border:1px solid #D0D0D0;padding-left: 5px; padding-right: 5px;} </style>

.. end required styles

.. required roles !!
.. role:: block
.. role:: tag0
.. role:: tag3
.. role:: tag4
.. role:: tag5
.. role:: tag6
.. role:: tag7
.. role:: tag8
.. role:: tag9
.. role:: tag10
.. role:: tag11
.. role:: tag12
.. role:: tagsign
.. role:: param
.. role:: tech

.. end required roles

.. _bhenk\msdata\connector\MysqlConnector:

MysqlConnector
==============

.. table::
   :widths: auto
   :align: left

   ========== ======================== 
   namespace  bhenk\\msdata\\connector 
   predicates Cloneable | Instantiable 
   ========== ======================== 


**Static wrapper around a mysqli instance**


Convenience class for a `mysqli <https://www.php.net/manual/en/class.mysqli.php>`_ connection and configuration.
Configuration options can be expressed in a configuration file. The configuration file
can be set with the method :ref:`bhenk\msdata\connector\MysqlConnector::setConfigFile` or you can rely on auto-finding
of the file.

If relying on auto-finding of configuration, this class will look for a configuration file
with the name *msd_config.php* in a directory with the name *config*. The *config* directory should be
a child of an ancestor directory of this code base:

..  code-block::

   {ancestor directory}/config/msd_config.php


The configuration file should return configuration options as an array:

..  code-block::

   <?php
   
   return [
       "hostname" => {string},     // required
       "username" => {string},     // required
       "password" => {string},     // required
       "database" => {string},     // required
       "port" => {int},            // optional, default 3306
       "persistent" => {bool},     // optional, default true
   ];



A third method of setting the configuration is by programmatically calling
:ref:`bhenk\msdata\connector\MysqlConnector::setConfiguration` with the appropriate array, like shown above.


.. contents::


----


.. _bhenk\msdata\connector\MysqlConnector::Constants:

Constants
+++++++++


.. _bhenk\msdata\connector\MysqlConnector::CONFIG_DIR:

MysqlConnector::CONFIG_DIR
--------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 




**Name of the directory where a configuration file is expected**



.. code-block:: php

   string(6) "config" 




----


.. _bhenk\msdata\connector\MysqlConnector::CONFIG_FILE:

MysqlConnector::CONFIG_FILE
---------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 




**Name of the expected configuration file**



.. code-block:: php

   string(14) "msd_config.php" 




----


.. _bhenk\msdata\connector\MysqlConnector::Methods:

Methods
+++++++


.. _bhenk\msdata\connector\MysqlConnector::get:

MysqlConnector::get
-------------------

.. table::
   :widths: auto
   :align: left

   ========== =============== 
   predicates public | static 
   ========== =============== 


**Get the singleton instance of this class**


.. code-block:: php

   public static function get(): MysqlConnector


| :tag6:`return` :ref:`bhenk\msdata\connector\MysqlConnector`


----


.. _bhenk\msdata\connector\MysqlConnector::closeConnection:

MysqlConnector::closeConnection
-------------------------------

.. table::
   :widths: auto
   :align: left

   ========== =============== 
   predicates public | static 
   ========== =============== 


**Close the connection (if any)**


.. code-block:: php

   public static function closeConnection(): void


| :tag6:`return` void


----


.. _bhenk\msdata\connector\MysqlConnector::getConfigFile:

MysqlConnector::getConfigFile
-----------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Get the (absolute path to the) configuration file**






.. admonition::  see also

    :ref:`bhenk\msdata\connector\MysqlConnector::getConfiguration`


.. code-block:: php

   public function getConfigFile(): string|bool


| :tag6:`return` string | bool  - absolute path to configuration file or *false* if not set


----


.. _bhenk\msdata\connector\MysqlConnector::setConfigFile:

MysqlConnector::setConfigFile
-----------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Set the configuration file**


When not using auto-find of configuration, this method must be called before a call to
:ref:`bhenk\msdata\connector\MysqlConnector::getConnector`.


.. code-block:: php

   public function setConfigFile(
         Parameter #0 [ <required> string|bool $config_file ]
    ): void


| :tag6:`param` string | bool :param:`$config_file` - absolute path to a configuration file, or *false* when returning to auto-find configuration
| :tag6:`return` void


----


.. _bhenk\msdata\connector\MysqlConnector::statusInfo:

MysqlConnector::statusInfo
--------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Returns status info**


Something like

..  code-block::

   Uptime: 80984
   Threads: 2
   Questions: 1327
   Slow queries: 0
   Opens: 432
   Flush tables: 3
   Open tables: 274
   Queries per second avg: 0.016"




.. code-block:: php

   public function statusInfo(): string|bool


| :tag6:`return` string | bool  - a string describing the server status, *false* if an error occurred
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_


----


.. _bhenk\msdata\connector\MysqlConnector::getConnector:

MysqlConnector::getConnector
----------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Get the connector**


.. code-block:: php

   public function getConnector(): mysqli


| :tag6:`return` `mysqli <https://www.php.net/manual/en/class.mysqli.php>`_  - connector to database
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - if connection could not be established, code 100


----


.. _bhenk\msdata\connector\MysqlConnector::getConfiguration:

MysqlConnector::getConfiguration
--------------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Get the configuration**


If configuration not set, the array will be read from the configuration file, either from the configuration
file as given with :ref:`bhenk\msdata\connector\MysqlConnector::setConfigFile` or from the auto-find configuration file at

..  code-block::

   {ancestor directory}/config/msd_config.php





.. code-block:: php

   public function getConfiguration(): array


| :tag6:`return` array  - configuration array
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - if configuration could not be read


----


.. _bhenk\msdata\connector\MysqlConnector::setConfiguration:

MysqlConnector::setConfiguration
--------------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Set configuration as an array**






.. admonition::  see also

    :ref:`bhenk\msdata\connector\MysqlConnector`


.. code-block:: php

   public function setConfiguration(
         Parameter #0 [ <required> array $configuration ]
    ): void


| :tag6:`param` array :param:`$configuration` - configuration as described in comment on this class
| :tag6:`return` void
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - if given configuration is not valid


----


.. _bhenk\msdata\connector\MysqlConnector::connectionInfo:

MysqlConnector::connectionInfo
------------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Returns client and server info**


Something like

..  code-block::

   client: mysqlnd 8.2.1
   server: 8.0.32
   host: 127.0.0.1 via TCP/IP
   protocol version: 10
   character set: utf8mb4





.. code-block:: php

   public function connectionInfo(): string


| :tag6:`return` string  - client and server info
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_


----

:block:`Sat, 15 Apr 2023 09:22:29 +0000` 
