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

.. _bhenk\msdata\user\UserDo:

UserDo
======

.. table::
   :widths: auto
   :align: left

   ================ ============================================================================================================ 
   namespace        bhenk\\msdata\\user                                                                                          
   predicates       Cloneable | Instantiable                                                                                     
   implements       `Stringable <https://www.php.net/manual/en/class.stringable.php>`_ | :ref:`bhenk\msdata\abc\EntityInterface` 
   extends          :ref:`bhenk\msdata\abc\Entity`                                                                               
   hierarchy        :ref:`bhenk\msdata\user\UserDo` -> :ref:`bhenk\msdata\abc\Entity`                                            
   known subclasses :ref:`bhenk\msdata\user\PersonDo`                                                                            
   ================ ============================================================================================================ 


**A simple user**


This line has :ref:`bhenk\msdata\abc\Entity`, `OutOfBoundsException <https://www.php.net/manual/en/class.outofboundsexception.php>`_,
:ref:`bhenk\msdata\abc\Entity` 3 links.

A box of roses
        with content


.. contents::


----


.. _bhenk\msdata\user\UserDo::Constructor:

Constructor
+++++++++++


.. _bhenk\msdata\user\UserDo::__construct:

UserDo::__construct
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ==================== 
   predicates public | constructor 
   ========== ==================== 


.. code-block:: php

   public function __construct(
         Parameter #0 [ <optional> ?int $ID = NULL ]
         Parameter #1 [ <optional> ?string $first_name = NULL ]
         Parameter #2 [ <optional> ?string $prefixes = NULL ]
         Parameter #3 [ <optional> ?string $last_name = NULL ]
         Parameter #4 [ <optional> ?string $email = NULL ]
    )


| :tag5:`param` ?\ int :param:`$ID`
| :tag5:`param` ?\ string :param:`$first_name`
| :tag5:`param` ?\ string :param:`$prefixes`
| :tag5:`param` ?\ string :param:`$last_name`
| :tag5:`param` ?\ string :param:`$email`


----


.. _bhenk\msdata\user\UserDo::Methods:

Methods
+++++++


.. _bhenk\msdata\user\UserDo::getFirstName:

UserDo::getFirstName
--------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function getFirstName(): ?string


| :tag6:`return` ?\ string


----


.. _bhenk\msdata\user\UserDo::setFirstName:

UserDo::setFirstName
--------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function setFirstName(
         Parameter #0 [ <required> ?string $first_name ]
    ): void


| :tag6:`param` ?\ string :param:`$first_name`
| :tag6:`return` void


----


.. _bhenk\msdata\user\UserDo::getPrefixes:

UserDo::getPrefixes
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function getPrefixes(): ?string


| :tag6:`return` ?\ string


----


.. _bhenk\msdata\user\UserDo::setPrefixes:

UserDo::setPrefixes
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function setPrefixes(
         Parameter #0 [ <required> ?string $prefixes ]
    ): void


| :tag6:`param` ?\ string :param:`$prefixes`
| :tag6:`return` void


----


.. _bhenk\msdata\user\UserDo::getLastName:

UserDo::getLastName
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function getLastName(): ?string


| :tag6:`return` ?\ string


----


.. _bhenk\msdata\user\UserDo::setLastName:

UserDo::setLastName
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function setLastName(
         Parameter #0 [ <required> ?string $last_name ]
    ): void


| :tag6:`param` ?\ string :param:`$last_name`
| :tag6:`return` void


----


.. _bhenk\msdata\user\UserDo::getEmail:

UserDo::getEmail
----------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function getEmail(): ?string


| :tag6:`return` ?\ string


----


.. _bhenk\msdata\user\UserDo::setEmail:

UserDo::setEmail
----------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 





.. code-block:: php

   public function setEmail(
         Parameter #0 [ <required> ?string $email ]
    ): void


| :tag6:`param` ?\ string :param:`$email`
| :tag6:`return` void


----


.. _bhenk\msdata\user\UserDo::clone:

UserDo::clone
-------------

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


.. _bhenk\msdata\user\UserDo::toArray:

UserDo::toArray
---------------

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
   `EntityInterface::fromArray() <https://www.google.com/search?q=EntityInterface::fromArray()>`_.
   
   | :tag6:`return` array  - array with properties of this Entity
   
   ``@inheritdoc`` from method :ref:`bhenk\msdata\abc\EntityInterface::toArray`





.. admonition::  see also

    :ref:`bhenk\msdata\abc\Entity::fromArray`


.. code-block:: php

   public function toArray(): array


| :tag6:`return` array  - array with properties


----


.. _bhenk\msdata\user\UserDo::getParents:

UserDo::getParents
------------------

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


.. _bhenk\msdata\user\UserDo::fromArray:

UserDo::fromArray
-----------------

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
   
   
   The given array should have the same order as the one gotten from `EntityInterface::toArray() <https://www.google.com/search?q=EntityInterface::toArray()>`_.
   
   
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


.. _bhenk\msdata\user\UserDo::isSame:

UserDo::isSame
--------------

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


.. _bhenk\msdata\user\UserDo::equals:

UserDo::equals
--------------

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


.. _bhenk\msdata\user\UserDo::getID:

UserDo::getID
-------------

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


.. _bhenk\msdata\user\UserDo::__toString:

UserDo::__toString
------------------

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

:block:`Sat, 01 Jul 2023 13:02:23 +0000` 
