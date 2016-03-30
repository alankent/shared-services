Magento Shared Services = Overview
==================================
*Version 0.1-draft*

# Introduction

Magento started life as an open source platform for developing online web stores. The base platform supported common online commerce concepts such as catalogs, product information, shopping carts, and more. Not only was this base platform made available as open source, it also was designed to be extended by third parties. This meant that merchants were not limited by what was provided out of the box. The platform could be adapted to the widely varying needs of different merchants.

The Magento Shared Service definitions are at a level higher, allowing systems to interact without source code level integrations. Instead of only addressing web stores, the Magento brand now covers all of commerce – online and off line. The web store technology, now referred to as "Magento Digital Commerce", is still open source. Other product offerings have been added however that are provided as Software as a Service (SaaS) offerings. Rather than extend the core of these products, instead there are core SaaS components to the offering as well as external services that can be added customized for specific merchant needs, much like the web store. Instead of allowing extensions at the PHP code level, extensions are now made at a higher level allowing a combination of SaaS and deployed technologies to be used to develop a single merchant solution giving merchants the choice of both convenience of SaaS with the ultimate flexibility of deployed code.

Magento Shared Services cre defined by a set of specifications that define how different “systems” within a commerce technology stack interact. Anyone following these specifications can then interact with other parts of the system. Keeping with its open source roots, these are open specifications available directly to the community. This allows third party solution providers to tightly integrate with other systems to build up complete commerce solutions.

The Magento Shared Service definitions are also designed to be extensible. A core set of specification is provided, but other parties are free to define their own specifications to extend the core set of services defined. In addition, a particular installation can extend the core data elements by defining additional data elements specific to that installation. These extended data elements can be captured in a local specification file enabling sites to complete capture the interaction patterns and data entities used for that specific site. Extensions to products may similarly add additional data elements.

# Terminology

This section introduces the core terminology used by these specifictions.

## Services

The Magento Shared Services define a set of communicating services. Service specifications define the inbound requests a conforming service must implement and a set of out bound notifications a service can provide to interested parties.

Interactions between clients and services are formally defined by service specifications. This is done using a programming language neutral specification language. The encoding of this specification is currently in XML, allowing validation using tools via an XSD specification.

There are two forms of in bound requests supported:

- **Commands** are used to request the service to take some action. An example of a command is “create an order”. Commands change the state of the service.
- **Queries** are used to request information from a service. An example of a query is “retrieve product information”. Queries are idempotent – they do not change state of the service.

The differentiation between commands and queries is primarily one of semantics. Both involve a request/response interaction. Some commands may be asynchronous – where no response is required. In such cases, other mechanisms are required to verify the success or failure of the command. Most commands however return a response. For example a "create order" command would return an order id allowing the client to make subsequent requests about the order if desired.

The third type of interaction that forms a service definition:

- **Events** are notifications that are broadcast by services to all interested listeners in a publisher-subscriber model. For example, after an order is created by a server, it may publish a “order created” event indicating the successful creation of a new order.

For clarity, both commands and queries are sent to a specific service for processing. There is one client and one service instance in the interaction where the service responds to the client. For events, the service broadcasts the notification to all registered subscribers.

Services are organized into "modules". For example, one service specification may cover the creation of product details and another service may cover the querying of product details. Both services may be included in the one “product information management” module. It is not required that a system implement all services within a module.

Service specifications are sometimes referred to as "Layer 2" specifications (with "Layer 1" referring to transport, covered in the next section).

## Transport

A part of these specifications is the definition of multiple transport specifications. Such specifications are often referred to as "Layer 1" specifications. These specifications define the mapping of the abstract service specifications to specific technologies.

For example, one such specification defines the JSON encoding rules of data entities and how the encoded JSON objects are shared across an AMQP message bus implementation such as RabbitMQ. Transport level specifications are not specific to services.

# Organization

The documents that make up the Magento Shared Service definitions are as follows:

- Core Specifications
  - [Overview](overview.md) (this document) – introduces the basic concepts and terminology, and provides references to other more detailed speciations.
  - [Service Specification Language](service-specification-language.md) – covers how service specifications are defined in XML.
- Layer 2 Specifications
  - [Core Service Interaction Flows](core-service-interaction-flows.md) – these show how common commerce operations map to a series of service requests. For example, a user making a purchase on a web store that flows through to an order management system would involve order creation and payment service interactions.
  - Core Service Specifications – these define specific services used as part of an overall interaction. Software systems implement one or more services. These specifications are encoded in machine readable form (XML).
- Layer 1 Specifications
  - [Core Transport Bindings](core-transport-bindings.md) – these define standard ways in how technologies such as HTTP and AMQP can be used to transport commands, queries, and events between clients and services.
