# 3.3 FILE SPECIFICATIONS

This section defines the physical artifact specification for the current implementation of the `bid_for_used_product` system, including runtime responsibilities, internal executable files, and security controls.

## 3.3.1 Application File & Directory Layout

| Artifact | Type | Technical Responsibility | Detailed Notes | Key Internal Files | Security/Access Notes |
|---|---|---|---|---|---|
| `.gitignore` | Root config file | VCS exclusion policy | Not present in current root snapshot; repository currently tracks project artifacts directly. Recommended for local logs, uploads, and environment-specific files. | *(missing in root)* | Add ignore rules for `logs/`, dynamic `uploads/`, and local secrets to reduce accidental disclosure. |
| `.htaccess` | Root web server config | URL rewriting / root-level hardening | Not present in project root. Access-control `.htaccess` exists in `uploads/` and `downloads/`. | `uploads/.htaccess`, `downloads/.htaccess` | `uploads/.htaccess` blocks PHP execution and disables indexing; `downloads/.htaccess` enables indexed access and should be reviewed for production hardening. |
| `index.php` | Entry script (public page) | Public landing + featured auction rendering | Loads header stack, queries latest open products, renders CTA and category visuals. | `index.php`, `includes/header.php`, `includes/footer.php` | Publicly accessible; output sanitized through `htmlspecialchars()` for dynamic display fields. |
| `LICENSE` | Legal text file | License compliance and distribution rights | MIT license for source usage, redistribution, and warranty disclaimer. | `LICENSE` | Must remain unchanged in redistributed builds. |
| `README.md` | Project documentation | System overview and technical orientation | Provides architecture summary, stack details, module overview, and links to comprehensive report. | `README.md`, `COMPLETE_PROJECT_REPORT.md` | No direct security risk; keep credentials and secrets out of docs. |
| `api/` | Root API directory | Conventional API namespace | Not implemented at root level in current build; API endpoints are placed under role domain (`admin/api/`). | *(no files under `api/`)* | Prefer role-scoped authentication if root APIs are introduced later. |
| `assets/` | Static resource directory | UI style, vendor resources, static images/icons | Houses CSS and large visual asset libraries used by all role UIs. Includes modern UI theme and legacy style file. | `assets/css/modern.css`, `assets/css/style.css`, image libraries under `assets/images/` | Static read-only delivery; no executable PHP in asset tree. Validate content-type and cache policy at server level. |
| `components/` | Shared UI component directory | Reusable partial component store | Directory not present in current implementation. Component composition is currently handled through `includes/header.php` and `includes/footer.php`. | *(no files under `components/`)* | If introduced, enforce include-only usage and avoid direct route access. |
| `config/` | Core configuration directory | Global constants and bootstrap settings | Defines DB credentials, upload paths, size/type limits, timezone, and bootstraps error handling. | `config/config.php` | Contains environment-sensitive values; should be protected from direct web download and moved to env-secure config in production. |
| `cron/` | Scheduler task directory | Background jobs / automation tasks | Not present in current build. Time-bound behavior (auction status checks) is currently evaluated at request-time in page/controller logic. | *(no files under `cron/`)* | If introduced, execute via CLI with least-privilege DB credentials and output logging. |
| `database/` | SQL schema and seed directory | Physical data model and initial dataset provisioning | Contains canonical schema (`database.sql`) and large seeded data package (`comprehensive_seed.sql`). Used by setup scripts. | `database/database.sql`, `database/comprehensive_seed.sql` | Restrict direct HTTP access to SQL files in production to prevent schema leakage. |
| `documentation/` | Structured project documentation directory | Formal docs namespace | Not present as `documentation/`; implemented as `docs/` with setup, DFD, troubleshooting, credentials, and screen documentation. | `docs/SETUP.md`, `docs/DFD_ANALYSIS.md`, `docs/TROUBLESHOOTING.md`, `docs/TEST_CREDENTIALS.md`, `docs/SCREENS_DESCRIPTION.md` | Documentation includes operational details; avoid exposing internal credentials in deploy docs. |
| `includes/` | Shared backend library directory | Middleware, helpers, DB abstraction, session/RBAC, notifications | Core runtime layer for all routes: DB PDO helper, session policy, input validation, file upload helper, notification API, and utility formatters. | `includes/session.php` (RBAC middleware), `includes/database.php`, `includes/validation.php`, `includes/file_upload_helper.php`, `includes/notifications.php`, `includes/functions.php` | High sensitivity: must not be directly route-executed. Protect include path and avoid exposing stack traces in production mode. |
| `pages/` | Public/neutral page routes | Authentication, registration, static policies, contact forms | Hosts UI forms that post to `auth/` handlers and stores contact messages. | `pages/login.php` -> `auth/login_process.php`; `pages/register_client.php` -> `auth/register_client_process.php`; `pages/register_company.php` -> `auth/register_company_process.php`; `pages/contact.php` | Public endpoints; requires strong validation and anti-automation controls (rate limit/CAPTCHA recommended). |
| `uploads/` | Runtime storage directory | User-uploaded product and verification files | Stores auction images and identity proofs generated from forms and setup scripts. | `uploads/products/*`, `uploads/.htaccess` | Current `.htaccess` blocks PHP execution and directory listing. Keep writable but non-executable. |

Implementation-specific file examples supporting runtime flow:
- **Forms**: `pages/register_company.php` (multipart upload), `company/post_product.php`, `admin/settings.php`, `client/place_bid.php`.
- **Middleware**: `includes/session.php` (`require_login()`, role gating, session timeout), `includes/validation.php`.
- **Endpoints/Handlers**: `auth/*.php`, `client/toggle_reminder.php` (JSON API), `admin/api/system_actions.php`.
- **Scripts/maintenance**: `setup_project.php` (schema + seed + media linking), `debug_login.php` (credential diagnostics), `maintenance.php` (maintenance gate page).

### 3.3.1.2 Complete Database Table List

##### Table: `users`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `user_id` | INT | PK, AUTO_INCREMENT | Unique user identity across all roles. |
| `role` | ENUM('admin','company','client') | NOT NULL, DEFAULT 'client' | RBAC role classification. |
| `name` | VARCHAR(100) | NOT NULL | Display and legal name of account holder. |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Login identity and communication key. |
| `password` | VARCHAR(255) | NOT NULL | Password hash storage. |
| `contact` | VARCHAR(20) | NULL | Phone/mobile contact. |
| `address` | TEXT | NULL | User/company address text. |
| `avatar` | VARCHAR(255) | DEFAULT NULL | Selected avatar filename. |
| `status` | ENUM('active','inactive','banned') | DEFAULT 'active' | Account lifecycle state. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Row creation timestamp. |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Update audit timestamp. |

##### Table: `companies`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `company_id` | INT | PK, AUTO_INCREMENT | Seller organization identifier. |
| `user_id` | INT | FK -> `users.user_id`, NOT NULL | Owner user account link. |
| `company_name` | VARCHAR(150) | NULL | Business identity name. |
| `owner_name` | VARCHAR(100) | NULL | Registered owner/representative. |
| `gst_number` | VARCHAR(50) | NULL | GST tax reference for verification. |
| `verified_status` | ENUM('pending','verified','rejected') | DEFAULT 'pending' | Seller verification workflow state. |

##### Table: `products`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `product_id` | INT | PK, AUTO_INCREMENT | Auction product identity. |
| `company_id` | INT | FK -> `companies.company_id`, NOT NULL | Listing owner company reference. |
| `product_name` | VARCHAR(200) | NOT NULL | Product title shown in listings. |
| `category` | VARCHAR(50) | NULL | Domain category (`2-wheeler`, `4-wheeler`, `machinery`). |
| `model` | VARCHAR(100) | NULL | Model metadata. |
| `year` | INT | NULL | Manufacturing year. |
| `chassis_no` | VARCHAR(100) | NULL | Physical traceability identifier. |
| `owner_details` | TEXT | NULL | Ownership and historical condition details. |
| `running_duration` | VARCHAR(50) | NULL | Mileage/hour usage. |
| `base_price` | DECIMAL(15,2) | NULL | Minimum bidding baseline. |
| `bid_start` | DATETIME | NULL | Auction start date-time. |
| `bid_end` | DATETIME | NULL | Auction close date-time. |
| `product_image` | VARCHAR(255) | NULL | Primary image filename. |
| `status` | ENUM('open','closed','sold') | DEFAULT 'open' | Auction lifecycle status. |
| `description` | TEXT | NULL | Item description for bidders. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Listing creation timestamp. |

##### Table: `bids`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `bid_id` | INT | PK, AUTO_INCREMENT | Unique bid record identifier. |
| `product_id` | INT | FK -> `products.product_id`, NOT NULL | Target auction product. |
| `client_id` | INT | FK -> `users.user_id`, NOT NULL | Bidder user identity. |
| `bid_amount` | DECIMAL(15,2) | NOT NULL | Offered monetary value. |
| `bid_status` | ENUM('pending','approved','rejected') | DEFAULT 'pending' | Bid decision state. |
| `bid_time` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Bid placement timestamp. |

##### Table: `notifications`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `notification_id` | INT | PK, AUTO_INCREMENT | Notification identity. |
| `user_id` | INT | FK -> `users.user_id`, NOT NULL | Target user receiving notification. |
| `title` | VARCHAR(255) | NOT NULL | Notification subject line. |
| `message` | TEXT | NULL | Notification content body. |
| `type` | VARCHAR(50) | DEFAULT 'info' | Display classification (`info`, `success`, etc.). |
| `target_url` | VARCHAR(255) | NULL | Redirect target when clicked. |
| `is_read` | TINYINT(1) | DEFAULT 0 | Read/unread state flag. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Notification creation time. |

##### Table: `product_reminders`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `reminder_id` | INT | PK, AUTO_INCREMENT | Reminder identity. |
| `user_id` | INT | FK -> `users.user_id`, NOT NULL | Subscriber client for reminder. |
| `product_id` | INT | FK -> `products.product_id`, NOT NULL | Product to remind for. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Reminder creation timestamp. |

##### Table: `subscriptions`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `subscription_id` | INT | PK, AUTO_INCREMENT | Subscription record identity. |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Newsletter/subscription email target. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Subscription creation timestamp. |

##### Table: `messages`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `message_id` | INT | PK, AUTO_INCREMENT | Message identity. |
| `sender_id` | INT | NOT NULL | Source user id (application-level link). |
| `receiver_id` | INT | NOT NULL | Destination user id (application-level link). |
| `product_id` | INT | NULL | Optional product-context reference. |
| `message` | TEXT | NULL | Message body text. |
| `is_read` | TINYINT(1) | DEFAULT 0 | Read flag for receiver. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Message creation timestamp. |

##### Table: `product_gallery`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `gallery_id` | INT | PK, AUTO_INCREMENT | Gallery row identity. |
| `product_id` | INT | FK -> `products.product_id`, NOT NULL | Parent listing relation. |
| `image_path` | VARCHAR(255) | NOT NULL | Stored gallery image filename. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Gallery insertion timestamp. |

##### Table: `site_settings`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `setting_id` | INT | PK, AUTO_INCREMENT | Setting identity key. |
| `setting_key` | VARCHAR(100) | UNIQUE, NOT NULL | Logical setting key (e.g., `maintenance_mode`). |
| `setting_value` | TEXT | NULL | Value payload for key. |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp. |

##### Table: `contact_messages`

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| `message_id` | INT | PK, AUTO_INCREMENT | Contact message identity. |
| `name` | VARCHAR(100) | NULL | Sender display name. |
| `email` | VARCHAR(100) | NULL | Sender email for follow-up. |
| `subject` | VARCHAR(200) | NULL | Contact topic. |
| `message` | TEXT | NULL | Message content body. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Submission timestamp. |

Schema alignment note: handler files reference additional runtime columns (`companies.identity_proof`, `bids.comments`, `subscriptions.client_id/status`, `contact_messages.user_id`) that are not declared in the current `database/database.sql` snapshot and should be addressed by migration scripts before production rollout.

## 3.3.2 MODULE SPECIFICATIONS (Role-Wise)

### Core Module 1: Authentication and Session Control

- **Purpose**: Centralized login, registration, role routing, and session governance.
- **Actors**: Guest, Admin, Company (seller), Client (bidder).
- **Pages**: `pages/login.php`, `pages/register_client.php`, `pages/register_company.php`.
- **Forms/Inputs**: `email`, `password`; registration fields (`name`, `contact`, `address`, `gst_number`, `identity_proof[]`, `confirm_password`).
- **APIs**: `auth/login_process.php`, `auth/register_client_process.php`, `auth/register_company_process.php`, `auth/logout.php`.
- **Includes/Handlers**: `includes/session.php`, `includes/validation.php`, `includes/database.php`, `includes/file_upload_helper.php`.
- **Processing Flow**: Validate input -> query `users`/`companies` -> hash/verify password -> set session -> redirect to role dashboard.
- **Outputs**: Session variables (`user_id`, `role`, `company_id`), redirect responses, validation error states.
- **Key Tables**: `users`, `companies`.
- **Permissions**: Public access for registration/login; post-auth role restrictions via `require_login(role)`.

### Core Module 2: Product Lifecycle and Auction Listing

- **Purpose**: Seller-side listing creation, image handling, listing edits, and listing presentation.
- **Actors**: Company, Client (viewer), Guest (viewer).
- **Pages**: `company/post_product.php`, `company/my_products.php`, `company/edit_product.php`, `products.php`, `product_details.php`.
- **Forms/Inputs**: `product_name`, `category`, `model`, `year`, `chassis_no`, `owner_details`, `running_duration`, `base_price`, `description`, `bid_start`, `bid_end`, `product_image[]`.
- **APIs**: Form posts to `company/post_product_process.php`; product visibility consumed via query pages.
- **Includes/Handlers**: `includes/validation.php`, `includes/database.php`, `includes/functions.php`.
- **Processing Flow**: Validate listing data -> upload images -> insert `products` -> insert `product_gallery` -> expose listing in browse/detail pages.
- **Outputs**: Product records, gallery records, listing cards/detail pages, seller product dashboard rows.
- **Key Tables**: `products`, `product_gallery`, `companies`, `users`.
- **Permissions**: Creation/edit restricted to `company`; read access open/public.

### Core Module 3: Bid Processing and Auction Decision Engine

- **Purpose**: Bid placement, bid updates, bid review/approval/rejection, and bidder notifications.
- **Actors**: Client (bidder), Company (approver), Admin (auditor via reports).
- **Pages**: `client/place_bid.php`, `client/my_bids.php`, `company/view_bids.php`, `admin/view_all_bids.php`.
- **Forms/Inputs**: Bid amount (`amount` or `bid_amount`), optional comments in process handler, action flags (`approve`/`reject`).
- **APIs**: `client/place_bid_process.php`; direct action links in `company/view_bids.php`.
- **Includes/Handlers**: `includes/functions.php` (`is_bid_active()`), `includes/notifications.php`, `includes/database.php`.
- **Processing Flow**: Validate auction time/status -> validate minimum amount -> insert/update `bids` -> company decision update -> notification generation.
- **Outputs**: Bid history, status badges, approval/rejection states, notification feed entries.
- **Key Tables**: `bids`, `products`, `users`, `notifications`.
- **Permissions**: `client` can place/modify own pending bids; `company` can process bids for owned products only.

### Core Module 4: Administration, Configuration, and Maintenance Operations

- **Purpose**: User governance, verification workflow, settings management, reporting, and system maintenance operations.
- **Actors**: Admin, Principal/Super Admin equivalent.
- **Pages**: `admin/dashboard.php`, `admin/manage_users.php`, `admin/verify_companies.php`, `admin/settings.php`, `admin/reports.php`, `admin/system_manager.php`.
- **Forms/Inputs**: User status actions, setting inputs (`site_name`, `maintenance_mode`, `admin_email`), system-manager authentication and action payloads.
- **APIs**: `admin/api/system_actions.php` (`get_users`, `get_upload_stats`, `db_wipe`, `upload_wipe`, `self_destruct`, `reset_password`, `generate_seed`).
- **Includes/Handlers**: `includes/session.php`, `includes/database.php`, `includes/seed_generator.php`, `config/config.php`.
- **Processing Flow**: Role/auth checks -> execute admin command -> DB/file operation -> JSON or page response.
- **Outputs**: Updated user states, company verification status, settings records, reports, maintenance operation logs.
- **Key Tables**: `users`, `companies`, `site_settings`, `bids`, `products`, `notifications`.
- **Permissions**: Strict `admin` role; `system_manager` adds second-stage password gate.

---

### Role-Wise Module: Admin

| # | Sub-Module | Description |
|---|---|---|
| 1 | User Management | Filter, activate/deactivate, and create user records. |
| 2 | Company Verification | Verify pending company records for seller eligibility. |
| 3 | Reporting & Monitoring | Aggregate bids, products, and financial trend indicators. |
| 4 | Settings Management | Update site parameters and maintenance mode. |
| 5 | System Manager Controls | Execute controlled destructive/admin automation actions via API. |

- `manage_users.php` + `toggle_user_status.php` map to `users.status` updates (RBAC: `admin` only).
- `verify_companies.php` + `verify_company.php` map to `companies.verified_status`.
- `reports.php` reads cross-table joins (`bids`, `products`, `users`) for analytics output.
- `settings.php` writes to `site_settings` (keys: `site_name`, `maintenance_mode`, `admin_email`, `welcome_message`).
- `system_manager.php` uses `admin/api/system_actions.php` for controlled operations on DB and `uploads/`.

### Role-Wise Module: Student/User (Mapped to Client Role)

| # | Sub-Module | Description |
|---|---|---|
| 1 | Product Discovery | Browse/search/filter available auctions. |
| 2 | Bidding | Submit and track bid amounts on active auctions. |
| 3 | Bid History | Monitor bid outcomes and auction status progression. |
| 4 | Alerts & Subscriptions | Set reminders and manage subscription state. |

- `client/browse_products.php` form inputs `search`, `category` map to filtered reads from `products` and `companies`.
- `client/place_bid.php` and `client/place_bid_process.php` persist bids in `bids` with `pending` workflow.
- `client/my_bids.php` joins `bids` and `products` for historical status traceability.
- `client/toggle_reminder.php` JSON endpoint writes/removes `product_reminders` records.
- `client/subscribe.php` and `client/unsubscribe.php` map to subscription lifecycle (`subscriptions` table implementation expected to include user mapping fields).

### Role-Wise Module: Staff (Mapped to Company/Seller Role)

| # | Sub-Module | Description |
|---|---|---|
| 1 | Product Posting | Create auction listings with media and schedule. |
| 2 | Listing Control | View own products and update listing details. |
| 3 | Bid Evaluation | Review incoming bids and approve/reject bidders. |
| 4 | Seller Dashboard | Monitor inventory and bid counts. |

- `company/post_product.php` form payload maps to `products` + `product_gallery` inserts via `company/post_product_process.php`.
- `company/my_products.php` lists company-owned products and linked bid counts.
- `company/view_bids.php` executes bid status transitions (`pending` -> `approved/rejected`) in `bids`.
- Approval/rejection triggers notification generation through `includes/notifications.php` into `notifications`.
- Ownership checks enforce isolation (`company_id` validation before viewing/editing bid contexts).

### Role-Wise Module: HOD/Manager (Mapped to Operational Admin)

| # | Sub-Module | Description |
|---|---|---|
| 1 | Operational Oversight | Track platform counts and auction activity health. |
| 2 | Compliance Review | Ensure company verification and role/state compliance. |
| 3 | Incident Controls | Use maintenance mode and controlled interventions. |

- `admin/dashboard.php` aggregates operational KPIs from `users`, `companies`, `products`, and `bids`.
- `admin/verify_companies.php` supports managerial verification queue processing.
- `admin/settings.php` toggles site-level operation state (`maintenance_mode` key).
- `maintenance.php` enforces runtime maintenance redirect logic for non-admin users.

### Role-Wise Module: Principal/Super Admin (Mapped to Privileged Admin + System Manager)

| # | Sub-Module | Description |
|---|---|---|
| 1 | Privileged Authentication | Secondary password gate for high-risk controls. |
| 2 | System Reset/Wipe | Database and storage wipe modes with API orchestration. |
| 3 | Credential Recovery | Bulk password reset for selected users. |
| 4 | Seed/Data Engineering | Generate and package seed datasets with media references. |

- `admin/system_manager.php` requires `admin` session + `system_manager_auth` master password flow.
- `admin/api/system_actions.php` routes privileged actions (`db_wipe`, `upload_wipe`, `self_destruct`, `reset_password`, `generate_seed`).
- Reset and wipe operations directly affect `users`, dependent tables, and `uploads/` filesystem.
- Seed generation binds to `includes/seed_generator.php` and outputs SQL/data package artifacts.

---

**Technical Mapping Note (Forms -> APIs/Handlers -> RBAC -> Tables):**
- `pages/login.php` -> `auth/login_process.php` -> roles (`admin/company/client`) -> `users`, `companies`.
- `pages/register_company.php` -> `auth/register_company_process.php` -> guest/public -> `users`, `companies` (+ identity proof files in `uploads/identity_proofs`).
- `company/post_product.php` -> `company/post_product_process.php` -> `company` only -> `products`, `product_gallery`.
- `client/place_bid.php` / product detail bid entry -> `client/place_bid_process.php` -> `client` only -> `bids`, `products`.
- Reminder toggle UI (`product_details.php`, `client/browse_products.php`) -> `client/toggle_reminder.php` JSON -> `client` only -> `product_reminders`.
- Admin settings form (`admin/settings.php`) -> self-post handler -> `admin` only -> `site_settings`.
- System manager JS actions (`admin/system_manager.php`) -> `admin/api/system_actions.php` -> `admin + system_manager_auth` -> multi-table + filesystem operations.

This mapping should be used as the primary test-trace matrix for RBAC validation, endpoint authorization, and database impact verification during integration and UAT cycles.
