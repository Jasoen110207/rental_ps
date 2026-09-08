Design a complete responsive web application UI for **“TambahBang — Smart POS & Real-Time Monitoring Rental PlayStation”**, a Point of Sale and PlayStation rental management system.

The application is primarily used by CASHIERS on desktop/laptop and by CUSTOMERS on mobile through QR codes.

Do NOT design this as a generic SaaS analytics dashboard. The main experience must feel like a real-time operational control center for a PlayStation rental business.

---

## DESIGN STYLE

Use a modern **Neo-Brutalist UI**.

Visual direction:
- Bold visual hierarchy
- Thick black or dark borders
- Hard shadows / offset shadows
- Geometric cards
- Minimal rounded corners
- High contrast
- Playful gaming personality
- Operational and highly readable
- Avoid excessive gradients
- Avoid glassmorphism
- Avoid generic corporate SaaS aesthetics

Primary colors:
- Strong electric/royal blue
- Vibrant orange

Supporting colors may be used for semantic states:
- Green = available / successful
- Yellow or orange = warning
- Red = expired / critical
- Neutral gray = disabled / inactive

Do not communicate status using color alone. Combine colors with labels and icons.

Typography:
- Bold sans-serif headings
- Highly readable body text
- Large timer digits
- Strong numeric hierarchy

Use Lucide-style icons where appropriate.

---

# APP SHELL

Create a desktop cashier application with:

LEFT SIDEBAR:
- TambahBang logo
- Dashboard
- Rental / POS
- Requests
- F&B
- Transactions
- Shifts
- Units
- Settings

BOTTOM SIDEBAR:
- Logged-in cashier identity
- Active shift indicator
- Logout

TOP BAR:
- Page title
- Current date/time
- WebSocket / Real-Time connection status
- Notification button with unread badge
- Current shift information

---

# SCREEN 1 — LOGIN

Create a bold neo-brutalist cashier login page.

Elements:
- TambahBang brand
- “Rental PS Management System”
- Username/email input
- Password input
- Show/hide password
- Login button
- Small system status indicator

Keep it simple and visually memorable.

---

# SCREEN 2 — MAIN REAL-TIME DASHBOARD

This is the MOST IMPORTANT screen.

Design it as an operational monitoring board.

Header:
- “Rental Dashboard”
- Current shift
- Current cashier
- Number of available units
- Number currently playing
- Number almost finished
- Number expired
- Pending request counter

Main content:
Create a responsive grid of PlayStation unit cards.

Example units:
- PS 01
- PS 02
- PS 03
- PS 04
- PS 05
- PS 06
- PS 07
- PS 08

Each card should clearly show:

- Unit name
- PlayStation type
- Current status
- Billing type
- Start time
- Remaining time OR elapsed time
- Large live timer
- Running rental cost
- F&B subtotal if any
- Customer request indicator if any
- Buzzer state if active

Card states:

1. AVAILABLE
   - Strong available badge
   - “Start Rental” primary button

2. PLAYING — PREPAID
   - Large countdown timer such as 01:24:32
   - Prepaid badge
   - Start/end time
   - Running total
   - Extend Time
   - Add F&B
   - View Session

3. PLAYING — POSTPAID / LOSS
   - Large elapsed timer
   - “POSTPAID / LOSS” badge
   - Current running bill
   - Add F&B
   - Stop Rental
   - View Session

4. ALMOST FINISHED
   - Highly visible warning state
   - Large remaining timer e.g. 00:08:42
   - Warning icon
   - Extend Time CTA

5. TIME UP
   - Critical visual treatment
   - “TIME UP” label
   - 00:00:00 timer
   - Buzzer active indicator
   - Extend
   - Checkout
   - Turn Off Buzzer

6. DISABLED
   - Neutral muted appearance
   - Disabled badge

The user must be able to understand the room status in under 5 seconds.

---

# SCREEN 3 — START RENTAL MODAL

Clicking an available unit opens a modal / side panel.

Title:
“Start Rental — PS 03”

Fields:
- Unit
- Billing Mode

Billing mode segmented selector:
[ PREPAID ] [ POSTPAID / LOSS ]

If PREPAID:
- Duration selector
  - 1 Hour
  - 2 Hours
  - 3 Hours
  - Custom
- Rate
- Estimated end time
- Rental subtotal

If POSTPAID:
- Hourly rate
- Start time
- Explanation that billing is calculated when rental ends

Primary CTA:
“Start Rental”

Secondary:
“Cancel”

---

# SCREEN 4 — ACTIVE RENTAL DETAIL

Create a detail panel/page for an active session.

Top:
- PS 03
- ACTIVE badge
- Billing type
- Large timer

Session details:
- Session ID
- Start time
- End time where applicable
- Duration
- Rental rate
- Rental subtotal

F&B section:
Table:
- Product
- Quantity
- Price
- Subtotal

Billing summary:
- Rental
- F&B
- Grand Total

Actions:
- Extend Time
- Add F&B
- Stop Session
- Checkout
- Buzzer On/Off

---

# SCREEN 5 — EXTEND TIME

Create an extension modal.

Display:
- Unit
- Current remaining time
- Current end time

Extension buttons:
- +30 minutes
- +1 hour
- +2 hours
- Custom

Display:
- Added cost
- New end time
- New estimated total

CTA:
“Confirm Extension”

---

# SCREEN 6 — ADD F&B POS

Create an integrated F&B POS interface.

Left:
Product catalog grid.

Example products:
- Mie Instan
- Es Teh
- Kopi
- Air Mineral
- Snack
- Soft Drink

Each product card:
- Product name
- Price
- Availability
- Add button

Include:
- Search
- Category chips
- Food
- Drinks
- Snacks

Right:
Current order panel.

Show:
- Unit
- Cart items
- Quantity stepper
- Price
- Subtotal

Bottom:
- Total F&B
- “Add to PS 03 Bill” CTA

---

# SCREEN 7 — CUSTOMER REQUEST CENTER

Create a cashier request management screen.

Header:
“Customer Requests”

Tabs:
- Pending
- Approved
- Rejected
- All

Request cards/table rows should include:

- Unit
- Request type
- Request detail
- Submitted time
- Status

Request examples:

PS 03
“Extend Time”
+1 Hour
Pending

PS 07
“Food Order”
2x Es Teh
1x Mie Instan
Pending

Actions:
[ Approve ]
[ Reject ]

New incoming request should feel visually noticeable but not distracting.

---

# SCREEN 8 — NOTIFICATION PANEL

Create a slide-over notification center.

Examples:
- “PS 04 has 10 minutes remaining.”
- “New +1 hour request from PS 03.”
- “New F&B order from PS 07.”
- “PS 01 rental time has expired.”

Each notification:
- Icon
- Type
- Unit
- Timestamp
- Read/unread state
- Relevant CTA

---

# SCREEN 9 — CHECKOUT

Create a checkout modal/page.

Header:
“Checkout — PS 03”

Rental information:
- Billing type
- Start
- End
- Total duration

Rental charges:
- Rental duration
- Rate
- Rental subtotal

F&B table:
- Product
- Qty
- Unit Price
- Subtotal

Summary:
Rental         Rp ...
Food & Drink   Rp ...
--------------------
GRAND TOTAL    Rp ...

Make the grand total visually dominant.

Actions:
- Complete Payment
- Back to Session

Do not invent a specific payment gateway.

If payment method selection is required in the mockup, use neutral placeholders:
- Cash
- Other

Clearly treat these as configurable options.

---

# SCREEN 10 — TRANSACTION HISTORY

Create transaction history.

Toolbar:
- Search
- Date filter
- Billing type filter
- Unit filter

Table columns:
- Transaction ID
- Unit
- Cashier
- Billing Type
- Duration
- Rental
- F&B
- Total
- Date
- Status

Clicking a row opens transaction detail.

---

# SCREEN 11 — SHIFT MANAGEMENT

Create a shift management screen.

Active shift card:
- Cashier
- Start time
- Active rentals
- Current sales summary
- Shift status

Action:
“End Shift”

Ensure active PlayStation sessions are explicitly shown as continuing after shift change.

Add shift history table:
- Cashier
- Start
- End
- Transactions
- Revenue
- Status

---

# SCREEN 12 — UNIT MANAGEMENT

Create rental unit management.

Grid/table fields:
- Unit name
- PlayStation type
- Rental rate
- Status
- QR Code
- Buzzer state

Actions:
- Edit
- Enable / Disable
- View QR
- Test Buzzer

Include a QR preview modal suitable for printing and placing on the rental desk.

---

# SCREEN 13 — F&B MANAGEMENT

Create a simple product management page.

Fields:
- Product image placeholder
- Product name
- Category
- Price
- Availability

Actions:
- Add product
- Edit
- Enable/disable

---

# MOBILE CUSTOMER EXPERIENCE

The customer page is accessed by scanning a QR Code.

No login is required.

Design for approximately 390px mobile width.

Do not reuse the desktop admin navigation.

---

# SCREEN 14 — CUSTOMER SESSION HOME

Mobile header:
- TambahBang
- PS 03
- ACTIVE

Hero:
Large remaining timer:
01:24:32

Below:
- Billing mode: PREPAID
- Session start
- Session end
- Current rental status

Primary actions as large touch-friendly cards:

[ + REQUEST EXTRA TIME ]

[ 🍜 ORDER FOOD & DRINK ]

Secondary section:
“Your Requests”

Display request status:
- Pending
- Approved
- Rejected

---

# SCREEN 15 — MOBILE EXTEND TIME REQUEST

Header:
“Tambah Waktu”

Display:
Current remaining time.

Options:
- +30 Minutes
- +1 Hour
- +2 Hours

Show:
- Estimated extra charge
- Requested new end time

CTA:
“Send Request”

Important:
Make it clear that the extension requires cashier approval.

After submission show:
“Request sent — waiting for cashier approval.”

---

# SCREEN 16 — MOBILE F&B MENU

Mobile food ordering experience.

Header:
“Food & Drinks”

Search bar.

Category chips:
- All
- Food
- Drinks
- Snacks

Product cards:
- Item
- Price
- Add button

Sticky cart bar:
“3 items · Rp xx.xxx — View Cart”

---

# SCREEN 17 — MOBILE CART

Show:
- Product
- Quantity
- Price
- Subtotal

Order total.

CTA:
“Send Order Request”

Explain:
“The cashier will review your order.”

---

# SCREEN 18 — MOBILE REQUEST STATUS

Create clear request cards.

Example:

EXTEND TIME
+1 Hour

Status:
PENDING

“Waiting for cashier approval.”

Another example:

FOOD ORDER
2 × Es Teh
1 × Mie Instan

Status:
APPROVED

---

# INTERACTION DETAILS

Use realistic UI states and micro-interactions.

Real-time examples:
- Unit timer continuously counts down/up.
- Newly incoming request receives a short visual highlight.
- Request counter updates instantly.
- Approving an extension immediately changes the unit timer.
- Time-warning state visually changes as deadline approaches.
- When time reaches zero, card enters TIME UP state.
- Buzzer indicator appears when buzzer command is active.
- Completing checkout changes unit back to AVAILABLE.

Use toast notifications for important successful actions:
- Rental started
- Extension approved
- F&B added
- Payment completed
- Buzzer disabled

Use confirmation dialogs for destructive/critical actions:
- Stop rental
- Complete checkout
- Disable unit
- Reject customer request

---

# RESPONSIVE BEHAVIOR

Desktop dashboard:
- 4 unit cards per row on wide desktop when possible
- 3 on smaller desktop
- 2 on tablet

Customer:
- Mobile-first
- Single-column
- Large touch targets
- Sticky bottom CTA where useful

---

# FRONTEND IMPLEMENTATION CONTEXT

The design will eventually be implemented with:

- Laravel 13
- Laravel Blade
- Tailwind CSS
- Alpine.js
- Laravel Reverb / WebSocket
- Vite

Therefore:
- Favor reusable UI components.
- Avoid patterns that require a heavy frontend SPA framework.
- Design interactions that can realistically be implemented using Blade + Alpine.js.
- Treat real-time status updates as WebSocket-powered states.

Recommended reusable UI components:
- UnitCard
- StatusBadge
- LiveTimer
- RequestCard
- NotificationItem
- BillingSummary
- ProductCard
- CartItem
- Modal
- Drawer
- ConfirmationDialog
- Toast
- EmptyState
- QRCodeCard

---

# UX PRIORITY

The highest-priority user experience is the CASHIER DASHBOARD.

A cashier should be able to glance at the dashboard and immediately answer:

1. Which PlayStation units are available?
2. Which ones are playing?
3. Which sessions are almost finished?
4. Which sessions have expired?
5. Which customers are requesting something?
6. Which units have an active buzzer?
7. How much time remains for each active prepaid session?

Prioritize operational clarity over decorative visuals.

Generate a polished, high-fidelity, production-ready UI covering both the cashier desktop experience and customer mobile QR experience.