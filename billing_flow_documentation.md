# Dokumentasi Billing Management System

## 1. Flow Bisnis Utama (Use-case Flow)

1. **Pembuatan Service (Subscription):**
   - Admin/Customer membuat service hosting baru.
   - Sistem `CreateServiceSubscription` dipanggil: menetapkan `start_date`, menentukan `billing_cycle` (Monthly/Yearly/Custom), lalu menghitung `next_due_date`.
   - Status service menjadi `Active`.

2. **Generate Invoice Otomatis:**
   - Scheduler memanggil `GenerateDueInvoicesJob` setiap pukul 00:00 (Harian).
   - Job mendeteksi jika ada layanan yang `next_due_date`-nya sama dengan (H-14 dari hari ini) bersumber dari `config('billing.invoice.generate_days_before')`.
   - `GenerateInvoiceForService` dijalankan: Status Invoice diset menjadi `Unpaid`, lalu Status Service berubah menjadi `Due`. Event `InvoiceCreated` memicu notifikasi Email/WA.

3. **Siklus Reminder (Notifikasi):**
   - Scheduler memanggil `SendInvoiceReminderJob` setiap pukul 08:00 (Harian).
   - Tagihan yang belum dibayar dengan tenggat H-7, H-3, dan H-1 akan mendapat `InvoiceReminderNeeded`.
   - Tagihan telat (H+1 sampai H+n) mendapat `InvoiceOverdueReminderNeeded`, status Service ditandai sebagai `Overdue`.

4. **Pembayaran dan Verifikasi:**
   - Customer mengupload bukti bayar `MarkInvoiceAsPaid`, status payment `Pending`. Admin kemudian memverifikasi dan Payment status menjadi `Verified`.
   - Secara otomatis Trigger Event `InvoicePaid`. Jika layanan tadinya `Due/Overdue/Suspended`, maka di `ActivateService` dan `next_due_date` diperpanjang kembali, status service menjadi `Active`.

5. **Auto Suspend:**
   - Scheduler `AutoSuspendServiceJob` berjalan tiap 01:00 pagi.
   - Mencari Tagihan Unpaid yang melampaui `suspend_after_days` (default: 7 Hari telat).
   - Memanggil `SuspendService`: merubah Service Status jadi `Suspended`.

## 2. Konfigurasi Cron & Scheduler (Laravel 11)

Pengaturan cron dilakukan melalui perintah OS yang menjalankan scheduler standar Laravel. 

**Crontab (Server Linux):**
```bash
* * * * * cd /path-ke-project && php artisan schedule:run >> /dev/null 2>&1
```

**Jadwal Internal (di `routes/console.php`):**
- `GenerateDueInvoicesJob` berjalan `dailyAt('00:00')`
- `SendInvoiceReminderJob` berjalan `dailyAt('08:00')`
- `AutoSuspendServiceJob` berjalan `dailyAt('01:00')`

## 3. Konfigurasi Reminder

Aturan hari reminder dikendalikan lewat `config/billing.php`. Kamu bisa publish atau cukup ubah `.env`:

```php
// config/billing.php
return [
    'invoice' => [
        'generate_days_before' => env('BILLING_INVOICE_GENERATE_DAYS', 14),
    ],
    'reminders' => [
        'before_due' => [7, 3, 1],
        'after_due' => [1, 7],
    ],
    'suspension' => [
        'suspend_after_days' => env('BILLING_SUSPEND_AFTER_DAYS', 7),
    ],
];
```

## 4. Checklist Siap Production

- [x] **Struktur Bersih**: Thin Controller, Logic di Action/Service layer.
- [x] **Testable**: Billing flow dan Auto Suspend tercover di Feature Test.
- [x] **Database Constraints**: Seluruh foreign key ditambahkan dengan parameter cascade / onDelete handler yang tepat.
- [x] **Transaction-safe Logic**: Pembungkus `DB::transaction()` digunakan di semua Action mutasi data.
- [x] **Event-Driven**: Segala trigger notifikasi, dikirim melalui Event->Listener.
- [x] **Enum Constants**: Status diconstrain via PHP Enum yang menjamin data integerity.

🚀 **Sistem Billing SaaS ini Siap Di-deploy!**
