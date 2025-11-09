<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Warehouses extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'tools';
    protected $table = 'warehouses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barcode',
        'product_name',
        'unit',
        'package_type',
        'quantity_per_package',
        'total_packages',
        'total_quantity',
        'purchase_price_per_package',
        'purchase_price_per_unit',
        'total_purchase_amount',
        'retail_price',
        'wholesale_price',
        'profit_loss_per_unit',
        'total_profit_loss',
        'country_of_origin',
        'production_year',
        'notes',
        'image_path',
        'is_active',
        'status',
        'min_stock_level',
        'max_stock_level',
        'category',
        'sub_category',
        'supplier_name',
        'supplier_contact',
        'last_purchase_date',
        'expiry_date',
        'user_id',
        'admin_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_price_per_package' => 'decimal:2',
        'purchase_price_per_unit' => 'decimal:2',
        'total_purchase_amount' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'profit_loss_per_unit' => 'decimal:2',
        'total_profit_loss' => 'decimal:2',
        'is_active' => 'boolean',
        'production_year' => 'integer',
        'quantity_per_package' => 'integer',
        'total_packages' => 'integer',
        'total_quantity' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'last_purchase_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * بوت مدل - ایجاد بارکد خودکار هنگام ساخت
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->barcode)) {
                $model->barcode = static::generateAutoBarcode();
            }
            
            // محاسبات خودکار قبل از ذخیره
            $model->calculateTotalQuantity()
                  ->calculateUnitPrice()
                  ->calculateTotalPurchaseAmount()
                  ->calculateProfitLoss()
                  ->updateStatus();
                  
            // تنظیم تاریخ آخرین خرید برای محصولات جدید
            if (!$model->last_purchase_date) {
                $model->last_purchase_date = now();
            }
        });

        static::updating(function ($model) {
            // محاسبات خودکار قبل از آپدیت
            $model->calculateTotalQuantity()
                  ->calculateUnitPrice()
                  ->calculateTotalPurchaseAmount()
                  ->calculateProfitLoss()
                  ->updateStatus();
        });
    }

    /**
     * تولید بارکد خودکار (استاتیک برای استفاده در مدل)
     */
    public static function generateAutoBarcode()
    {
        $prefix = 'WH';
        $timestamp = now()->format('YmdHis');
        $random = Str::random(4);
        
        $autoBarcode = $prefix . $timestamp . strtoupper($random);
        
        $counter = 0;
        while (static::where('barcode', $autoBarcode)->exists() && $counter < 10) {
            $random = Str::random(4);
            $autoBarcode = $prefix . $timestamp . strtoupper($random);
            $counter++;
        }
        
        // اگر هنوز تکراری بود، از timestamp متفاوت استفاده کن
        if (static::where('barcode', $autoBarcode)->exists()) {
            $autoBarcode = $prefix . now()->format('YmdHisu') . strtoupper(Str::random(4));
        }
        
        return $autoBarcode;
    }

    /**
     * تولید بارکد عددی
     */
    public static function generateNumericBarcode()
    {
        $prefix = '8';
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            $randomNumber = mt_rand(1, 99999999999);
            $barcode = $prefix . str_pad($randomNumber, 11, '0', STR_PAD_LEFT);
            $attempt++;
        } while (static::where('barcode', $barcode)->exists() && $attempt < $maxAttempts);
        
        // اگر بعد از ۱۰ بار موفق نشد، از روش timestamp استفاده کن
        if ($attempt >= $maxAttempts) {
            $barcode = '8' . now()->format('YmdHis') . mt_rand(100, 999);
        }
        
        return $barcode;
    }

       public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * تولید بارکد بر اساس نام محصول
     */
    public static function generateNameBasedBarcode($productName)
    {
        if (empty($productName)) {
            return static::generateAutoBarcode();
        }
        
        // حذف کاراکترهای غیر الفبایی و عددی
        $namePart = substr(preg_replace('/[^a-zA-Z0-9]/', '', $productName), 0, 6);
        if (empty($namePart)) {
            $namePart = 'PROD';
        }
        
        $timestamp = now()->format('mdHis');
        $random = mt_rand(100, 999);
        
        $barcode = strtoupper($namePart) . $timestamp . $random;
        
        $counter = 0;
        while (static::where('barcode', $barcode)->exists() && $counter < 5) {
            $random = mt_rand(100, 999);
            $barcode = strtoupper($namePart) . $timestamp . $random;
            $counter++;
        }
        
        return $barcode;
    }

    /**
     * بررسی معتبر بودن بارکد
     */
    public static function isValidBarcode($barcode)
    {
        if (empty($barcode)) {
            return false;
        }
        
        // بارکد باید بین ۸ تا ۲۰ کاراکتر باشد
        if (strlen($barcode) < 8 || strlen($barcode) > 20) {
            return false;
        }
        
        // بارکد باید فقط شامل حروف، اعداد و underline باشد
        return preg_match('/^[a-zA-Z0-9_]+$/', $barcode);
    }

    /**
     * جستجوی محصول بر اساس بارکد
     */
    public static function findByBarcode($barcode)
    {
        return static::where('barcode', $barcode)->first();
    }

    /**
     * بررسی وجود بارکد
     */
    public static function barcodeExists($barcode, $excludeId = null)
    {
        $query = static::where('barcode', $barcode);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * روابط
     */
    
    public function histories()
    {
        return $this->hasMany(WarehousesHistory::class, 'warehouse_id');
    }


 
    /**
     * اسکوپ برای محصولات فعال
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * اسکوپ برای محصولات غیرفعال
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * اسکوپ برای محصولات موجود
     */
    public function scopeInStock($query)
    {
        return $query->where('status', 'موجود')->where('total_quantity', '>', 0);
    }

    /**
     * اسکوپ برای محصولات ناموجود
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'ناموجود')->orWhere('total_quantity', '<=', 0);
    }

    /**
     * اسکوپ برای محصولاتی که موجودی کم دارند
     */
    public function scopeLowStock($query)
    {
        return $query->where('total_quantity', '<=', DB::raw('min_stock_level'))
                    ->where('total_quantity', '>', 0);
    }

    /**
     * اسکوپ برای محصولاتی که موجودی بیش از حد دارند
     */
    public function scopeOverStock($query)
    {
        return $query->where('total_quantity', '>', DB::raw('max_stock_level'));
    }

    /**
     * اسکوپ برای محصولاتی که تاریخ انقضای آنها نزدیک است
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    /**
     * اسکوپ برای محصولات منقضی شده
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<', now());
    }

    /**
     * اسکوپ برای محصولات بدون تاریخ انقضا
     */
    public function scopeNoExpiry($query)
    {
        return $query->whereNull('expiry_date');
    }

    /**
     * اسکوپ برای محصولات با سود بالا
     */
    public function scopeHighProfit($query, $percentage = 30)
    {
        return $query->whereRaw('((wholesale_price - purchase_price_per_unit) / purchase_price_per_unit * 100) >= ?', [$percentage]);
    }

    /**
     * اسکوپ برای محصولات با سود کم
     */
    public function scopeLowProfit($query, $percentage = 10)
    {
        return $query->whereRaw('((wholesale_price - purchase_price_per_unit) / purchase_price_per_unit * 100) <= ?', [$percentage]);
    }

    /**
     * محاسبه خودکار قیمت هر واحد
     */
    public function calculateUnitPrice()
    {
        if ($this->purchase_price_per_package > 0 && $this->quantity_per_package > 0) {
            $this->purchase_price_per_unit = $this->purchase_price_per_package / $this->quantity_per_package;
        } else {
            $this->purchase_price_per_unit = 0;
        }
        return $this;
    }

    /**
     * محاسبه موجودی کل
     */
    public function calculateTotalQuantity()
    {
        if ($this->package_type === 'دانه') {
            $this->total_quantity = $this->total_packages;
        } else {
            $this->total_quantity = $this->total_packages * ($this->quantity_per_package ?? 1);
        }
        return $this;
    }

    /**
     * محاسبه مبلغ کل خرید
     */
    public function calculateTotalPurchaseAmount()
    {
        $this->total_purchase_amount = ($this->total_packages ?? 0) * ($this->purchase_price_per_package ?? 0);
        return $this;
    }

    /**
     * محاسبه سود و ضرر
     */
    public function calculateProfitLoss()
    {
        if ($this->wholesale_price > 0 && $this->purchase_price_per_unit > 0) {
            $this->profit_loss_per_unit = $this->wholesale_price - $this->purchase_price_per_unit;
            $this->total_profit_loss = $this->profit_loss_per_unit * ($this->total_quantity ?? 0);
        } else {
            $this->profit_loss_per_unit = 0;
            $this->total_profit_loss = 0;
        }
        return $this;
    }

    /**
     * محاسبه درصد سود
     */
    public function getProfitPercentageAttribute()
    {
        if ($this->purchase_price_per_unit > 0 && $this->wholesale_price > 0) {
            return round((($this->wholesale_price - $this->purchase_price_per_unit) / $this->purchase_price_per_unit) * 100, 2);
        }
        return 0;
    }

    /**
     * محاسبه درصد سود پرچون
     */
    public function getRetailProfitPercentageAttribute()
    {
        if ($this->purchase_price_per_unit > 0 && $this->retail_price > 0) {
            return round((($this->retail_price - $this->purchase_price_per_unit) / $this->purchase_price_per_unit) * 100, 2);
        }
        return 0;
    }

    /**
     * بروزرسانی وضعیت موجودی
     */
    public function updateStatus()
    {
        if ($this->total_quantity <= 0) {
            $this->status = 'ناموجود';
        } elseif ($this->total_quantity <= $this->min_stock_level) {
            $this->status = 'در حال تکمیل';
        } elseif ($this->max_stock_level > 0 && $this->total_quantity > $this->max_stock_level) {
            $this->status = 'موجود بیش از حد';
        } else {
            $this->status = 'موجود';
        }
        return $this;
    }

    /**
     * افزایش موجودی
     */
    public function increaseStock($quantity, $packageType = null, $price = null, $notes = null)
    {
        $previousQuantity = $this->total_quantity;
        
        if ($packageType && $packageType !== $this->package_type) {
            // تبدیل واحد در صورت نیاز
            // این قسمت می‌تواند بر اساس منطق کسب و کار توسعه یابد
        }
        
        $this->total_packages += $quantity;
        $this->calculateTotalQuantity();
        
        if ($price) {
            $this->purchase_price_per_package = $price;
            $this->calculateUnitPrice();
        }
        
        $this->calculateTotalPurchaseAmount();
        $this->calculateProfitLoss();
        $this->updateStatus();
        $this->last_purchase_date = now();
        
        $this->save();
        
        // ثبت در تاریخچه
        $this->recordHistory('ورود', $quantity, $previousQuantity, $this->total_quantity, $price, $notes);
        
        return $this;
    }

    /**
     * کاهش موجودی
     */
    public function decreaseStock($quantity, $type = 'خروج', $unitPrice = null, $notes = null)
    {
        $previousQuantity = $this->total_quantity;
        
        if ($quantity > $this->total_packages) {
            throw new \Exception('موجودی کافی نیست. موجودی فعلی: ' . $this->total_packages);
        }
        
        $this->total_packages -= $quantity;
        $this->calculateTotalQuantity();
        $this->calculateTotalPurchaseAmount();
        $this->calculateProfitLoss();
        $this->updateStatus();
        
        $this->save();
        
        // ثبت در تاریخچه
        $this->recordHistory($type, -$quantity, $previousQuantity, $this->total_quantity, $unitPrice, $notes);
        
        return $this;
    }

    /**
     * تنظیم موجودی به مقدار مشخص
     */
    public function setStock($newQuantity, $notes = null)
    {
        $previousQuantity = $this->total_quantity;
        $quantityChange = $newQuantity - $previousQuantity;
        
        $this->total_packages = $newQuantity;
        $this->calculateTotalQuantity();
        $this->calculateTotalPurchaseAmount();
        $this->calculateProfitLoss();
        $this->updateStatus();
        
        $this->save();
        
        // ثبت در تاریخچه
        $this->recordHistory('تعدیل', $quantityChange, $previousQuantity, $this->total_quantity, null, $notes);
        
        return $this;
    }

    /**
     * انتقال موجودی به انبار دیگر
     */
    public function transferStock($quantity, $targetWarehouse, $notes = null)
    {
        DB::transaction(function () use ($quantity, $targetWarehouse, $notes) {
            // کاهش از انبار فعلی
            $this->decreaseStock($quantity, 'انتقال به انبار ' . $targetWarehouse->id, null, $notes);
            
            // افزایش در انبار مقصد
            $targetWarehouse->increaseStock($quantity, $this->package_type, $this->purchase_price_per_package, 'انتقال از انبار ' . $this->id);
        });
        
        return $this;
    }

    /**
     * ثبت تاریخچه تغییرات
     */
    private function recordHistory($type, $quantityChange, $previousQuantity, $newQuantity, $unitPrice = null, $notes = null)
    {
        $user = auth()->guard('tools')->user();
        
        $this->histories()->create([
            'type' => $type,
            'quantity_change' => $quantityChange,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $newQuantity,
            'unit_price' => $unitPrice,
            'total_amount' => $unitPrice ? abs($quantityChange) * $unitPrice : null,
            'notes' => $notes,
            'created_by' => $user->name ?? 'system',
            'user_id' => $user->id ?? null,
            'admin_id' => $user->admin_id ?? $user->id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * دریافت موجودی قابل فروش
     */
    public function getAvailableQuantityAttribute()
    {
        return $this->total_quantity;
    }

    /**
     * بررسی آیا موجودی کم است
     */
    public function getIsLowStockAttribute()
    {
        return $this->total_quantity <= $this->min_stock_level;
    }

    /**
     * بررسی آیا موجودی بیش از حد است
     */
    public function getIsOverStockAttribute()
    {
        return $this->max_stock_level > 0 && $this->total_quantity > $this->max_stock_level;
    }

    /**
     * بررسی آیا محصول منقضی شده است
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    /**
     * بررسی آیا محصول به زودی منقضی می‌شود
     */
    public function getIsExpiringSoonAttribute()
    {
        return $this->expiry_date && 
               $this->expiry_date > now() && 
               $this->expiry_date <= now()->addDays(30);
    }

    /**
     * دریافت روزهای باقی مانده تا انقضا
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        
        return now()->diffInDays($this->expiry_date, false);
    }

    /**
     * دریافت ارزش کل موجودی بر اساس قیمت خرید
     */
    public function getInventoryValueAttribute()
    {
        return $this->total_quantity * $this->purchase_price_per_unit;
    }

    /**
     * دریافت ارزش کل موجودی بر اساس قیمت فروش عمده
     */
    public function getPotentialValueAttribute()
    {
        return $this->total_quantity * $this->wholesale_price;
    }

    /**
     * دریافت ارزش کل موجودی بر اساس قیمت فروش پرچون
     */
    public function getRetailPotentialValueAttribute()
    {
        return $this->total_quantity * $this->retail_price;
    }

    /**
     * دریافت سود بالقوه کل (عمده)
     */
    public function getTotalPotentialProfitAttribute()
    {
        return $this->potential_value - $this->inventory_value;
    }

    /**
     * دریافت سود بالقوه کل (پرچون)
     */
    public function getTotalRetailProfitAttribute()
    {
        return $this->retail_potential_value - $this->inventory_value;
    }

    /**
     * دریافت URL تصویر محصول
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default-product.png');
    }

    /**
     * بررسی آیا محصول قابل فروش است
     */
    public function getIsSalableAttribute()
    {
        return $this->is_active && 
               $this->total_quantity > 0 && 
               !$this->is_expired &&
               $this->retail_price > 0;
    }

    /**
     * جستجو بر اساس نام یا بارکد
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('product_name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('sub_category', 'like', "%{$search}%")
                    ->orWhere('supplier_name', 'like', "%{$search}%")
                    ->orWhere('country_of_origin', 'like', "%{$search}%");
    }

    /**
     * فیلتر بر اساس دسته بندی
     */
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * فیلتر بر اساس زیردسته
     */
    public function scopeBySubCategory($query, $subCategory)
    {
        if ($subCategory) {
            return $query->where('sub_category', $subCategory);
        }
        return $query;
    }

    /**
     * فیلتر بر اساس وضعیت
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * فیلتر بر اساس ادمین
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * فیلتر بر اساس تامین کننده
     */
    public function scopeBySupplier($query, $supplierName)
    {
        if ($supplierName) {
            return $query->where('supplier_name', $supplierName);
        }
        return $query;
    }

    /**
     * فیلتر بر اساس کشور سازنده
     */
    public function scopeByCountry($query, $country)
    {
        if ($country) {
            return $query->where('country_of_origin', $country);
        }
        return $query;
    }

    /**
     * فیلتر بر اساس سال تولید
     */
    public function scopeByProductionYear($query, $year)
    {
        if ($year) {
            return $query->where('production_year', $year);
        }
        return $query;
    }

    /**
     * مرتب سازی بر اساس محبوبیت (بر اساس تعداد تراکنش‌ها)
     */
    public function scopeOrderByPopularity($query)
    {
        return $query->withCount('histories')->orderBy('histories_count', 'desc');
    }

    /**
     * مرتب سازی بر اساس سودآوری
     */
    public function scopeOrderByProfitability($query, $direction = 'desc')
    {
        return $query->orderBy('profit_loss_per_unit', $direction);
    }

    /**
     * مرتب سازی بر اساس ارزش موجودی
     */
    public function scopeOrderByInventoryValue($query, $direction = 'desc')
    {
        return $query->orderByRaw('total_quantity * purchase_price_per_unit ' . $direction);
    }

    /**
     * دریافت آمار محصولات
     */
    public static function getInventoryStats($adminId = null)
    {
        $query = static::query();
        
        if ($adminId) {
            $query->where('admin_id', $adminId);
        }
        
        $totalProducts = $query->count();
        $activeProducts = $query->clone()->active()->count();
        $inStock = $query->clone()->inStock()->count();
        $lowStock = $query->clone()->lowStock()->count();
        $outOfStock = $query->clone()->outOfStock()->count();
        $expiringSoon = $query->clone()->expiringSoon()->count();
        $expired = $query->clone()->expired()->count();
        
        $totalValue = $query->clone()->get()->sum('inventory_value');
        $potentialValue = $query->clone()->get()->sum('potential_value');
        
        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'in_stock' => $inStock,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'expiring_soon' => $expiringSoon,
            'expired' => $expired,
            'total_value' => $totalValue,
            'potential_value' => $potentialValue,
            'total_profit_potential' => $potentialValue - $totalValue,
        ];
    }

    /**
     * دریافت محصولات پر-risk (موجودی کم یا در حال انقضا)
     */
    public static function getHighRiskProducts($adminId = null)
    {
        $query = static::query();
        
        if ($adminId) {
            $query->where('admin_id', $adminId);
        }
        
        return $query->where(function ($q) {
                $q->where('total_quantity', '<=', DB::raw('min_stock_level'))
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('expiry_date')
                         ->where('expiry_date', '<=', now()->addDays(30));
                  });
            })
            ->where('total_quantity', '>', 0)
            ->get();
    }

    /**
     * بررسی امکان حذف محصول
     */
    public function canBeDeleted()
    {
        // اگر محصول در تاریخچه تراکنش داشته باشد، نباید حذف شود
        return $this->histories()->count() === 0;
    }

    /**
     * غیرفعال کردن محصول به جای حذف
     */
    public function safeDelete()
    {
        if ($this->canBeDeleted()) {
            return $this->delete();
        } else {
            $this->is_active = false;
            return $this->save();
        }
    }

    /**
     * بازیابی محصول
     */
    public function restoreProduct()
    {
        if ($this->trashed()) {
            $this->restore();
            $this->is_active = true;
            return $this->save();
        }
        return false;
    }

    /**
     * دریافت تاریخچه قیمت‌ها
     */
    public function getPriceHistory()
    {
        return $this->histories()
            ->whereNotNull('unit_price')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * دریافت میانگین قیمت خرید
     */
    public function getAveragePurchasePrice()
    {
        return $this->histories()
            ->where('type', 'ورود')
            ->whereNotNull('unit_price')
            ->avg('unit_price');
    }

    /**
     * فرمت کردن داده‌ها برای نمایش
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // اضافه کردن attributeهای computed
        $array['image_url'] = $this->image_url;
        $array['is_low_stock'] = $this->is_low_stock;
        $array['is_expired'] = $this->is_expired;
        $array['inventory_value'] = $this->inventory_value;
        $array['potential_value'] = $this->potential_value;
        $array['profit_percentage'] = $this->profit_percentage;
        $array['is_salable'] = $this->is_salable;
        
        return $array;
    }
}