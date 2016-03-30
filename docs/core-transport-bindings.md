Magento Shared Services – Core Transport Bindings
=================================================
*Version 0.1-draft*

# Introduction

This specification defines core transport bindings used by Magento Shared Services definitions. It is not required that implementations of this standard support all defined transports, but supporting one than one transport can make integreations easier. The default recommended transport for implementors of this standard is JSON over AMQP.

# JSON over AMQP

This section defines the encoding of service requests and responses in JSON and the delivery of such requests and responses over AMQP. A popular implementation of AMQP is RabbitMQ.

## JSON

JSON was selected over XML as it is generally more compact in its encoding. As requests and responses are machine generated, there is no significant advantage in validating messages using XSD or similar.

Data entities are encoded in JSON as follows. These rules have been designed to be compatible with "Service Contracts" in Magento Digital Commerce (MDC).

### Simple Types

- Values of type `string` are encoded as JSON strings.
- Values of type `integer` are encoded as JSON strings.
- Values of type `float` are encoded as JSON strings.
- Values of type `boolean` are encoded as a JSON string holding "0" or "1".
- Values of type `datetime` are encoded as a JSON string in ISO-8601 format.
- Values of type `date` are encoded as a JSON string in ISO-8601 format.
- Values of type `blob` are encoded as a base64 encoded JSON string.

### Built In Parameterized Types

- Values of type `array[T]` are encoded as a JSON array.
- Values of type `map[T]` are encoded as a JSON object.

### Structures

- Structures are encoded as JSON objects. Property names are used as the member name in JSON.

### Extensions

- If a structure has one or more extensions, an `extension_attributes` member is added to the JSON object holding the structure properties. The value for this member is a nested JSON object where member names are the extension name and the value is a JSON object holding the extension properties.
- The properties in an extension are encoded using the same rules as structures.

Example

```
{
    ... structure properties …
    "extension_attributes": {
        "magento.catalog.my_extension": {
            ... extension properties ...
        }
    }
}
```

### Dynamic Properties

Dynamic properties are how Magento Digital Commerce (MDC) "custom attributes" are stored. This specification uses the same encoding as MDC for compatibility reasons.

All dynamic properties for a structure are stored in a JSON member called `custom_attributes`. This member holds an array of JSON objects. Each JSON object is encoded with two members, `attribute_code` holding the dynamic property name and `value` holding the JSON encoding of the value.

# Service Requests and Responses (JSON)

Service requests (command and query requests) arguments are encoded in the same was as structure properties.

# Routing (AMQP)

There are several types of interactions corresponding to commands, queries, and events.

The JSON encoding of service requests (command and query requests) are transported over AMQP as the message payload. TODO: The topic name is set to XXX. In addition, the “To” header must be set to XXX. This standard does not define how an implementation determines the target of a request.

For synchronous requests (the norm), the "Reply-To" header must be populated with the queue name which the service will send the response message to. For asynchronous requests (ones where no response is required) the "Reply-To" header is omitted. The choice of queue topology (one shared queue, load balancing, multiple consumers, use of temporary queues, TODO: USE RabbitMQ Terminology here) is not defined by this Specification.

For events, the JSON payload is encoded as per requests above, but the `Reply-To` header is never set and it is anticipated that multiple consumers may be configured to process the request. The `To` header is always set to `*`. TODO: REWORK AS APPROPRIATE.

# JSON over HTTP

HTTP can be used as a transport protocol instead of AMQP. 

## JSON

The JSON encoding is identical to that of "JSON over AMQP" above.

## HTTP

For service requests (commands and queries), the HTTP header `Content-Type` is always set to `application/json`. Requests are always sent via HTTP POST to a specified URL.

For service responses (commands and queries), the response header `Content-Type` is always set to `application/json`. The HTTP status code is set to 200 on success. Note that the HTTP status code is used to represent the successful transport of a response. The HTTP status code is not used to indicate the request encountered problems during processing. A status code other than 200 indicates there is no JSON encoded response available for the client to parse and use. A useful convention is for the response to instead be returned with a content type of `text/plain` and the payload to be an error message to help diagnose the problem that occurred. 

For synchronous requests, the JSON response is returned as the payload of the HTTP response. For asynchronous requests, the HTTP response payload is empty (content length is zero).

For events where broadcast semantics are desired, the service must support configuration allowing multiple end points to be specified. A separate HTTP call is made to end point.

One of the reasons AMQP is preferred over HTTP is it avoids circumstances such as an event being partially broadcast to a subset of the targets in the event of a system failure. AMQP is also more robust in that if the target is temporarily unavailable the message is queued for later delivery. However in some circumstances this is not as important. For example, queries (which are by definition idempotent) may map well to HTTP requests, with commands and events mapping well to AMQP requests. This standard does not define a requirement as to which transports must be supported, and a single service may be configured to support a mix of different transport schemes.
