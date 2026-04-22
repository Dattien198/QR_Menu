# QR Menu Platform - User Stories

This document outlines the user stories for the QR Menu platform, structured by epics. These stories reflect the multi-tenant architecture, KDS integration, menu management, and role-based access control.

## Roles

*   **Platform Admin:** Manages the overall SaaS platform, subscriptions, and global settings.
*   **Restaurant Admin:** Manages a specific restaurant's settings, branches, menus, and staff.
*   **Branch Manager:** Manages day-to-day operations of a specific branch.
*   **Kitchen Staff (KDS):** Uses the Kitchen Display System to fulfill orders.
*   **Waitstaff:** Manages tables, assists customers, and handles offline orders or payments.
*   **Customer:** Scans the QR code, browses the menu, places orders, and manages their profile.

---

## Epic 1: Multi-Tenant Restaurant & Branch Management

*   **As a Platform Admin,** I want to create and manage multiple restaurant accounts so that I can onboard new clients to the SaaS platform.
*   **As a Restaurant Admin,** I want to create multiple branches (locations) for my restaurant so that I can manage localized operations separately.
*   **As a Restaurant Admin,** I want to assign staff to specific roles and branches so that role-based access control restricts users to only their authorized locations and features.
*   **As a Branch Manager,** I want to view an activity log for my branch so that I can monitor staff actions and system events.

## Epic 2: Table & QR Code Management

*   **As a Branch Manager,** I want to generate infinite restaurant tables within my branch so that the system maps physical dining layouts digitally.
*   **As a Branch Manager,** I want to generate and print unique QR codes for each table so that customers can scan them to access the ordering system.
*   **As a Waitstaff,** I want to view the real-time status of all tables in my assigned branch (e.g., vacant, occupied, order placed) so that I can efficiently manage dining flow.
*   **As a Waitstaff,** I want to manually open or close a table session so that I can control ordering availability when a party arrives or leaves.

## Epic 3: Menu & Inventory Management

*   **As a Restaurant Admin,** I want to create and organize Menu Items into Categories so that customers can easily browse the food and beverage offerings.
*   **As a Restaurant Admin,** I want to associate Add-ons and modifiers to Menu Items so that customers can customize their orders (e.g., "Extra cheese", "No onions").
*   **As a Restaurant Admin,** I want to configure Discounts and apply them to specific items or categories during happy hours or promotions so that I can drive sales.
*   **As a Branch Manager,** I want to mark specific menu items as "out of stock" at my branch so that customers do not order items we cannot fulfill.

## Epic 4: Customer Experience & Ordering

*   **As a Customer,** I want to scan a QR code on my table to view the digital menu without downloading a dedicated app.
*   **As a Customer,** I want to browse menu categories, view item details, and select add-ons before adding items to my cart.
*   **As a Customer,** I want to manage my personal profile (if registered) so that I can view my order history and save payment methods.
*   **As a Customer,** I want to make a future reservation through the platform so that I can secure a table in advance.
*   **As a Customer,** I want to leave a review for my meal so that I can provide feedback on my dining experience.

## Epic 5: Kitchen Display System (KDS) Integration

*   **As a Kitchen Staff member,** I want the KDS to receive and display new orders instantly when a customer checks out so that we can begin prep without delay.
*   **As a Kitchen Staff member,** I want to see the specific add-ons and modifiers for each order item clearly on the KDS so that dishes are prepared accurately.
*   **As a Kitchen Staff member,** I want to mark individual order items or entire orders as "Preparing" and "Ready" so that the waitstaff knows when food is ready to be delivered.
*   **As a Waitstaff,** I want to receive notifications when an assigned table's order is marked "Ready" by the kitchen.

## Epic 6: Checkout & Payments

*   **As a Customer,** I want to seamlessly pay for my order directly from my phone via a digital gateway so that I do not have to wait for the check.
*   **As a Customer,** I want the option to split the bill with my tablemates or request to pay by cash/card to the waiter.
*   **As a Branch Manager,** I want to view real-time payment statuses for all active orders so that I can ensure all tabs are settled before a table is cleared.

## Epic 7: System Administration & UI/UX

*   **As a Restaurant Admin,** I want the customer-facing interface to reflect my brand's visual identity so that the experience feels premium and native to my restaurant.
*   **As a Platform Admin,** I want a clean, unified dashboard combining the KDS and role administration to minimize the learning curve for restaurant operators.
