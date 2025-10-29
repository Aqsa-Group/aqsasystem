<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            
            // اطلاعات اصلی محصول
            $table->string('barcode')->unique()->comment('بارکد محصول');
            $table->string('product_name')->comment('نام محصول');
            $table->string('unit')->comment('واحد اندازه گیری (عدد، کیلوگرم، لیتر، ...)');
            $table->enum('package_type', ['کارتن', 'بسته', 'دانه'])->default('کارتن')->comment('نوع بسته بندی');
            $table->integer('quantity_per_package')->default(1)->comment('تعداد در هر بسته/کارتن');
            $table->integer('total_packages')->default(0)->comment('تعداد کل بسته/کارتن ها');
            $table->integer('total_quantity')->default(0)->comment('موجودی کل به واحد اصلی');
            
            // قیمت‌ها و مالی
            $table->decimal('purchase_price_per_package', 15, 2)->default(0)->comment('قیمت خرید هر بسته/کارتن');
            $table->decimal('purchase_price_per_unit', 15, 2)->default(0)->comment('قیمت خرید هر واحد');
            $table->decimal('total_purchase_amount', 15, 2)->default(0)->comment('مبلغ کل خرید');
            $table->decimal('retail_price', 15, 2)->default(0)->comment('قیمت فروش پرچون');
            $table->decimal('wholesale_price', 15, 2)->default(0)->comment('قیمت فروش عمده');
            $table->decimal('profit_loss_per_unit', 15, 2)->default(0)->comment('سود/ضرر هر واحد');
            $table->decimal('total_profit_loss', 15, 2)->default(0)->comment('سود/ضرر کل');
            
            // اطلاعات اضافی
            $table->string('country_of_origin')->default('افغانستان')->comment('کشور سازنده');
            $table->integer('production_year')->comment('سال تولید');
            $table->text('notes')->nullable()->comment('توضیحات و یادداشت ها');
            $table->string('image_path')->nullable()->comment('مسیر عکس محصول');
            
            // وضعیت و مدیریت
            $table->boolean('is_active')->default(true)->comment('فعال/غیرفعال');
            $table->enum('status', ['موجود', 'ناموجود', 'در حال تکمیل'])->default('موجود')->comment('وضعیت موجودی');
            
            // حداقل و حداکثر موجودی برای اعلان‌ها
            $table->integer('min_stock_level')->default(0)->comment('حداقل موجودی برای اعلان');
            $table->integer('max_stock_level')->nullable()->comment('حداکثر موجودی پیشنهادی');
            
            // دسته بندی
            $table->string('category')->nullable()->comment('دسته بندی محصول');
            $table->string('sub_category')->nullable()->comment('زیر دسته بندی');
            
            // اطلاعات تأمین کننده
            $table->string('supplier_name')->nullable()->comment('نام تأمین کننده');
            $table->string('supplier_contact')->nullable()->comment('اطلاعات تماس تأمین کننده');
            
            // تاریخ‌های مهم
            $table->date('last_purchase_date')->nullable()->comment('تاریخ آخرین خرید');
            $table->date('expiry_date')->nullable()->comment('تاریخ انقضا');

            // اضافه کردن فیلدهای مدیریت کاربر
            $table->foreignId('user_id')->nullable()->comment('کاربر ایجاد کننده');
            $table->foreignId('admin_id')->nullable()->comment('ادمین مربوطه');
            
            // ایندکس‌ها برای جستجوی بهتر
            $table->index('barcode');
            $table->index('product_name');
            $table->index('category');
            $table->index('status');
            $table->index('is_active');
            $table->index('user_id');
            $table->index('admin_id');
            
            $table->timestamps();
            $table->softDeletes()->comment('حذف نرم برای بازیابی اطلاعات');

            // تعریف foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');
        });
        
        // ایجاد جدول برای تاریخچه تغییرات موجودی دوکان
        Schema::create('warehouse_histories', function (Blueprint $table) {
            $table->id();
            
            // **اصلاح مهم: تغییر از inventory_id به warehouse_id**
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade')->comment('مرجع به محصول در دوکان');
            
            // **اصلاح: تغییر از enum به string با طول مناسب**
            $table->string('type', 50)->comment('نوع تراکنش: ورود, خروج, تعدیل, فروش, خرید, انتقال');
            
            $table->integer('quantity_change')->comment('تعداد تغییر یافته');
            $table->integer('previous_quantity')->comment('موجودی قبلی');
            $table->integer('new_quantity')->comment('موجودی جدید');
            $table->decimal('unit_price', 15, 2)->nullable()->comment('قیمت واحد در زمان تراکنش');
            $table->decimal('total_amount', 15, 2)->nullable()->comment('مبلغ کل تراکنش');
            $table->string('reference_number')->nullable()->comment('شماره مرجع (فاکتور، سند)');
            $table->text('notes')->nullable()->comment('توضیحات تراکنش');
            $table->string('created_by')->nullable()->comment('ایجاد کننده تراکنش');
            
            // اضافه کردن فیلدهای مدیریت کاربر برای تاریخچه
            $table->foreignId('user_id')->nullable()->comment('کاربر ایجاد کننده');
            $table->foreignId('admin_id')->nullable()->comment('ادمین مربوطه');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('warehouse_id'); // **تغییر به warehouse_id**
            $table->index('type'); // **اصلاح: حذف طول از ایندکس**
            $table->index('reference_number');
            $table->index('created_at');
            $table->index('user_id');
            $table->index('admin_id');

            // تعریف foreign keys برای تاریخچه
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_histories');
        Schema::dropIfExists('warehouses');
    }
};