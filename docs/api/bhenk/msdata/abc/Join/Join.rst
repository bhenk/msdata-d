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

.. _bhenk\msdata\abc\Join:

Join
====

.. table::
   :widths: auto
   :align: left

   ========== ============================================================================================================ 
   namespace  bhenk\\msdata\\abc                                                                                           
   predicates Cloneable | Instantiable                                                                                     
   implements `Stringable <https://www.php.net/manual/en/class.stringable.php>`_ | :ref:`bhenk\msdata\abc\EntityInterface` 
   extends    :ref:`bhenk\msdata\abc\Entity`                                                                               
   hierarchy  :ref:`bhenk\msdata\abc\Join` -> :ref:`bhenk\msdata\abc\Entity`                                               
   ========== ============================================================================================================ 


**A basic Entity for a join function**


Join can be used when expressing a many-to-many relation


.. contents::


----


.. _bhenk\msdata\abc\Join::Constructor:

Constructor
+++++++++++


.. _bhenk\msdata\abc\Join::__construct:

Join::__construct
-----------------

.. table::
   :widths: auto
   :align: left

   ========== ==================== 
   predicates public | constructor 
   ========== ==================== 


**Constructs a new Join**


.. code-block:: php

   public function __construct(
         Parameter #0 [ <optional> ?int $ID = NULL ]
         Parameter #1 [ <optional> ?int $FK_LEFT = NULL ]
         Parameter #2 [ <optional> ?int $FK_RIGHT = NULL ]
    )


| :tag5:`param` ?\ int :param:`$ID` - ID of this Join
| :tag5:`param` ?\ int :param:`$FK_LEFT` - the left hand foreign key
| :tag5:`param` ?\ int :param:`$FK_RIGHT` - the right hand foreign key


----


.. _bhenk\msdata\abc\Join::Methods:

Methods
+++++++


.. _bhenk\msdata\abc\Join::getFkLeft:

Join::getFkLeft
---------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Get the left hand foreign key**


.. code-block:: php

   public function getFkLeft(): ?int


| :tag6:`return` ?\ int


----


.. _bhenk\msdata\abc\Join::setFkLeft:

Join::setFkLeft
---------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Set the left hand foreign key**


.. code-block:: php

   public function setFkLeft(
         Parameter #0 [ <required> ?int $FK_LEFT ]
    ): void


| :tag6:`param` ?\ int :param:`$FK_LEFT`
| :tag6:`return` void


----


.. _bhenk\msdata\abc\Join::getFkRight:

Join::getFkRight
----------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Get the right hand foreign key**


.. code-block:: php

   public function getFkRight(): ?int


| :tag6:`return` ?\ int


----


.. _bhenk\msdata\abc\Join::setFkRight:

Join::setFkRight
----------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Set the right hand foreign key**


.. code-block:: php

   public function setFkRight(
         Parameter #0 [ <required> ?int $FK_RIGHT ]
    ): void


| :tag6:`param` ?\ int :param:`$FK_RIGHT`
| :tag6:`return` void


----


.. _bhenk\msdata\abc\Join::clone:

Join::clone
-----------

.. table::
   :widths: auto
   :align: left

   ============== ============================================== 
   predicates     public                                         
   implements     :ref:`bhenk\msdata\abc\EntityInterface::clone` 
   inherited from :ref:`bhenk\msdata\abc\Entity::clone`          
   ============== ============================================== 






.. admonition:: @inheritdoc

    

   **Create an Entity that equals this Entity**
   
   
   The newly created Entity gets the given ID or no ID if :tagsign:`param` :tech:`$ID` is *null*.
   
   | :tag6:`param` int | null :param:`$ID`
   | :tag6:`return` :ref:`bhenk\msdata\abc\Entity`
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::clone`




.. code-block:: php

   public function clone(
         Parameter #0 [ <optional> ?int $ID = NULL ]
    ): Entity


| :tag6:`param` ?\ int :param:`$ID`
| :tag6:`return` :ref:`bhenk\msdata\abc\Entity`  - Entity, similar to this one, with the given ID
| :tag6:`throws` `ReflectionException <https://www.php.net/manual/en/class.reflectionexception.php>`_


----


.. _bhenk\msdata\abc\Join::toArray:

Join::toArray
-------------

.. table::
   :widths: auto
   :align: left

   ============== ================================================ 
   predicates     public                                           
   implements     :ref:`bhenk\msdata\abc\EntityInterface::toArray` 
   inherited from :ref:`bhenk\msdata\abc\Entity::toArray`          
   ============== ================================================ 






.. admonition:: @inheritdoc

    

   **Express the properties of this Entity in an array**
   
   
   The returned array should be in such order that it can be fet to the static method
   :ref:`bhenk\msdata\abc\EntityInterface::fromArray`.
   
   | :tag6:`return` array  - array with properties of this Entity
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::toArray`





.. admonition::  see also

    :ref:`bhenk\msdata\abc\Entity::fromArray`


.. code-block:: php

   public function toArray(): array


| :tag6:`return` array  - array with properties


----


.. _bhenk\msdata\abc\Join::getParents:

Join::getParents
----------------

.. table::
   :widths: auto
   :align: left

   ============== ========================================== 
   predicates     public                                     
   inherited from :ref:`bhenk\msdata\abc\Entity::getParents` 
   ============== ========================================== 


**Get the (Reflection) parents of this Entity in reverse order**



..  code-block::

   class A extends Entity
   
   class B extends A
   
   returned array = [Entity-Reflection, A-Reflection, B-Reflection]





.. code-block:: php

   public function getParents(): array


| :tag6:`return` array  - array with `ReflectionClass <https://www.php.net/manual/en/class.reflectionclass.php>`_ parents and this Entity


----


.. _bhenk\msdata\abc\Join::fromArray:

Join::fromArray
---------------

.. table::
   :widths: auto
   :align: left

   ============== ================================================== 
   predicates     public | static                                    
   implements     :ref:`bhenk\msdata\abc\EntityInterface::fromArray` 
   inherited from :ref:`bhenk\msdata\abc\Entity::fromArray`          
   ============== ================================================== 


**Create a new Entity**


The order of the given array should be *parent-first*, i.e.:

..  code-block::

   class A extends Entity
   
   class B extends A


In :tech:`__construct()`, :tech:`toArray()` and :tech:`fromArray()` functions,
properties/parameters have the order:

..  code-block::

   ID, {props of A}, {props of B}





.. admonition:: @inheritdoc

    

   **Create a new Entity from an array of properties**
   
   
   The given array should have the same order as the one gotten from :ref:`bhenk\msdata\abc\EntityInterface::toArray`.
   
   
   | :tag6:`param` array :param:`$arr` - property array
   | :tag6:`return` :ref:`bhenk\msdata\abc\Entity`  - newly created Entity with the given properties
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::fromArray`




.. code-block:: php

   public static function fromArray(
         Parameter #0 [ <required> array $arr ]
    ): static


| :tag6:`param` array :param:`$arr` - array with properties
| :tag6:`return` static  - Entity object
| :tag6:`throws` `ReflectionException <https://www.php.net/manual/en/class.reflectionexception.php>`_


----


.. _bhenk\msdata\abc\Join::isSame:

Join::isSame
------------

.. table::
   :widths: auto
   :align: left

   ============== =============================================== 
   predicates     public                                          
   implements     :ref:`bhenk\msdata\abc\EntityInterface::isSame` 
   inherited from :ref:`bhenk\msdata\abc\Entity::isSame`          
   ============== =============================================== 






.. admonition:: @inheritdoc

    

   **Test is same function**
   
   
   The given Entity is similar to this Entity if all properties, including :tech:`ID`, are equal.
   
   | :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$other` - Entity to test
   | :tag6:`return` bool  - *true* if all properties, including :tech:`ID`, are equal, *false* otherwise
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::isSame`




.. code-block:: php

   public function isSame(
         Parameter #0 [ <required> bhenk\msdata\abc\Entity $other ]
    ): bool


| :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$other`
| :tag6:`return` bool


----


.. _bhenk\msdata\abc\Join::equals:

Join::equals
------------

.. table::
   :widths: auto
   :align: left

   ============== =============================================== 
   predicates     public                                          
   implements     :ref:`bhenk\msdata\abc\EntityInterface::equals` 
   inherited from :ref:`bhenk\msdata\abc\Entity::equals`          
   ============== =============================================== 






.. admonition:: @inheritdoc

    

   **Test equals function**
   
   
   The given Entity equals this Entity if all properties, except :tech:`ID`, are equal.
   
   | :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$other` - Entity to test
   | :tag6:`return` bool  - *true* if all properties are equal, *false* otherwise
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::equals`




.. code-block:: php

   public function equals(
         Parameter #0 [ <required> bhenk\msdata\abc\Entity $other ]
    ): bool


| :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$other`
| :tag6:`return` bool


----


.. _bhenk\msdata\abc\Join::getID:

Join::getID
-----------

.. table::
   :widths: auto
   :align: left

   ============== ============================================== 
   predicates     public                                         
   implements     :ref:`bhenk\msdata\abc\EntityInterface::getID` 
   inherited from :ref:`bhenk\msdata\abc\Entity::getID`          
   ============== ============================================== 






.. admonition:: @inheritdoc

    

   **Get the ID of this Entity or** *null* **if it has no ID**
   
   | :tag6:`return` int | null  - ID of this Entity or *null*
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::getID`




.. code-block:: php

   public function getID(): ?int


| :tag6:`return` ?\ int


----


.. _bhenk\msdata\abc\Join::__toString:

Join::__toString
----------------

.. table::
   :widths: auto
   :align: left

   ============== =================================================================================== 
   predicates     public                                                                              
   implements     `Stringable::__toString <https://www.php.net/manual/en/stringable.__tostring.php>`_ 
   inherited from :ref:`bhenk\msdata\abc\Entity::__toString`                                          
   ============== =================================================================================== 


**String representation of this Entity**


.. code-block:: php

   public function __toString(): string


| :tag6:`return` string  - representing this Entity


----

:block:`Sat, 08 Apr 2023 17:57:52 +0000` 
