# 🏗️ Architecture & System Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        WEB BROWSER                              │
│                  (User Interface / Frontend)                    │
└──────────────────────────┬──────────────────────────────────────┘
                           │ HTTP/AJAX
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                      LARAVEL 12 SERVER                          │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                    Routes/Web.php                         │  │
│  │  GET  /                    → Home                         │  │
│  │  GET  /dashboard           → Dashboard                    │  │
│  │  GET  /inventory           → InventoryDashboard          │  │
│  │  GET  /inventory/product   → ProductDetail               │  │
│  │  POST /livewire/update     → Livewire Updates            │  │
│  └───────────────────────────────────────────────────────────┘  │
│                           │                                      │
│  ┌────────────────────────▼───────────────────────────────────┐ │
│  │              Livewire Components (3.6.4)                  │ │
│  │  ┌──────────────────────────────────────────────────────┐ │ │
│  │  │ InventoryDashboard                                   │ │ │
│  │  │ - Display products table                             │ │ │
│  │  │ - Search, filter, sort                               │ │ │
│  │  │ - Stats computation                                  │ │ │
│  │  └──────────────────────────────────────────────────────┘ │ │
│  │  ┌──────────────────────────────────────────────────────┐ │ │
│  │  │ ProductForm                                          │ │ │
│  │  │ - Add/edit product                                   │ │ │
│  │  │ - Form validation                                    │ │ │
│  │  └──────────────────────────────────────────────────────┘ │ │
│  │  ┌──────────────────────────────────────────────────────┐ │ │
│  │  │ StockMovementForm                                    │ │ │
│  │  │ - Log stock movements                                │ │ │
│  │  │ - Update quantities                                  │ │ │
│  │  └──────────────────────────────────────────────────────┘ │ │
│  │  ┌──────────────────────────────────────────────────────┐ │ │
│  │  │ ProductDetail                                        │ │ │
│  │  │ - Show product info                                  │ │ │
│  │  │ - Display movement history                           │ │ │
│  │  └──────────────────────────────────────────────────────┘ │ │
│  └────────────────────────┬───────────────────────────────────┘ │
│                           │                                      │
│  ┌────────────────────────▼───────────────────────────────────┐ │
│  │              Eloquent Models (ORM)                         │ │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   │ │
│  │  │  Category    │  │  Product     │  │ StockMovement│   │ │
│  │  ├──────────────┤  ├──────────────┤  ├──────────────┤   │ │
│  │  │ id           │  │ id           │  │ id           │   │ │
│  │  │ name         │  │ category_id  │  │ product_id   │   │ │
│  │  │ description  │  │ name         │  │ user_id      │   │ │
│  │  └──────────────┘  │ sku          │  │ type         │   │ │
│  │                    │ price        │  │ quantity     │   │ │
│  │                    │ quantity     │  │ reference    │   │ │
│  │                    │ min_quantity │  │ notes        │   │ │
│  │                    │ max_quantity │  └──────────────┘   │ │
│  │                    │ unit         │                      │ │
│  │                    │ supplier     │  ┌──────────────┐   │ │
│  │                    │ is_active    │  │ StockAlert   │   │ │
│  │                    └──────────────┘  ├──────────────┤   │ │
│  │                                       │ id           │   │ │
│  │                                       │ product_id   │   │ │
│  │                                       │ alert_type   │   │ │
│  │                                       │ is_resolved  │   │ │
│  │                                       └──────────────┘   │ │
│  └────────────────────────┬───────────────────────────────────┘ │
│                           │                                      │
└───────────────────────────┼──────────────────────────────────────┘
                            │ SQL Queries
                            ▼
        ┌──────────────────────────────────────────┐
        │      SQLite Database (database.sqlite)   │
        │                                          │
        │  ├─ categories                          │
        │  ├─ products                            │
        │  ├─ stock_movements                     │
        │  ├─ stock_alerts                        │
        │  ├─ users                               │
        │  ├─ sessions                            │
        │  └─ migrations                          │
        │                                          │
        └──────────────────────────────────────────┘
```

---

## Data Flow

### Add Product Flow
```
User (Browser)
    │
    ├─ Fill form + Submit
    │
    ▼
ProductForm Livewire Component
    │
    ├─ Validate inputs
    ├─ Create/Update Product in database
    ├─ Dispatch 'product-saved' event
    │
    ▼
InventoryDashboard Component
    │
    ├─ Listen for 'product-saved'
    ├─ Refresh product list
    │
    ▼
User sees updated table
```

### Stock Movement Flow
```
User (Browser)
    │
    ├─ Select product detail
    │
    ▼
ProductDetail Component
    │
    ├─ Display product info
    ├─ Show StockMovementForm
    │
    ▼
User fills form + Submit
    │
    ▼
StockMovementForm Component
    │
    ├─ Calculate new quantity based on type:
    │  ├─ IN/RETURN: quantity += input
    │  └─ OUT/ADJUSTMENT: quantity -= input
    │
    ├─ Update Product.quantity
    ├─ Create StockMovement record
    ├─ Dispatch 'stock-updated' event
    │
    ▼
ProductDetail Component
    │
    ├─ Refresh stock movements list
    ├─ Update product quantity display
    │
    ▼
User sees updated data
```

---

## Component Relationships

```
Dashboard (Blade Template)
    │
    └─ InventoryDashboard (Livewire)
        │
        ├─ Search & Filter
        ├─ Sort Table
        ├─ Dispatch 'open-product-form'
        │
        └─ ProductForm (Livewire Modal)
            │
            ├─ Validate inputs
            ├─ Save to Product model
            ├─ Emit 'product-saved'


ProductDetail (Blade Template)
    │
    └─ ProductDetail (Livewire)
        │
        ├─ Display product info
        ├─ Compute stock movements
        │
        └─ StockMovementForm (Livewire)
            │
            ├─ Form for adding movement
            ├─ Update quantity
            ├─ Create StockMovement record
```

---

## Database Relationships

```
Categories (1) ─────→ (Many) Products
                     │
                     ├─ (Many) StockMovements
                     │          │
                     │          └─ (1) Users
                     │
                     └─ (Many) StockAlerts

Users (1) ──────────→ (Many) StockMovements
```

### Entity Relationship Diagram
```
┌────────────────┐
│  categories    │
├────────────────┤
│ id (PK)        │
│ name           │
│ description    │
└────────────────┘
        │
        │ 1:N
        │
        ▼
┌────────────────┐
│  products      │
├────────────────┤
│ id (PK)        │
│ category_id(FK)│
│ name           │
│ sku            │
│ price          │
│ quantity       │
│ min_quantity   │
│ max_quantity   │
│ unit           │
│ supplier       │
│ is_active      │
└────────────────┘
      ▲    │
      │    │ 1:N
      │    ▼
      │  ┌──────────────────┐
      │  │ stock_movements  │
      │  ├──────────────────┤
      │  │ id (PK)          │
      │  │ product_id (FK)  │
      │  │ user_id (FK)     │
      │  │ type             │
      │  │ quantity         │
      │  │ reference        │
      │  │ notes            │
      │  └──────────────────┘
      │    ▲
      │    │ N:1
      │    │
      │  ┌──────────────────┐
      └──│ stock_alerts     │
         ├──────────────────┤
         │ id (PK)          │
         │ product_id (FK)  │
         │ alert_type       │
         │ is_resolved      │
         │ resolved_at      │
         └──────────────────┘
            │
            │ N:1
            ▼
       ┌────────────┐
       │   users    │
       ├────────────┤
       │ id (PK)    │
       │ name       │
       │ email      │
       │ password   │
       └────────────┘
```

---

## File Structure

```
app/
├── Livewire/
│   ├── InventoryDashboard.php           (303 lines)
│   ├── ProductForm.php                  (102 lines)
│   ├── ProductDetail.php                (40 lines)
│   └── StockMovementForm.php            (74 lines)
├── Models/
│   ├── Category.php                     (16 lines)
│   ├── Product.php                      (75 lines)
│   ├── StockAlert.php                   (45 lines)
│   ├── StockMovement.php                (58 lines)
│   └── User.php                         (existing)
└── Http/
    └── Controllers/
        └── InventoryController.php      (35 lines)

resources/views/
├── livewire/
│   ├── inventory-dashboard.blade.php    (260 lines)
│   ├── product-form.blade.php           (185 lines)
│   ├── product-detail.blade.php         (165 lines)
│   └── stock-movement-form.blade.php    (110 lines)
├── inventory-dashboard.blade.php        (existing)
└── product-detail.blade.php             (existing)

database/
├── migrations/
│   └── 2025_11_13_000001_...php         (100 lines)
└── seeders/
    └── InventorySeeder.php              (120 lines)

routes/
└── web.php                              (updated with inventory routes)

docs/
├── SUMMARY.md                           (Comprehensive overview)
├── INVENTORY_README.md                  (Features & setup)
├── PANDUAN_PENGGUNAAN.md                (User manual)
├── QUICK_START.md                       (Quick setup)
└── ARCHITECTURE.md                      (This file)
```

---

## Technology Stack

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend (Browser)                       │
├─────────────────────────────────────────────────────────────┤
│  • HTML5 + Blade Templates                                  │
│  • Tailwind CSS 4.1.11 (Styling)                           │
│  • Alpine.js (via Livewire)                                │
│  • Livewire 3.6.4 (Reactive Components)                    │
│  • Vite 7 (Build tool)                                     │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                   Backend (Server)                         │
├─────────────────────────────────────────────────────────────┤
│  • PHP 8.2.12 (Server language)                            │
│  • Laravel 12.38.1 (Framework)                             │
│  • Livewire 3.6.4 (Real-time components)                   │
│  • Eloquent ORM (Database access)                          │
│  • Laravel Fortify (Authentication)                        │
│  • Composer (Dependency management)                        │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                      Database                              │
├─────────────────────────────────────────────────────────────┤
│  • SQLite (File-based database)                            │
│  • 7 Tables (categories, products, stock_movements, etc)   │
│  • Migrations (Schema versioning)                          │
│  • Seeders (Sample data)                                   │
└─────────────────────────────────────────────────────────────┘
```

---

## Performance Considerations

### Optimizations Implemented
- ✅ Pagination (15 items per page)
- ✅ Computed properties (lazy evaluation)
- ✅ Query optimization (Eloquent with relationships)
- ✅ Caching ready (can add caching later)
- ✅ Lazy loading for relationships
- ✅ Real-time validation (no extra DB calls)

### Scalability
- ✅ Database indexes on frequently queried columns (category_id, sku)
- ✅ Soft deletes for data integrity
- ✅ Proper foreign keys for referential integrity
- ✅ Can handle thousands of products with current setup

---

## Security Architecture

```
Request
    │
    ├─ Web.php (Route definition)
    │
    ├─ Middleware:
    │   ├─ 'auth' - Check if user authenticated
    │   ├─ 'verified' - Check email verification
    │   └─ CSRF protection
    │
    ├─ Controller/Livewire Component
    │   ├─ Input validation
    │   ├─ Authorization checks
    │   └─ Database operations
    │
    ├─ Model (Eloquent)
    │   ├─ Prepared statements (prevent SQL injection)
    │   └─ Mass assignment protection ($fillable)
    │
    └─ Database
        └─ No direct SQL execution
```

---

## Deployment Checklist

- [ ] Copy .env.example to .env
- [ ] Set APP_KEY via `php artisan key:generate`
- [ ] Configure database connection
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeders (optional): `php artisan db:seed`
- [ ] Build assets: `npm run build`
- [ ] Set proper permissions on storage/ folder
- [ ] Enable HTTPS/SSL
- [ ] Set up proper logging
- [ ] Configure email for notifications
- [ ] Set up backups
- [ ] Monitor application performance

---

**Architecture designed for scalability, security, and maintainability.**

*Last Updated: November 13, 2025*
