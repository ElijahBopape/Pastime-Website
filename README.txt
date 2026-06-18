# Pastimes Clothing Store

Pastimes is a full-stack web-based marketplace developed for the WEDE6021/W Portfolio of Evidence. The platform provides a structured environment where buyers and sellers can interact through product listings, messaging, shopping cart functionality, and order management.

The system demonstrates practical implementation of PHP, MySQL, HTML5, CSS3, JavaScript, Object-Oriented Programming, CRUD operations, user authentication, session management, and role-based access control.

The goal of the application is to provide a secure and user-friendly platform for buying and selling second-hand branded clothing while giving administrators control over user verification, seller approval, product moderation, and marketplace management.

---

# Project Demo

A full demonstration of the application can be viewed below:

**YouTube Demonstration Video:**
https://youtu.be/jO24LfHMPiA 

---

# Source Code Repository

# User Roles

The system supports three different user roles:

* Buyer
* Seller
* Administrator

Each role has specific permissions and functionality within the system.

---

# Buyer Workflow

### Registration

1. Register as a Buyer.
2. Complete all required fields.
3. Wait for administrator verification.
4. Log in using a username or email address together with a password.

### Shopping Experience

After logging in, buyers can:

* Browse approved clothing products.
* View detailed product information.
* View product images.
* Search available listings.
* Add products to a shopping cart.
* Update product quantities.
* Remove products from the cart.
* Complete the checkout process.
* Enter delivery information.
* View order details.

### Messaging Sellers

Buyers can communicate directly with sellers regarding specific products.

Messaging features include:

* Product-specific conversations.
* Direct buyer-seller communication.
* Message history.
* Product-linked enquiries.

This allows buyers to ask questions about sizing, availability, condition, pricing, and delivery before making a purchase.

---

# Seller Workflow

### Seller Registration

1. Register as a Seller.
2. Complete all required registration fields.
3. Await administrator approval.

Only approved sellers are permitted to upload products to the marketplace.

### Seller Dashboard

Approved sellers gain access to a dedicated Seller Dashboard containing:

* Product management tools.
* Product upload functionality.
* Messaging centre.
* Listing management features.

### Product Upload Process

Sellers can upload:

* Product image
* Product name
* Product description
* Product price
* Product category
* Product condition

Uploaded products are placed into a pending state and remain invisible to buyers until approved by an administrator.

### Seller Communication

Sellers can:

* View buyer enquiries.
* Respond to messages.
* Manage conversations linked to specific products.

---

# Administrator Workflow

### Administrator Login

Administrators can access the management dashboard through:

```text
admin_login.php
```

### Administrator Responsibilities

Administrators have full control over the marketplace and can:

* Verify buyer accounts.
* Approve seller accounts.
* Reject seller applications.
* Approve product listings.
* Remove products.
* Mark products as sold.
* Manage users.
* Monitor marketplace activity.
* Review customer orders.
* Manage platform operations.

### Product Moderation

All seller-uploaded products must be approved before becoming publicly visible.

This ensures:

* Better marketplace quality.
* Reduced spam listings.
* Improved platform security.
* Consistent product standards.

---

# Key Features

## User Authentication

* User registration
* User login
* Session management
* Password validation
* User verification
* Role-based access control

## Product Management

* Product uploads
* Product approval workflow
* Product browsing
* Product search functionality
* Product image management
* Product status management

## Shopping Cart

* Add products
* Remove products
* Update quantities
* Cart management
* Checkout functionality

## Messaging System

* Buyer-seller communication
* Product-linked conversations
* Message management
* Conversation tracking

## Order Management

* Delivery information capture
* Order creation
* Order tracking
* Administrative order monitoring

## Administration

* User management
* Seller approval
* Product approval
* Marketplace moderation
* Customer verification

---

# Additional Features Implemented

The following enhancements were implemented beyond the minimum requirements of the assessment brief:

### Product Approval Workflow

All seller products require administrator approval before publication.

### Dedicated Seller Dashboard

Separate dashboard designed specifically for seller activities.

### Product-Based Messaging

Messages are linked directly to specific clothing items.

### Enhanced User Interface

* Responsive layouts
* Dashboard-based navigation
* Modern product cards
* Improved user experience
* Consistent styling

### Order and Delivery Management

The system captures and stores customer delivery details during checkout for administrative review and order fulfilment.

### Role-Based User Experience

Different dashboards and functionality are displayed depending on whether the user is a Buyer, Seller, or Administrator.

---

# Technology Stack

### Front-End

* HTML5
* CSS3
* JavaScript

### Back-End

* PHP

### Database

* MySQL

### Development Environment

* XAMPP
* phpMyAdmin

---

# Database Structure

### Database Name

```sql
ClothingStore
```

### Main Tables

* tblUser
* tblAdmin
* tblProduct
* tblCart
* tblOrder
* tblMessage

The database supports user management, product listings, shopping cart functionality, messaging, and order processing.

---

# Installation Guide

### Step 1

Install XAMPP.

### Step 2

Start:

* Apache
* MySQL

### Step 3

Place the project folder inside:

```text
xampp/htdocs/
```

### Step 4

Run:

```text
http://localhost/pastimes/loadClothingStore.php
```

This creates the database and required tables.

### Step 5

Open:

```text
http://localhost/pastimes/
```

### Step 6

Register a new account or login using existing credentials.

---

# Testing Checklist

The following functionality should be tested:

### User Management

* User registration
* User login
* User verification
* Seller approval

### Product Management

* Product upload
* Product approval
* Product display

### Marketplace Features

* Buyer-seller messaging
* Shopping cart operations
* Checkout process
* Order creation

### Administration

* User management
* Product moderation
* Order monitoring

---

# Learning Outcomes Demonstrated

This project demonstrates practical understanding of:

* PHP Programming
* Object-Oriented PHP
* Database Design
* MySQL Integration
* CRUD Operations
* Session Management
* Form Validation
* State Management
* User Authentication
* Role-Based Access Control
* Responsive Web Design
* System Testing and Debugging

---

# License

This project was developed for educational purposes as part of the WEDE6021/W Portfolio of Evidence and is not intended for commercial deployment.

---

# Credits

Developed as part of the Web Development (Intermediate) module to demonstrate the design, implementation, and testing of a full-stack web application.

---

**Pastimes — Giving Quality Clothing a Second Life.**
