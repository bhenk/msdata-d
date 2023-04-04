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

.. _bhenk\msdata\abc\EntityInterface:

EntityInterface
===============

.. table::
   :widths: auto
   :align: left

   ===================== ====================================================================================================================================== 
   namespace             bhenk\\msdata\\abc                                                                                                                     
   predicates            Abstract | Interface                                                                                                                   
   implements            `Stringable <https://www.php.net/manual/en/class.stringable.php>`_                                                                     
   known implementations :ref:`bhenk\msdata\abc\Entity` | :ref:`bhenk\msdata\node\NodeDo` | :ref:`bhenk\msdata\user\PersonDo` | :ref:`bhenk\msdata\user\UserDo` 
   ===================== ====================================================================================================================================== 


**Definition of a basic data object**


.. contents::


----


.. _bhenk\msdata\abc\EntityInterface::Methods:

Methods
+++++++


.. _bhenk\msdata\abc\EntityInterface::fromArray:

EntityInterface::fromArray
--------------------------

.. table::
   :widths: auto
   :align: left

   ========== ========================== 
   predicates public | static | abstract 
   ========== ========================== 


**Create a new Entity from an array of properties**


The given array should have the same order as the one gotten from :ref:`bhenk\msdata\abc\EntityInterface::toArray`.



.. code-block:: php

   public static abstract function fromArray(
         Parameter #0 [ <required> array $arr ]
    ): Entity


| :tag6:`param` array :param:`$arr` - property array
| :tag6:`return` :ref:`bhenk\msdata\abc\Entity`  - newly created Entity with the given properties


----


.. _bhenk\msdata\abc\EntityInterface::getID:

EntityInterface::getID
----------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Get the ID of this Entity or** *null* **if it has no ID**


.. code-block:: php

   public abstract function getID(): ?int


| :tag6:`return` ?\ int  - ID of this Entity or *null*


----


.. _bhenk\msdata\abc\EntityInterface::toArray:

EntityInterface::toArray
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Express the properties of this Entity in an array**


The returned array should be in such order that it can be fet to the static method
:ref:`bhenk\msdata\abc\EntityInterface::fromArray`.


.. code-block:: php

   public abstract function toArray(): array


| :tag6:`return` array  - array with properties of this Entity


----


.. _bhenk\msdata\abc\EntityInterface::clone:

EntityInterface::clone
----------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Create an Entity that equals this Entity**


The newly created Entity gets the given ID or no ID if :tagsign:`param` :tech:`$ID` is *null*.


.. code-block:: php

   public abstract function clone(
         Parameter #0 [ <optional> ?int $ID = NULL ]
    ): Entity


| :tag6:`param` ?\ int :param:`$ID`
| :tag6:`return` :ref:`bhenk\msdata\abc\Entity`


----


.. _bhenk\msdata\abc\EntityInterface::equals:

EntityInterface::equals
-----------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Test equals function**


The given Entity equals this Entity if all properties, except :tech:`ID`, are equal.


.. code-block:: php

   public abstract function equals(
         Parameter #0 [ <required> bhenk\msdata\abc\Entity $other ]
    ): bool


| :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$other` - Entity to test
| :tag6:`return` bool  - *true* if all properties are equal, *false* otherwise


----


.. _bhenk\msdata\abc\EntityInterface::isSame:

EntityInterface::isSame
-----------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Test is same function**


The given Entity is similar to this Entity if all properties, including :tech:`ID`, are equal.


.. code-block:: php

   public abstract function isSame(
         Parameter #0 [ <required> bhenk\msdata\abc\Entity $other ]
    ): bool


| :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$other` - Entity to test
| :tag6:`return` bool  - *true* if all properties, including :tech:`ID`, are equal, *false* otherwise


----


.. _bhenk\msdata\abc\EntityInterface::__toString:

EntityInterface::__toString
---------------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


.. code-block:: php

   public abstract function __toString(): string


| :tag6:`return` string


----

:block:`Tue, 04 Apr 2023 13:37:54 +0000` 
