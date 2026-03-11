<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add widget_token to customers
        Schema::table('customers', function (Blueprint $table) {
            $table->string('widget_token')->nullable()->unique()->after('status');
        });

        // Generate tokens for existing customers
        foreach (\Illuminate\Support\Facades\DB::table('customers')->get() as $customer) {
            \Illuminate\Support\Facades\DB::table('customers')->where('id', $customer->id)->update([
                'widget_token' => \Illuminate\Support\Str::random(32)
            ]);
        }

        // 2. Add customer_id and make service_id nullable first
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // 3. Update existing invoices to standard structure
        $invoices = \Illuminate\Support\Facades\DB::table('invoices')->get();
        foreach ($invoices as $invoice) {
            $service = \Illuminate\Support\Facades\DB::table('services')->where('id', $invoice->service_id)->first();
            if ($service) {
                \Illuminate\Support\Facades\DB::table('invoices')->where('id', $invoice->id)->update([
                    'customer_id' => $service->customer_id
                ]);
            }
        }

        // Drop the old foreign key constraint
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
        });

        // 4. Update invoice_items to optionally link to service
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('invoice_id')->constrained()->nullOnDelete();
        });

        // Migrate service_id from invoices down to their invoice_items
        foreach ($invoices as $invoice) {
            \Illuminate\Support\Facades\DB::table('invoice_items')
                ->where('invoice_id', $invoice->id)
                ->update(['service_id' => $invoice->service_id]);
        }

        // 5. Drop service_id from invoices entirely
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('service_id');
        });

        // 6. Drop widget_token from services
        if (Schema::hasColumn('services', 'widget_token')) {
            if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
                \Illuminate\Support\Facades\DB::statement('DROP INDEX IF EXISTS services_widget_token_unique');
            } else {
                Schema::table('services', function (Blueprint $table) {
                    $table->dropUnique(['widget_token']);
                });
            }
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('widget_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert widget_token on services
        Schema::table('services', function (Blueprint $table) {
            $table->string('widget_token')->nullable()->unique()->after('status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // Recover service_id from items to invoices (just taking the first item's service_id)
        $invoices = \Illuminate\Support\Facades\DB::table('invoices')->get();
        foreach ($invoices as $invoice) {
            $item = \Illuminate\Support\Facades\DB::table('invoice_items')->where('invoice_id', $invoice->id)->first();
            if ($item && $item->service_id) {
                \Illuminate\Support\Facades\DB::table('invoices')->where('id', $invoice->id)->update([
                    'service_id' => $item->service_id
                ]);
            }
        }

        // Drop customer_id from invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        // Drop service_id from invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });

        // Drop widget_token from customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('widget_token');
        });
    }
};
