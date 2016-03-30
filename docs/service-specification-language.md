Magento Open Commerce Standard – Service Specification Language
===============================================================
*Version 0.1-draft*

# Introduction

Each service defined within the Magento Open Commerce Standard is formally defined by a service specification. Currently service specifications are defined in XML. An XSD is provided to validate specification documents.

# Purpose

Service specifications are encoded in machine readable form allowing automated tools to use the specifications. Example usages include:

- Code generation of client APIs and service implementation skeleton code for various programming languages ensuring consistency between the specification and implementations.
- Online documentation browsing tools.

# Concepts

This section introduces the main concepts of a specification. The following section describes how these concepts are encoded in XML.

## Specification Files

The complete specification for a particular site involves the merging of a series of specification files. Separate files are used for easier management of the specification.

The core specification defines a set of standard files available online for download. Third party products may also define specifications for additional services or extensions to data elements of existing services.

## Modules

Modules are used to namespace service definitions and data types. Module names include a vendor name. TODO: IS IT GOOD OR BAD TO USE “MODULES” LIKE MDC? “DOMAINS” WAS ANOTHER IDEA.  The vendor name `magento` is reserved for use by the core specification. Example module names include `magento.sales` and `magento.orders`.

All services and types are scoped by a module name. For example, a product structure (defined below) defined within the "Catalog" module as specified as a part of the core standard may have a name such as `magento.catalog.product_description`.

## Types

A service is defined as a series of inbound command and query request/response pairs plus a set of outbound event notifications. A service implements the commands and queries, sending notifications out to interested listeners when events of particular note occur.

Structured data is passed as a part of requests and responses. The precise encoding of data is define by a transport binding specification. Example possible encodings include JSON and XML.

### Core Data Types

Core data types are types defined by the Magento Open Commerce Standard and cannot be changed by specifications. Core data types include simple types such as strings and integers.

The following simple types are supported:

- `string`: Zero or more Unicode characters.
- `integer`: A signed integer number such as “34” or “-43”.
- `float`: A signed floating point number.
- `boolean`: "1" for true or "0" for false.
- `datetime`: Date/time in ISO-8601 format.
- `date`: Date in ISO-8601 format.
- `blob`: Base64 encoded 8-bit binary data.

### Built In Parameterized Types

The following parameterized types are supported:

- `array[T]`: An array of values of the specific type.
- `map[T]`: A set of name/value pairs where the name is a string and the value has the specified type.

Examples:

- `array[string]`: An array of strings.
- `map[magento.catalog.product_description]`: A set of product descriptions, keyed by string (which might be the product ID).

### Structure Data Types

Specification data types are defined by service specifications. Types are scoped by "modules" to avoid name space collisions. All specification data types can be used by any other specification (they are not restricted to the specification they are defined within).

Structures are defined by a series of properties. Each property has a name, type, optionality, and more. There is no inheritance model supported by structures.

Structures also support the concept of "extension", similar to the concept of "mixins" in some programming languages (not inheritance as in langauges such as Java). (The name came from Magento Digital Commerce where a third party extension added to a site can extend the set of "attributes" supported by a product.) Extensions are defined much like structures, nominating the base structure to be extended. Only structures marked as "extensible" can be extended.

In addition to extensions, structures may also include "dynamic properties". Dynamic properties can be optionally defined within the specification for clarity, but are treated as opaque name/value pairs by code generation tools. The purpose of dynamic properties is to allow sites to define additional data elements used by that site but which are not included in any existing products. They allow systems to pass through data elements without the system understanding the semantics of the data. For example, an order item may have dynamic properties defined that are provided to an order management system when an order is placed for passing through to a warehouse.

### Parameterized Structure Types

Structured types can also be parameterized. Parameterized structure types are declared with a name followed by `[T]` representing the parameterized type. At this time only a single type parameter is permitted.

Inside a parameterized structure type defintion, `T` is then available as a new type name. For example, a property can be declared as of type `T`, or as a parameterized type such as `array[T]`.

When the structured type is referenced, the type parameter must always be specified. For example, a structured type `by_locale[T]` can never be referenced using `by_locale` without the type parameter.

# Specification XML Structure

TODO: NEED TO DESCRIBE THE XML ENCODING.

