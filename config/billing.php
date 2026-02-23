<?php

return [
    'invoice' => [
        // Days before next_due_date to generate the invoice
        'generate_days_before' => env('BILLING_INVOICE_GENERATE_DAYS', 14),
    ],

    'reminders' => [
        // Reminders before due date
        'before_due' => [
            7, // H-7
            3, // H-3
            1, // H-1
        ],

        // Reminders after due date (overdue)
        'after_due' => [
            1, // H+1
            7, // H+7
        ],
    ],

    'suspension' => [
        // Days after due_date to auto-suspend service
        'suspend_after_days' => env('BILLING_SUSPEND_AFTER_DAYS', 7),
    ],
];
