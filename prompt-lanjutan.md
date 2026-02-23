PROMPT LANJUTAN — IMPLEMENTASI TERKENDALI LARAVEL (NO NGACO)

Kamu melanjutkan pekerjaan SETELAH starterpack Laravel dinyatakan VALID & RAPi.
Jika starterpack belum memenuhi standar:

Struktur bersih

RBAC jalan

Separation of concern jelas

HENTIKAN IMPLEMENTASI DAN LAPORKAN KEKURANGANNYA.

ATURAN MUTLAK

❌ DILARANG:

CRUD langsung di controller

Query kompleks di controller

Hardcode status & tanggal

Skip migration / seeder

Langsung buat UI kompleks

✅ WAJIB:

Action / Service class per use-case

Transaction-safe logic

Enum / constant untuk status

Event-driven untuk billing & reminder

PHASE 5 — DATABASE & DOMAIN IMPLEMENTATION

Implementasikan domain inti berikut:

1. Customer

Relasi ke User

Status customer

2. Service (Langganan)

Customer → many services

billing_cycle (monthly / yearly / custom)

start_date, next_due_date

status (enum)

3. Invoice

Auto-generated

Period based

Due date configurable

Status lifecycle

4. Payment

Manual & gateway-ready

Proof upload support

Payment verification flow

Gunakan:

Migration

Model

Enum

Factory

Seeder minimal

PHASE 6 — BUSINESS LOGIC LAYER

Buat Action / UseCase class:

CreateServiceSubscription

GenerateInvoiceForService

MarkInvoiceAsPaid

ActivateService

SuspendService

Semua action:

Transactional

Testable

Tanpa ketergantungan UI

PHASE 7 — AUTOMATION & SCHEDULER

Implementasikan:

Laravel Scheduler (Kernel)

Job:

GenerateDueInvoicesJob

SendInvoiceReminderJob

AutoSuspendServiceJob

Reminder timeline:

H-7

H-3

H-1

H+1

H+7

Reminder HARUS CONFIGURABLE, bukan hardcode.

PHASE 8 — EVENT, NOTIFICATION, AUDIT

Gunakan:

Event:

InvoiceCreated

InvoicePaid

ServiceSuspended

Listener:

SendEmailNotification

SendWhatsAppNotification (stub)

Notification:

Email channel mandatory

Tambahkan:

Audit log untuk billing & payment

PHASE 9 — API & ADMIN INTERFACE (MINIMAL)

REST API terpisah dari Web

Resource controller tipis

Validation via FormRequest

UI hanya:

List service

List invoice

Invoice detail

NO beautification. Functional only.

PHASE 10 — QUALITY CONTROL

Wajib:

Feature test untuk billing flow

Test auto suspend

Test payment verification

Dokumentasikan:

Flow bisnis

Konfigurasi cron

Konfigurasi reminder

OUTPUT AKHIR

Folder & file implementasi

Use-case flow (teks)

Contoh scheduler config

Checklist “siap production”

Jika menemukan desain buruk:

Jelaskan

Refactor

Jangan kompromi

Kerjakan seperti:

Ini core system penagihan — salah sedikit, uang bocor.