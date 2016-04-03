Magento Shared Services – Core Service Interaction Flows
========================================================
*Version 0.1-draft*

# Introduction

This document describes common sequences of services calls to achieve various objectives. An example sequence flow is for when a consumer makes a purchase on a web store and the order is sent to an order management system for fulfillment.

The purpose for these flows is to show how different services are typically coordinated, rather than the specifics of any particular service.

# Catalog Related Flows

## Creating a new Product

New products are created by the `magento.catalog.update` command. This command is an "upsert" request (update if a record with the same key already exists, insert if it does not exist). Once the service processes the update, it should send out `magento.catalog.updated` event notifications to subscribers. A PIM integration generating requests for new products will send out such event notifications so that multiple listeners (such as MDC and MCOM) can both listen for product update messges.

## Deleting a Product

Deleting a product is similar to creating/updating a product. A client sends a `magento.catalog.delete` request to the `magento.catalog.catalog_magement` service. If successful, the server broadcasts a `magento.catalog.deleted` event notification.

# Orders

## Online Store Order Placement with Separate Order Management System

Consider a Magento Digital Commerce (MDC) online web store connected to Magento Commerce Order Management (MCOM) for managing orders. The overall flow is as follows:

1. A user browses the online store, adding items to a cart, and finally checking out with the cart.
2. Consumer makes an purchase using one or more payments modules. A payment reference is returned by the payment service. (This could be a “token” or a simpler reference, depending on the service.)
3. MDC sends the order with payment reference to MCOM. `magento.order_service.create_order(order, payment reference)`
4. MCOM verifies the order with the payment service to verify the payment has been processed. `magento.payment_service.validate_payment(payment_reference)`
5. TODO etc.

