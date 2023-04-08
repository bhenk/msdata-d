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

.. _bhenk\msdata\abc\DataTypes:

DataTypes
=========

.. table::
   :widths: auto
   :align: left

   ========== =================================================================================================================================== 
   namespace  bhenk\\msdata\\abc                                                                                                                  
   predicates Final | Enum                                                                                                                        
   implements `UnitEnum <https://www.php.net/manual/en/class.unitenum.php>`_ | `BackedEnum <https://www.php.net/manual/en/class.backedenum.php>`_ 
   ========== =================================================================================================================================== 


**Convertor for PHP-types to database-types**


.. contents::


----


.. _bhenk\msdata\abc\DataTypes::Constants:

Constants
+++++++++


.. _bhenk\msdata\abc\DataTypes::string:

DataTypes::string
-----------------

.. table::
   :widths: auto
   :align: left

   ========== ================== 
   predicates public | enum case 
   ========== ================== 




**PHP string converts to database type VARCHAR(255)**



.. code-block:: php

   enum(bhenk\msdata\abc\DataTypes::string) 




----


.. _bhenk\msdata\abc\DataTypes::int:

DataTypes::int
--------------

.. table::
   :widths: auto
   :align: left

   ========== ================== 
   predicates public | enum case 
   ========== ================== 




**PHP int converts to database type INT**



.. code-block:: php

   enum(bhenk\msdata\abc\DataTypes::int) 




----


.. _bhenk\msdata\abc\DataTypes::bool:

DataTypes::bool
---------------

.. table::
   :widths: auto
   :align: left

   ========== ================== 
   predicates public | enum case 
   ========== ================== 




**PHP bool converts to database type BOOLEAN**



.. code-block:: php

   enum(bhenk\msdata\abc\DataTypes::bool) 




----


.. _bhenk\msdata\abc\DataTypes::float:

DataTypes::float
----------------

.. table::
   :widths: auto
   :align: left

   ========== ================== 
   predicates public | enum case 
   ========== ================== 




**PHP float converts to database type FLOAT**



.. code-block:: php

   enum(bhenk\msdata\abc\DataTypes::float) 




----


.. _bhenk\msdata\abc\DataTypes::Methods:

Methods
+++++++


.. _bhenk\msdata\abc\DataTypes::fromName:

DataTypes::fromName
-------------------

.. table::
   :widths: auto
   :align: left

   ========== =============== 
   predicates public | static 
   ========== =============== 


**Get the database-type for the given PHP-type**


.. code-block:: php

   public static function fromName(
         Parameter #0 [ <required> string $name ]
    ): string


| :tag6:`param` string :param:`$name` - PHP-type name
| :tag6:`return` string  - database-type name


----


.. _bhenk\msdata\abc\DataTypes::cases:

DataTypes::cases
----------------

.. table::
   :widths: auto
   :align: left

   ========== ===================================================================== 
   predicates public | static                                                       
   implements `UnitEnum::cases <https://www.php.net/manual/en/unitenum.cases.php>`_ 
   ========== ===================================================================== 


.. code-block:: php

   public static function cases(): array


| :tag6:`return` array


----


.. _bhenk\msdata\abc\DataTypes::from:

DataTypes::from
---------------

.. table::
   :widths: auto
   :align: left

   ========== ======================================================================= 
   predicates public | static                                                         
   implements `BackedEnum::from <https://www.php.net/manual/en/backedenum.from.php>`_ 
   ========== ======================================================================= 


.. code-block:: php

   public static function from(
         Parameter #0 [ <required> string|int $value ]
    ): static


| :tag6:`param` string | int :param:`$value`
| :tag6:`return` static


----


.. _bhenk\msdata\abc\DataTypes::tryFrom:

DataTypes::tryFrom
------------------

.. table::
   :widths: auto
   :align: left

   ========== ============================================================================= 
   predicates public | static                                                               
   implements `BackedEnum::tryFrom <https://www.php.net/manual/en/backedenum.tryfrom.php>`_ 
   ========== ============================================================================= 


.. code-block:: php

   public static function tryFrom(
         Parameter #0 [ <required> string|int $value ]
    ): ?static


| :tag6:`param` string | int :param:`$value`
| :tag6:`return` ?\ static


----

:block:`Sat, 08 Apr 2023 09:06:57 +0000` 
