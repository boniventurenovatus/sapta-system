# SAPTA HRIS — Kumbukumbu Ya Maendeleo

**Tarehe:** 2026-09-22
**Mfumo:** SAPTA Management System
**URL:** https://sapta-system.onrender.com

---

## Hali Ya Mfumo

### Authentication & Authorization
- [x] Login/Logout
- [x] 23 roles
- [x] 36 permissions
- [x] Auto-generate credentials
- [x] Forgot password
- [x] Reset password
- [x] Credentials expiry (7 days)

### Dashboards (11)
- [x] Admin Dashboard
- [x] Executive Dashboard
- [x] Director Dashboard
- [x] HR Dashboard
- [x] Finance Dashboard
- [x] Program Dashboard
- [x] MEAL Dashboard
- [x] ICT Dashboard
- [x] Manager Dashboard
- [x] Staff Dashboard

### Modules (16)
- [x] Employees
- [x] Departments
- [x] Positions
- [x] Attendances
- [x] Leave Requests
- [x] Trainings
- [x] Recruitment
- [x] Budgets
- [x] Receipts
- [x] Payment Vouchers
- [x] Payroll
- [x] Projects
- [x] Tasks
- [x] Documents
- [x] Reports
- [x] Communication

### UI/UX
- [x] Modern design
- [x] Toast notifications
- [x] Role-based sidebar
- [x] Collapse toggle
- [x] Mobile responsive
- [x] Quick actions
- [x] KPIs + Charts
- [x] Cascading dropdowns

### Staff Specific
- [x] Staff anaona My Reports tu
- [x] Staff anaona My Tasks, My Leaves, My Attendance, My Trainings
- [x] Staff hana ruhusa kufikia reports za admin

---

## Credentials

| Role | Username | Password |
|------|----------|----------|
| Super Admin | `superadmin` | `Sapta@2025!` |
| Admin | `admin` | `Sapta@2025!` |
| Director | `director` | `Sapta@2025!` |
| Manager | `manager` | `Sapta@2025!` |
| Staff | `staff` | `Sapta@2025!` |
| HR Manager | `hr.manager` | `Sapta@2025!` |
| HR Officer | `hr.officer` | `Sapta@2025!` |
| Finance Manager | `finance.manager` | `Sapta@2025!` |
| Accountant | `accountant.test` | `Sapta@2025!` |
| Project Manager | `project.manager` | `Sapta@2025!` |
| MEAL Manager | `meal.manager` | `Sapta@2025!` |
| ICT Manager | `ict.manager` | `Sapta@2025!` |
| BOD | `bod` | `Sapta@2025!` |
| CEO | `ceo` | `Sapta@2025!` |

---

## Files Muhimu

### Controllers
- `app/Http/Controllers/EmployeeController.php`
- `app/Http/Controllers/UserController.php`
- `app/Http/Controllers/TrainingController.php`
- `app/Http/Controllers/DocumentController.php`
- `app/Http/Controllers/ReportController.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

### Models
- `app/Models/User.php`
- `app/Models/Employee.php`
- `app/Models/Training.php`
- `app/Models/TrainingEnrollment.php`
- `app/Models/Document.php`
- `app/Models/Role.php`

### Services
- `app/Services/NotificationService.php`
- `app/Services/DashboardService.php`

### Views
- `resources/views/layouts/sapta.blade.php`
- `resources/views/layouts/partials/sapta-sidebar.blade.php`
- `resources/views/dashboard/*.blade.php`
- `resources/views/employees/*.blade.php`
- `resources/views/trainings/*.blade.php`
- `resources/views/documents/*.blade.php`
- `resources/views/reports/*.blade.php`

### Routes
- `routes/web.php`

### Config
- `config/sapta.php`
- `.env`

### Seeders
- `database/seeders/RoleUsersSeeder.php`
- `database/seeders/FullAccessSeeder.php`
- `database/seeders/DatabaseImportSeeder.php`
- `database/seeders/OrganizationalUnitsAndPositionsSeeder.php`

### Migrations
- `database/migrations/*_create_trainings_table.php`
- `database/migrations/*_create_training_enrollments_table.php`
- `database/migrations/*_create_documents_table.php`

---

## Environment Variables (Render)

```
APP_NAME="SAPTA System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sapta-system.onrender.com

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=boniventurenovatus@gmail.com
MAIL_PASSWORD=vviqdyyiqjgmwqeu
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sapta.co.tz
MAIL_FROM_NAME="SAPTA System"

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
CREDENTIALS_EXPIRY_DAYS=7
```

---

## Features Zinazofanya Kazi

### Auto-Generate Credentials
- Format: `[jina]@sapta2024[X]` / `[Lastname]@Sapta.org`
- Internal Message: Inatumwa
- Email: Inatumwa
- Expiry: Siku 7

### Dashboard Kwa Kila Role
- Admin: Users, Roles, Permissions, Departments
- HR: Employees, Leaves, Trainings
- Finance: Budgets, Vouchers, Claims
- Staff: My Tasks, My Leaves, My Attendance, My Trainings

### Role-Based Sidebar
- Super Admin: Zote
- Executive: Executive, Reports
- HR: HR, Communication, Reports
- Finance: Finance, Communication, Reports
- Staff: My Work, Communication, Reports (zake)

### Reports
- Admin: All Reports
- Staff: My Reports (zake tu)

---

## Issues Zinazohitaji Kurekebishwa

### Pending
- [ ] Test module zote kwa kila role
- [ ] Export CSV/Excel/PDF
- [ ] Print views
- [ ] Real-time notifications
- [ ] Email/SMS notifications
- [ ] Two-factor auth
- [ ] Audit logs
- [ ] Backup database

### Known Issues — Fixed
- [x] Reports filter — inafanya kazi
- [x] Documents filter — inafanya kazi
- [x] Sidebar collapse — inafanya kazi
- [x] Staff reports — inafanya kazi

---

## Git Commits (Recent)

```
0a30a41 Fix ReportController - correct View import
e8c68ac Fix sidebar collapse - ID + overlay + CSS
078d0b5 Remove placeholder routes - restore real routes
d617552 Role-based sidebar with menus for each role
65a1146 Add RoleUsersSeeder - create users for all roles on Render
```

---

## Kumbukumbu Ya Kazi

### Tarehe: 2026-09-22

**Kazi Zilizofanyika:**
1. ✅ Fix auto-generate credentials
2. ✅ Fix internal message + email
3. ✅ Fix admin anaona credentials
4. ✅ Fix modern design + toast
5. ✅ Fix reset password
6. ✅ Fix English only
7. ✅ Fix dashboards za kila role
8. ✅ Fix role-based sidebar
9. ✅ Fix sidebar collapse
10. ✅ Fix mobile responsive
11. ✅ Fix trainings module
12. ✅ Fix documents module
13. ✅ Fix documents filter + search
14. ✅ Fix staff anaona reports zake
15. ✅ Fix ReportController View import

**Inayofuata:**
- Test modules zote
- Deploy Render
- Ongeza features mpya

---

## Jinsi Ya Kuendelea

**Kila nikianza new chat:**

1. Soma file hii — `SAPTA_PROGRESS.md`
2. Angalia hali ya mwisho — tulipoishia
3. Endelea kutoka hapo — bila kurudia

**Kila tunachokifanya kipya:**

1. Ongeza kwenye file hii — `SAPTA_PROGRESS.md`
2. Commit kwenye Git — `git commit -m "..."`
3. Push kwenye Render — `git push origin main`

---

**Mwisho wa Kumbukumbu**