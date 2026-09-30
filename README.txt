CAFEASE - REFERENCE-STYLE PHP PROJECT

This version follows the simple structure/style of the supplied login reference:
- dbconn.php
- index.php / login.php / register.php
- admin/ folder
- user/ folder
- mysqli queries
- PHP sessions
- simple HTML/CSS

SETUP
1. Start Apache and MySQL in XAMPP.
2. Open http://localhost/phpmyadmin
3. Import database.sql.
4. Put this folder inside C:\xampp\htdocs\
5. Open http://localhost/CafeEase_Reference_Project/

DATABASE
Database: cafe_ordering
Tables: accounts, menu, orders, order_items

EXISTING MENU TABLE ATTRIBUTES USED
ID
item_name
category
price
stock
status

DEMO LOGIN
Admin: admin / admin123
User: user / user123

FLOW
Admin: login -> add/update/delete menu -> view/update orders
User: register/login -> view menu -> add to cart -> checkout -> view orders

NOTE
The password handling intentionally follows the supplied classroom reference (plain text) so the structure is easy to compare. For a real deployment, use password_hash/password_verify and stronger validation/security controls.
