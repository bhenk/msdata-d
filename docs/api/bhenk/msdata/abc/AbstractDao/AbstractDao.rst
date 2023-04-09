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

.. _bhenk\msdata\abc\AbstractDao:

AbstractDao
===========

.. table::
   :widths: auto
   :align: left

   ================ ================================================================================================================================================== 
   namespace        bhenk\\msdata\\abc                                                                                                                                 
   predicates       Abstract                                                                                                                                           
   known subclasses :ref:`bhenk\msdata\abc\AbstractJoinDao` | :ref:`bhenk\msdata\node\NodeDao` | :ref:`bhenk\msdata\user\PersonDao` | :ref:`bhenk\msdata\user\UserDao` 
   ================ ================================================================================================================================================== 


**Data Access Object with basic functionality**


In most cases subclasses only need to implement :ref:`getDataObjectName <bhenk\msdata\abc\AbstractDao::getDataObjectName>` and
:ref:`getTableName <bhenk\msdata\abc\AbstractDao::getTableName>`. If
:ref:`getCreateTableStatement <bhenk\msdata\abc\AbstractDao::getCreateTableStatement>` is not sufficient
override that method as well.

This class expects :ref:`Entities <bhenk\msdata\abc\EntityInterface>` that subclass other Entity implementations to have
parent-first in their :tech:`__construct()` and :ref:`toArray() <bhenk\msdata\abc\EntityInterface::toArray>` functions, i.e.:

..  code-block::

   class A extends Entity
   
   class B extends A


In their :tech:`__construct()` and :tech:`toArray()` functions, properties/parameters have the order:

..  code-block::

   ID, {props of A}, {props of B}




.. contents::


----


.. _bhenk\msdata\abc\AbstractDao::Methods:

Methods
+++++++


.. _bhenk\msdata\abc\AbstractDao::createTable:

AbstractDao::createTable
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Create a table in the database**


The statement used is the one from :ref:`getCreateTableStatement <bhenk\msdata\abc\AbstractDao::getCreateTableStatement>`.



.. code-block:: php

   public function createTable(
         Parameter #0 [ <optional> bool $drop = false ]
    ): int


| :tag6:`param` bool :param:`$drop` - Drop (if exists) table with same name before create
| :tag6:`return` int  - count of executed statements
| :tag6:`throws` `ReflectionException <https://www.php.net/manual/en/class.reflectionexception.php>`_
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 200


----


.. _bhenk\msdata\abc\AbstractDao::getTableName:

AbstractDao::getTableName
-------------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Get the name of the table that will store the** :ref:`Entities <bhenk\msdata\abc\Entity>` **this class provides access to**


.. code-block:: php

   public abstract function getTableName(): string


| :tag6:`return` string  - name of table reserved for DO


----


.. _bhenk\msdata\abc\AbstractDao::getCreateTableStatement:

AbstractDao::getCreateTableStatement
------------------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Produces a minimal** *CreateTableStatement*




..  code-block::

   CREATE TABLE IF NOT EXISTS `%table_name%`
   (
        `ID`                INT NOT NULL AUTO_INCREMENT,
        `%int_prop%`        INT,
        `%string_prop%`     VARCHAR(255),
        `%bool_prop%`       BOOLEAN,
        `%float_prop%`      FLOAT,
        PRIMARY KEY (`ID`)
   );


In the above :tech:`%xyz%` is placeholder for table name or property name. Notice that string type
parameters have a limited length of 255 characters.

Subclasses may override. The table MUST have the same name as the one returned by the method
:ref:`getTableName <bhenk\msdata\abc\AbstractDao::getTableName>`.



.. code-block:: php

   public function getCreateTableStatement(): string


| :tag6:`return` string  - the :tech:`CREATE TABLE` sql
| :tag6:`throws` `ReflectionException <https://www.php.net/manual/en/class.reflectionexception.php>`_


----


.. _bhenk\msdata\abc\AbstractDao::getDataObjectName:

AbstractDao::getDataObjectName
------------------------------

.. table::
   :widths: auto
   :align: left

   ========== ================= 
   predicates public | abstract 
   ========== ================= 


**Get the fully qualified classname of the** :ref:`bhenk\msdata\abc\Entity` **this class provides access to**


.. code-block:: php

   public abstract function getDataObjectName(): string


| :tag6:`return` string  - fully qualified classname


----


.. _bhenk\msdata\abc\AbstractDao::insert:

AbstractDao::insert
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Insert the given Entity**


The :tech:`ID` of the :ref:`bhenk\msdata\abc\Entity` (if any) will be ignored. Returns an Entity equal to the
given Entity with the new :tech:`ID`.



.. code-block:: php

   public function insert(
         Parameter #0 [ <required> bhenk\msdata\abc\Entity $entity ]
    ): Entity


| :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$entity` - Entity to insert
| :tag6:`return` :ref:`bhenk\msdata\abc\Entity`  - new Entity, equal to given one, with new :tech:`ID`
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 201


----


.. _bhenk\msdata\abc\AbstractDao::insertBatch:

AbstractDao::insertBatch
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Insert the Entities from the given array**


The :tech:`ID` of the :ref:`Entities <bhenk\msdata\abc\Entity>` (if any) will be ignored. Returns an array of
Entities equal to the
given Entities with new :tech:`ID`\ s and ID as array key. This default behaviour can be altered by
providing a closure that receives each inserted entity and decides what key will be returned:

..  code-block::

   $func = function(Entity $entity): int {
       return  $entity->getID();
   };





.. code-block:: php

   public function insertBatch(
         Parameter #0 [ <required> array $entity_array ]
         Parameter #1 [ <optional> ?Closure $func = NULL ]
    ): array


| :tag6:`param` array :param:`$entity_array` - array of Entities to insert
| :tag6:`param` ?\ `Closure <https://www.php.net/manual/en/class.closure.php>`_ :param:`$func` - function to assign key in the returned array
| :tag6:`return` array  - array of Entities with new :tech:`ID`\ s
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 201


----


.. _bhenk\msdata\abc\AbstractDao::update:

AbstractDao::update
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Update the given Entity**


.. code-block:: php

   public function update(
         Parameter #0 [ <required> bhenk\msdata\abc\Entity $entity ]
    ): int


| :tag6:`param` :ref:`bhenk\msdata\abc\Entity` :param:`$entity` - persisted Entity to update
| :tag6:`return` int  - rows affected: 1 for success, 0 for failure
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 202


----


.. _bhenk\msdata\abc\AbstractDao::updateBatch:

AbstractDao::updateBatch
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Update the Entities in the given array**


.. code-block:: php

   public function updateBatch(
         Parameter #0 [ <required> array $entity_array ]
    ): int


| :tag6:`param` array :param:`$entity_array` - array of persisted Entities to update
| :tag6:`return` int  - rows affected
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 202


----


.. _bhenk\msdata\abc\AbstractDao::delete:

AbstractDao::delete
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Delete the row with the given ID**


.. code-block:: php

   public function delete(
         Parameter #0 [ <required> int $ID ]
    ): int


| :tag6:`param` int :param:`$ID` - the :tech:`ID` to delete
| :tag6:`return` int  - rows affected: 1 for success, 0 if :tech:`ID` was not present
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 203


----


.. _bhenk\msdata\abc\AbstractDao::deleteBatch:

AbstractDao::deleteBatch
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Delete rows with the given IDs**


.. code-block:: php

   public function deleteBatch(
         Parameter #0 [ <required> array $ids ]
    ): int


| :tag6:`param` array :param:`$ids` - array with IDs of persisted entities
| :tag6:`return` int  - affected rows
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 203


----


.. _bhenk\msdata\abc\AbstractDao::select:

AbstractDao::select
-------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Fetch the Entity with the given ID**


.. code-block:: php

   public function select(
         Parameter #0 [ <required> int $ID ]
    ): ?Entity


| :tag6:`param` int :param:`$ID` - the :tech:`ID` to fetch
| :tag6:`return` ?\ :ref:`bhenk\msdata\abc\Entity`  - Entity with given :tech:`ID` or *null* if not present
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 204


----


.. _bhenk\msdata\abc\AbstractDao::selectBatch:

AbstractDao::selectBatch
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Select Entities with the given IDs**


The returned Entity[] array has Entity IDs as keys.



.. code-block:: php

   public function selectBatch(
         Parameter #0 [ <required> array $ids ]
    ): array


| :tag6:`param` array :param:`$ids` - array of IDs of persisted Entities
| :tag6:`return` array  - array of Entities or empty array if none found
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 204


----


.. _bhenk\msdata\abc\AbstractDao::deleteWhere:

AbstractDao::deleteWhere
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Delete Entity rows with a** *where-clause*



..  code-block::

   DELETE FROM %table_name% WHERE %expression%





.. code-block:: php

   public function deleteWhere(
         Parameter #0 [ <required> string $where_clause ]
    ): int


| :tag6:`param` string :param:`$where_clause` - expression
| :tag6:`return` int  - rows affected
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 203


----


.. _bhenk\msdata\abc\AbstractDao::selectWhere:

AbstractDao::selectWhere
------------------------

.. table::
   :widths: auto
   :align: left

   ========== ====== 
   predicates public 
   ========== ====== 


**Select Entities with a** *where-clause*



..  code-block::

   SELECT FROM %table_name% WHERE %expression%


The optional :tagsign:`param` :tech:`$func` receives selected Entities and can decide what key
the Entity will have in the returned Entity[] array.
Default: the returned Entity[] array has Entity IDs as keys.

..  code-block::

   $func = function(Entity $entity): int {
       return  $entity->getID();
   };





.. code-block:: php

   public function selectWhere(
         Parameter #0 [ <required> string $where_clause ]
         Parameter #1 [ <optional> ?Closure $func = NULL ]
    ): array


| :tag6:`param` string :param:`$where_clause` - expression
| :tag6:`param` ?\ `Closure <https://www.php.net/manual/en/class.closure.php>`_ :param:`$func` - if given decides which keys the returned array will have
| :tag6:`return` array  - array of Entities or empty array if none found
| :tag6:`throws` `Exception <https://www.php.net/manual/en/class.exception.php>`_  - code 204


----

:block:`Sun, 09 Apr 2023 10:39:06 +0000` 
