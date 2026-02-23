# Analysis & Architecure Laravel SaaS Starter Pack

Berdasarkan prompt yang diberikan, berikut adalah analisa dan arsitektur starter pack Laravel yang telah dirancang untuk aplikasi manajemen langganan hosting:

## 1. Struktur Starterpack Laravel (Folder & File Utama)

Struktur folder dimodifikasi dari default Laravel 11 untuk mendukung model SaaS dan skalabilitas.

```
app/
├── Actions/                 # Berisi use-case tunggal seperti CreateServiceSubscription, AutoSuspendService
├── Console/                 # Kernel.php (tempat scheduler) dan Commands (misal: command cron trigger)
├── Enums/                   # Enum class (ServiceStatus, InvoiceStatus, PaymentStatus)
├── Events/                  # Event trigger (InvoiceCreated, InvoicePaid, ServiceSuspended)
├── Exceptions/              # Custom error handling untuk API dan view
├── Http/
│   ├── Controllers/         # Thin Controller (hanya mengurus validasi form-request & return response)
│   ├── Requests/            # FormRequest validasi
│   └── Middleware/          # Middleware custom selain default 
├── Jobs/                    # Background Jobs (GenerateDueInvoicesJob, SendInvoiceReminderJob, AutoSuspendServiceJob)
├── Listeners/               # Listener untuk Events (SendEmailNotification, NotifyAdmin)
├── Models/                  # Eloquent models (User, Customer, Service, Invoice, Payment, AuditLog)
├── Notifications/           # Kelas notifikasi (InvoiceDueNotification, ServiceSuspendedNotification)
├── Policies/                # Authorization policies (Gate)
├── Providers/               # Route, Repository, dan Service binding
└── Services/                # Service layer yang mengorganisir berbagai actions menjadi unit yang bisa digunakan controller
```

## 2. Penjelasan Arsitektur

- **Action / UseCase Design Pattern**: Arsitektur ini tidak menyimpan business logic di controller sama sekali (Thin Controller). Setiap tugas spesifik akan memiliki class Action atau class Service sendiri, misalnya `GenerateInvoiceForService`. Hal ini sangat testable dan scalable.
- **Breeze & Spatie Permission**: Breeze digunakan sebagai pondasi authentication scaffolding karena sangat clean, tidak overweight (seperti Jetstream), dan mudah dimodifikasi sesuai UI webapp. Untuk otorisasi (RBAC - Super Admin, Admin, Finance, Customer), Spatie Permission diintegrasikan ke Laravel.
- **Database enum**: Digunakan native enum PHP 8.1 dan tipe data ENUM (atau integer mapped ke Enum) pada database sehingga status state-machine menjadi *type-safe* dan mencegah typo logic.
- **Event-Driven & Queues**: Proses billing, pembuatan invoice, dan notifikasi diletakkan ke *Queues* melalui *Events-Listeners / Jobs* agar HTTP request cepat di sisi UI dan tidak terganggu dengan beban external API (email, WhatsApp).

## 3. Alur Billing & Reminder (State Machine)

**Service State Machine**: `Active` → `Due` (tagihan rilis belum dibayar) → `Overdue` (lewat tempo) → `Suspended` (dihentikan otomatis via Cron) → `Active` (bila dibayar).

**Cron & Scheduler Timeline (via `GenerateDueInvoicesJob` & `SendInvoiceReminderJob`)**:
- *H-14 atau H-7 (Konfigurasi di db/env)*: System (cron harian) membuat record `Invoice` berstatus `Unpaid`. Start kirim notification "Invoice Generated". State masuk `Due`.
- *H-3*: System trigger reminder email warning (tagihan sebentar lagi jatuh tempo).
- *H-1 (Satu hari sebelum The Due Date)*: Final reminder harian, bisa memicu integrasi WhatsApp API.
- *The Due Date (H-0)*: Status invoice ditinjau. Jika masih `Unpaid`, status invoice menjadi `Overdue`. 
- *H+1 s/d H+n*: Reminder overdue berjalan (jika belum dibayar).
- *H+7 (Batas Overdue Configurable)*: Jika tetap belum dibayar, Job `AutoSuspendServiceJob` akan trigger, lalu ganti status dari `Overdue` masuk ke `Suspended`, dan API notifikasi trigger untuk suspend server hosting customer (dan/atau mengirim notifikasi service dihentikan).

## 4. Best Practice yang Diterapkan

1. **Transaction-safe Logic**: Semua interaksi Database seperti merubah status Service beserta Invoicenya dibungkus via `DB::transaction()` dalam *Action block*.
2. **Environment Separation & Configuration**: Variabel dinamis tidak di *hardcode*, tapi disimpan di `config/billing.php` yang mereferensi `.env` (misalnya batas jatuh tempo ke suspend dalam hitungan hari).
3. **Audit Log System**: Pembuatan tabel `audit_logs` atau instalasi package *Spatie Activitylog* untuk merekam setiap mutasi uang bayar/generate invoice agar historikal billing jelas.
4. **Security Hardening**: *Spatie Permission* dan *Policies* untuk mencegah eskalasi user, *Throttle / Rate Limiting*, *Laravel Form Request* XSS filtering.

## 5. Checklist "Siap Lanjut ke Fitur Aplikasi"

Starter Pack ini dijamin VALID apabila semua item di bawah terpenuhi sebelum Phase 5 - 10 di prompt lanjutan dieksekusi:

- [ ] Folder skeleton sudah dibuat (Actions, Enums, Services).
- [ ] Laravel berjalan di LTS (v11) dengan error logging & timezone dikonfigurasi.
- [ ] Authentication dengan Laravel Breeze terinstall.
- [ ] Spatie Permission `Role` dan `Permission` seed untuk 4 role inti siap (Super Admin, Admin, Finance, Customer) sudah dijalankan.
- [ ] Admin panel boilerplate dengan dynamic navigation (bergantung via can()/role middleware) berhasil dirender.
- [ ] Database config, Mail trap config, dan Queue driver (database/redis) sudah tersambung di dotenv.
- [ ] *Quality Control Test* minimal Auth bisa login, dan Middleware berperan mencegah user biasa akses panel Admin.
