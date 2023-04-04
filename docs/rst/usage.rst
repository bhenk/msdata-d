Usage
=====

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
override the method :ref:`bhenk\msdata\abc\AbstractDao::getCreateTableStatement` with
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