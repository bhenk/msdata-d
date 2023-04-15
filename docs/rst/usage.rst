Usage
=====

.. contents::

Installation
++++++++++++

.. code-block::

   composer require bhenk/msdata

Configuration
+++++++++++++

See :ref:`bhenk\msdata\connector\MysqlConnector`

Persist your data
+++++++++++++++++

* Extend the Data Object :ref:`bhenk\msdata\abc\Entity` \
* Extend the Data Access Object :ref:`bhenk\msdata\abc\AbstractDao` \
* Run :ref:`bhenk\msdata\abc\AbstractDao::createTable` once. \

Creation of tables
------------------

Data types *string*, *int*, *bool* and *float*, when used in Entities will
be converted to proper database types automatically. Caveat: String is
converted to *VARCHAR(255)*. If your strings are larger you'll have to
override the method :ref:`bhenk\msdata\abc\AbstractDao::getCreateTableStatement` and provide
your own.

Simple Data Objects
-------------------

msData is on purpose kept simple. There will be one table for each
Data Object. Data Objects have only primitive types: *string*, *int*,
*bool* and *float*. Extend Entity, create a constructor for all your
Data Object fields, generate getters and setters and you're basically done.
The corresponding Data Access Object needs only implementing of two methods:
:ref:`bhenk\msdata\abc\AbstractDao::getDataObjectName` and
:ref:`bhenk\msdata\abc\AbstractDao::getTableName`.

Complexity in your business layer
---------------------------------

Mixes of different entities is foreseen to take place in
your business layer. For instance PersonWithAddress(es) is a mix of the
Data Objects PersonDo and (one or more) AddressDo. Objects like Date,
Time, DateTime etc. are represented in your Data Objects as strings,
or other primitive types,
though they may be represented in the database with appropriate types
and queried as such, and in your business layer again as
`DateTimeImmutable(s) <https://www.php.net/manual/en/class.datetimeimmutable.php>`_
etc.

Relations
---------

The Data Access Object :ref:`bhenk\msdata\abc\AbstractJoinDao` and Data Object :ref:`bhenk\msdata\abc\Join` can be
used to express many-to-many relationships, based on a join-table with foreign keys. Below is a complete diagram
covering database, data-layer and business-layer.

.. image:: /img/many_to_many.svg
   :alt: Many-to-many relationship over 3 layers

Business Objects (Bo's) are created by their corresponding Store Object. Store Objects rely on their
Data Access Object (Dao) to materialize the type of Bo. Bo's have a dependency on their
Relations Object  which in turn has a dependency on the opposite Store Object, in order to materialize the
opposite ends of the relation. Relations Objects may have lazy methods to fetch their (Join) Data Objects and
related Bo's, in order to keep database traffic at a minimum.

After a Store Object has persisted a Bo, it calls on the Relations Object to persist the relations.

A Relations Object may keep track of more than one type of relation, so Bo's can have multiple relations
to multiple other Bo-types. For each type of relation the Relations Object than has distinguished Dao's and Do's,
backed by separate join tables.

Although there are no objections to complete symmetry, adding and removing of relations is often done
from one side only, while the other side has readonly methods on their Relations Object. So for
instance a Person can add and remove Addresses, while from the Address Object you can only obtain which
Persons are living or working there.