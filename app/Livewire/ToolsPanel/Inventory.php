<?php

namespace App\Livewire\ToolsPanel;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Models\Tools\Inventories;
use App\Models\Tools\InventoryHistory;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class Inventory extends Component
{
    use WithFileUploads, WithPagination;

    // Properties for form
    public $barcode;
    public $product_name;
    public $unit;
    public $package_type = 'کارتن';
    public $quantity_per_package = 1;
    public $total_packages = 0;
    public $total_quantity = 0;
    public $purchase_price_per_package = 0;
    public $purchase_price_per_unit = 0;
    public $total_purchase_amount = 0;
    public $retail_price = 0;
    public $wholesale_price = 0;
    public $profit_loss_per_unit = 0;
    public $total_profit_loss = 0;
    public $country_of_origin = 'افغانستان';
    public $production_year;
    public $notes;
    public $product_image;

    // Additional fields from database
    public $category;
    public $sub_category;
    public $supplier_name;
    public $supplier_contact;
    public $min_stock_level = 0;
    public $max_stock_level;
    public $expiry_date;
    public $status = 'موجود';
    public $is_active = true;

    // Properties for editing and management
    public $editingId = null;
    public $search = '';
    public $selectedCategory = '';
    public $selectedStatus = '';

    // Stock management
    public $stock_quantity = 0;
    public $stock_type = 'ورود';
    public $stock_notes = '';
    public $selectedProductId = null;

    protected $paginationTheme = 'bootstrap';

     public function mount()
    {
        $this->production_year = Jalalian::now()->getYear();
        $this->expiry_date = null;
        $this->calculatePrices();
    }

        public function calculatePrices()
    {
        // Calculate unit price
        if ($this->purchase_price_per_package > 0 && $this->quantity_per_package > 0) {
            $this->purchase_price_per_unit = $this->purchase_price_per_package / $this->quantity_per_package;
        } else {
            $this->purchase_price_per_unit = 0;
        }

        // Calculate total quantity
        if ($this->total_packages > 0) {
            $this->total_quantity = $this->package_type === 'دانه' 
                ? $this->total_packages
                : $this->total_packages * $this->quantity_per_package;

            $this->total_purchase_amount = $this->total_packages * $this->purchase_price_per_package;
        } else {
            $this->total_quantity = 0;
            $this->total_purchase_amount = 0;
        }

        // Calculate profit/loss
        if ($this->wholesale_price > 0 && $this->purchase_price_per_unit > 0) {
            $this->profit_loss_per_unit = $this->wholesale_price - $this->purchase_price_per_unit;
            $this->total_profit_loss = $this->profit_loss_per_unit * $this->total_quantity;
        } else {
            $this->profit_loss_per_unit = 0;
            $this->total_profit_loss = 0;
        }

        // Update status based on stock level
        $this->updateStatus();
    }

     public function updateStatus()
    {
        if ($this->total_quantity <= 0) {
            $this->status = 'ناموجود';
        } elseif ($this->total_quantity <= $this->min_stock_level) {
            $this->status = 'در حال تکمیل';
        } else {
            $this->status = 'موجود';
        }
    }

      public function updated($property)
    {
        $calculateProperties = [
            'purchase_price_per_package',
            'quantity_per_package',
            'total_packages',
            'package_type',
            'wholesale_price',
            'min_stock_level',
            'retail_price' // اضافه کردن این
        ];

        if (in_array($property, $calculateProperties)) {
            $this->calculatePrices();
        }

        // Reset pagination when search or filters change
        if (in_array($property, ['search', 'selectedCategory', 'selectedStatus'])) {
            $this->resetPage();
        }
    }


    public function saveProduct()
    {
        $this->validate([
            // حذف required از بarcode و اضافه کردن nullable
            'barcode' => 'nullable|string|unique:tools.inventories,barcode,' . $this->editingId,
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string',
            'package_type' => 'required|in:کارتن,بسته,دانه',
            'quantity_per_package' => 'required|integer|min:1',
            'total_packages' => 'required|integer|min:0',
            'purchase_price_per_package' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'country_of_origin' => 'required|string',
            'production_year' => 'required|integer|min:1380|max:' . (Jalalian::now()->getYear() + 1),
            'min_stock_level' => 'required|integer|min:0',
            'category' => 'nullable|string',
        ]);

        // اگر بارکد خالی بود، بارکد خودکار ایجاد کن
        if (empty($this->barcode)) {
            $this->barcode = $this->generateAutoBarcode();
        }

        // بررسی یکتایی بارکد (اگر کاربر دستی وارد کرده)
        $existingProduct = Inventories::where('barcode', $this->barcode)
            ->when($this->editingId, function ($query) {
                $query->where('id', '!=', $this->editingId);
            })
            ->first();

        if ($existingProduct) {
            $this->addError('barcode', 'این بارکد قبلاً ثبت شده است.');
            return;
        }

        // Perform calculations before saving
        $this->calculatePrices();

        $user = Auth::guard('tools')->user();
        $imagePath = $this->product_image ? $this->product_image->store('products', 'public') : null;

        $productData = [
            'barcode' => $this->barcode,
            'product_name' => $this->product_name,
            'unit' => $this->unit,
            'package_type' => $this->package_type,
            'quantity_per_package' => $this->quantity_per_package,
            'total_packages' => $this->total_packages,
            'total_quantity' => $this->total_quantity,
            'purchase_price_per_package' => $this->purchase_price_per_package,
            'purchase_price_per_unit' => $this->purchase_price_per_unit,
            'total_purchase_amount' => $this->total_purchase_amount,
            'retail_price' => $this->retail_price,
            'wholesale_price' => $this->wholesale_price,
            'profit_loss_per_unit' => $this->profit_loss_per_unit,
            'total_profit_loss' => $this->total_profit_loss,
            'country_of_origin' => $this->country_of_origin,
            'production_year' => $this->production_year,
            'notes' => $this->notes,
            'image_path' => $imagePath,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'supplier_name' => $this->supplier_name,
            'supplier_contact' => $this->supplier_contact,
            'min_stock_level' => $this->min_stock_level,
            'max_stock_level' => $this->max_stock_level,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'last_purchase_date' => $this->editingId ? null : now(),
        ];

        // اضافه کردن user_id و admin_id اگر فیلدها وجود دارند
        if ($user && Schema::hasColumn('inventories', 'user_id')) {
            $productData['user_id'] = $user->id;
        }
        
        if ($user && Schema::hasColumn('inventories', 'admin_id')) {
            $productData['admin_id'] = $user->admin_id ?? $user->id;
        }

        DB::beginTransaction();
        try {
            if ($this->editingId) {
                $product = Inventories::findOrFail($this->editingId);
                $product->update($productData);
                $message = 'محصول با موفقیت بروزرسانی شد!';
            } else {
                Inventories::create($productData);
                $message = 'محصول جدید با موفقیت ثبت شد!';
            }

            DB::commit();
            $this->resetForm();
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در ذخیره محصول: ' . $e->getMessage());
        }
    }

    /**
     * تولید بارکد خودکار
     */
    private function generateAutoBarcode()
    {
        $prefix = 'AUTO'; // پیشوند برای بارکدهای خودکار
        $timestamp = now()->format('YmdHis');
        $random = Str::random(4); // ۴ کاراکتر تصادفی
        
        $autoBarcode = $prefix . $timestamp . strtoupper($random);
        
        // بررسی یکتایی
        $counter = 0;
        while (Inventories::where('barcode', $autoBarcode)->exists() && $counter < 10) {
            $random = Str::random(4);
            $autoBarcode = $prefix . $timestamp . strtoupper($random);
            $counter++;
        }
        
        // اگر هنوز تکراری بود، از timestamp متفاوت استفاده کن
        if (Inventories::where('barcode', $autoBarcode)->exists()) {
            $autoBarcode = $prefix . now()->format('YmdHisu') . strtoupper(Str::random(4));
        }
        
        return $autoBarcode;
    }

    /**
     * روش جایگزین: تولید بارکد عددی
     */
    private function generateNumericBarcode()
    {
        $prefix = '8';
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            $randomNumber = mt_rand(1, 99999999999);
            $barcode = $prefix . str_pad($randomNumber, 11, '0', STR_PAD_LEFT);
            $attempt++;
        } while (Inventories::where('barcode', $barcode)->exists() && $attempt < $maxAttempts);
        
        // اگر بعد از ۱۰ بار موفق نشد، از روش timestamp استفاده کن
        if ($attempt >= $maxAttempts) {
            $barcode = '8' . now()->format('YmdHis') . mt_rand(100, 999);
        }
        
        return $barcode;
    }

    /**
     * روش جایگزین: تولید بارکد بر اساس نام محصول
     */
    private function generateNameBasedBarcode()
    {
        if (empty($this->product_name)) {
            return $this->generateAutoBarcode();
        }
        
        $namePart = substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->product_name), 0, 6);
        $timestamp = now()->format('mdHis');
        $random = mt_rand(100, 999);
        
        $barcode = strtoupper($namePart) . $timestamp . $random;
        
        $counter = 0;
        while (Inventories::where('barcode', $barcode)->exists() && $counter < 5) {
            $random = mt_rand(100, 999);
            $barcode = strtoupper($namePart) . $timestamp . $random;
            $counter++;
        }
        
        return $barcode;
    }

    public function editProduct($productId)
    {
        $product = Inventories::findOrFail($productId);
        
        $this->editingId = $product->id;
        $this->barcode = $product->barcode;
        $this->product_name = $product->product_name;
        $this->unit = $product->unit;
        $this->package_type = $product->package_type;
        $this->quantity_per_package = $product->quantity_per_package;
        $this->total_packages = $product->total_packages;
        $this->purchase_price_per_package = $product->purchase_price_per_package;
        $this->retail_price = $product->retail_price;
        $this->wholesale_price = $product->wholesale_price;
        $this->country_of_origin = $product->country_of_origin;
        $this->production_year = $product->production_year;
        $this->notes = $product->notes;
        $this->category = $product->category;
        $this->sub_category = $product->sub_category;
        $this->supplier_name = $product->supplier_name;
        $this->supplier_contact = $product->supplier_contact;
        $this->min_stock_level = $product->min_stock_level;
        $this->max_stock_level = $product->max_stock_level;
        $this->expiry_date = $product->expiry_date;
        $this->status = $product->status;
        $this->is_active = $product->is_active;

        // Recalculate derived values
        $this->calculatePrices();
    }


    public $confirmDeleteId = null;

public function confirmDelete($productId)
{
    $this->confirmDeleteId = $productId;
}

public function deleteProductConfirmed()
{
    if ($this->confirmDeleteId) {
        DB::beginTransaction();
        try {
            $product = Inventories::findOrFail($this->confirmDeleteId);
            
            // Delete associated image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $product->delete();
            
            DB::commit();
            session()->flash('message', 'محصول با موفقیت حذف شد!');
            
            // رفرش کامپوننت
            $this->dispatch('$refresh');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در حذف محصول: ' . $e->getMessage());
        }
        
        $this->confirmDeleteId = null;
    }
}

public function cancelDelete()
{
    $this->confirmDeleteId = null;
}

    public function applyStockChange($productId)
    {
        $this->selectedProductId = $productId;
        
        $this->validate([
            'stock_quantity' => 'required|integer|min:1',
            'stock_type' => 'required|in:ورود,خروج,تعدیل,فروش,خرید',
            'stock_notes' => 'nullable|string|max:500',
        ]);

        $product = Inventories::findOrFail($this->selectedProductId);

        DB::beginTransaction();
        try {
            if ($this->stock_type === 'ورود' || $this->stock_type === 'خرید') {
                // افزایش موجودی
                $previousQuantity = $product->total_quantity;
                $product->total_packages += $this->stock_quantity;
                $product->calculateTotalQuantity();
                $product->calculateTotalPurchaseAmount();
                $product->calculateProfitLoss();
                $product->updateStatus();
                $product->last_purchase_date = now();
                $product->save();

                // ثبت در تاریخچه
                $this->recordHistory($product, 'ورود', $this->stock_quantity, $previousQuantity, $product->total_quantity, null, $this->stock_notes);
            } else {
                // کاهش موجودی
                $previousQuantity = $product->total_quantity;
                
                if ($this->stock_quantity > $product->total_packages) {
                    throw new \Exception('موجودی کافی نیست');
                }
                
                $product->total_packages -= $this->stock_quantity;
                $product->calculateTotalQuantity();
                $product->calculateTotalPurchaseAmount();
                $product->calculateProfitLoss();
                $product->updateStatus();
                $product->save();

                // ثبت در تاریخچه
                $this->recordHistory($product, $this->stock_type, -$this->stock_quantity, $previousQuantity, $product->total_quantity, null, $this->stock_notes);
            }

            DB::commit();
            $this->reset(['stock_quantity', 'stock_type', 'stock_notes', 'selectedProductId']);
            session()->flash('message', 'موجودی با موفقیت بروزرسانی شد!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    private function recordHistory($product, $type, $quantityChange, $previousQuantity, $newQuantity, $unitPrice = null, $notes = null)
    {
        $user = Auth::guard('tools')->user();
        
        $historyData = [
            'inventory_id' => $product->id,
            'type' => $type,
            'quantity_change' => $quantityChange,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $newQuantity,
            'unit_price' => $unitPrice,
            'total_amount' => $unitPrice ? abs($quantityChange) * $unitPrice : null,
            'notes' => $notes,
            'created_by' => $user->name ?? 'system',
        ];

        // اضافه کردن user_id و admin_id اگر فیلدها وجود دارند
        if ($user && Schema::hasColumn('inventory_histories', 'user_id')) {
            $historyData['user_id'] = $user->id;
        }
        
        if ($user && Schema::hasColumn('inventory_histories', 'admin_id')) {
            $historyData['admin_id'] = $user->admin_id ?? $user->id;
        }

        InventoryHistory::create($historyData);
    }

    public function deleteProduct($productId)
    {
        DB::beginTransaction();
        try {
            $product = Inventories::findOrFail($productId);
            
            // Delete associated image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $product->delete();
            
            DB::commit();
            session()->flash('message', 'محصول با موفقیت حذف شد!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در حذف محصول: ' . $e->getMessage());
        }
    }

    public function toggleActive($productId)
    {
        try {
            $product = Inventories::findOrFail($productId);
            $product->update(['is_active' => !$product->is_active]);
            
            session()->flash('message', 'وضعیت محصول تغییر کرد!');
        } catch (\Exception $e) {
            session()->flash('error', 'خطا در تغییر وضعیت: ' . $e->getMessage());
        }
    }

   public function resetForm()
    {
        $this->reset([
            'barcode',
            'product_name',
            'unit',
            'package_type',
            'quantity_per_package',
            'total_packages',
            'purchase_price_per_package',
            'retail_price',
            'wholesale_price',
            'country_of_origin',
            'production_year',
            'notes',
            'product_image',
            'category',
            'sub_category',
            'supplier_name',
            'supplier_contact',
            'min_stock_level',
            'max_stock_level',
            'expiry_date',
            'editingId'
        ]);
        
        $this->package_type = 'کارتن';
        $this->quantity_per_package = 1;
        $this->production_year = Jalalian::now()->getYear();
        $this->status = 'موجود';
        $this->is_active = true;
        $this->expiry_date = null;
        $this->calculatePrices();
    }


   public function clearFilters()
{
    $this->reset(['search', 'selectedCategory', 'selectedStatus']);
    $this->resetPage();
    
    // اطمینان از محاسبه مجدد قیمت‌ها
    $this->calculatePrices();
    
    // اگر می‌خواهید فرم هم ریست شود:
    // $this->resetForm();
}


    public function clearStockForm()
    {
        $this->reset(['selectedProductId', 'stock_quantity', 'stock_type', 'stock_notes']);
    }

    // Get unique categories for filter dropdown
    public function getCategoriesProperty()
    {
        $user = Auth::guard('tools')->user();
        
        $query = Inventories::query();
        
        // اگر فیلد admin_id وجود دارد و کاربر لاگین کرده، فیلتر کنیم
        if ($user && Schema::hasColumn('inventories', 'admin_id')) {
            $adminId = $user->admin_id ?? $user->id;
            $query->where('admin_id', $adminId);
        }

        return $query->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    // Get low stock products for alerts
    public function getLowStockProductsProperty()
    {
        $user = Auth::guard('tools')->user();
        
        $query = Inventories::query();
        
        // اگر فیلد admin_id وجود دارد و کاربر لاگین کرده، فیلتر کنیم
        if ($user && Schema::hasColumn('inventories', 'admin_id')) {
            $adminId = $user->admin_id ?? $user->id;
            $query->where('admin_id', $adminId);
        }

        return $query->where('total_quantity', '<=', DB::raw('min_stock_level'))
            ->where('is_active', true)
            ->get();
    }

    // Get inventory statistics
    public function getInventoryStatsProperty()
    {
        $user = Auth::guard('tools')->user();
        
        $query = Inventories::query();
        
        // اگر فیلد admin_id وجود دارد و کاربر لاگین کرده، فیلتر کنیم
        if ($user && Schema::hasColumn('inventories', 'admin_id')) {
            $adminId = $user->admin_id ?? $user->id;
            $query->where('admin_id', $adminId);
        }

        $activeProducts = $query->where('is_active', true)->get();

        return [
            'total_products' => $query->count(),
            'active_products' => $activeProducts->count(),
            'out_of_stock' => $activeProducts->where('status', 'ناموجود')->count(),
            'low_stock' => $activeProducts->where('status', 'در حال تکمیل')->count(),
            'total_value' => $activeProducts->sum(function($product) {
                return $product->total_quantity * $product->purchase_price_per_unit;
            }),
        ];
    }

    public function getProductsProperty()
    {
        $user = Auth::guard('tools')->user();
        
        $query = Inventories::query();

        // اگر فیلد admin_id وجود دارد و کاربر لاگین کرده، فیلتر کنیم
        if ($user && Schema::hasColumn('inventories', 'admin_id')) {
            $adminId = $user->admin_id ?? $user->id;
            $query->where('admin_id', $adminId);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('product_name', 'like', "%{$this->search}%")
                  ->orWhere('barcode', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedCategory) {
            $query->where('category', $this->selectedCategory);
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
    

    public function render()
    {
        return view('livewire.tools-panel.inventory', [
            'categories' => $this->categories,
            'lowStockProducts' => $this->lowStockProducts,
            'inventoryStats' => $this->inventoryStats,
            'products' => $this->products,
        ]);
    }
}