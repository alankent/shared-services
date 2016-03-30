Magento Shared Services – Core Service Interaction Flows
========================================================
*Version 0.1-draft*

# Introduction

This document describes common sequences of services calls to achieve various objectives. An example sequence flow is for when a consumer makes a purchase on a web store and the order is sent to an order management system for fulfillment.

The purpose for these flows is to show how different services are typically coordinated, rather than the specifics of any particular service.

# Online Store Order Placement with Separate Order Management System

TODO: Should this be a major heading (Payments) with different sub-headings for specific flows? To better group related flows.

Consider a Magento Digital Commerce (MDC) online web store connected to Magento Commerce Order Management (MCOM) for managing orders. The overall flow is as follows:

TODO: This might be shown better with a UML sequence diagram, but too hard for now.

1. A user browses the online store, adding items to a cart, and finally checking out with the cart.
2. Consumer makes an purchase using one or more payments modules. A payment reference is returned by the payment service. (This could be a “token” or a simpler reference, depending on the service.)
3. MDC sends the order with payment reference to MCOM. `magento.order_service.create_order(order, payment reference)`
4. MCOM verifies the order with the payment service to verify the payment has been processed. `magento.payment_service.validate_payment(payment_reference)`
5. TODO etc.

TODO: Talk about flows for different payment options – COD, bank payments, line of credit, etc.

# ETC

TODO: go through various services and describe interaction flows for each one.
