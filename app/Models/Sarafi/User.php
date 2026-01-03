<?php

namespace App\Models\Sarafi;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;


class User extends Authenticatable
{
    protected $connection = 'sarafi';
    protected $table = 'users';
    protected $guard = 'sarafi';

    protected $fillable = [
        'name',
        'lastname',
        'sarafi_name',
        'address',
        'phone',
        'username',
        'password',
        'role',
        'user_limition',
        'status',
        'admin_id',
        'zone',
        'address2',
        'address3',
        'phone2',
        'phone3',
        'whatsapp_notification',
        'user_image'


    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'status' => 'boolean',
        'user_limition' => 'integer',
        'admin_id' => 'integer',
        'password' => 'hashed',
        'whatsapp_notification' => 'boolean',

    ];

// تابع برای دسترسی به تصویر کامل کاربر
    public function getImageUrlAttribute()
    {
        if ($this->user_image && file_exists(public_path('storage/' . $this->user_image))) {
            return asset('storage/' . $this->user_image);
        }
        
        return asset('assets/sarafi/avatar.svg'); // تصویر پیش‌فرض
    }
    
    // تابع برای دسترسی به تصویر کوچک (برای هدر)
    public function getThumbnailUrlAttribute()
    {
        if ($this->user_image) {
            // اگر می‌خواهید نسخه کوچک‌شده داشته باشید
            $path = $this->user_image;
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $thumbnail = 'users/thumbnails/' . $filename . '_thumb.' . $ext;
            
            if (file_exists(public_path('storage/' . $thumbnail))) {
                return asset('storage/' . $thumbnail);
            }
            
            // اگر نسخه کوچک وجود ندارد، همان تصویر اصلی را برگردان
            return asset('storage/' . $this->user_image);
        }
        
        return asset('assets/sarafi/avatar.svg');
    }
      
    // Function to compress and save image
    public static function compressAndSaveImage($image, $userId = null)
    {
        if (!$image) {
            return null;
        }
        
        try {
            $imageName = 'user_' . ($userId ?? time()) . '_' . uniqid() . '.webp';
            $imagePath = public_path('storage/users/' . $imageName);
            
            // Create directory if not exists
            if (!file_exists(public_path('storage/users'))) {
                mkdir(public_path('storage/users'), 0755, true);
            }
            
            // Compress and convert to webp format
            $img = Image::make($image->getRealPath());
            
            // Resize if image is larger than 800px in any dimension
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Convert to WebP format with 80% quality
            $img->encode('webp', 80)->save($imagePath);
            
            return 'users/' . $imageName;
            
        } catch (\Exception $e) {
            Log::error('Image compression failed: ' . $e->getMessage());
            return null;
        }
    }
    
    // Function to delete old image
    public function deleteOldImage()
    {
        if ($this->user_image && file_exists(public_path('storage/' . $this->user_image))) {
            unlink(public_path('storage/' . $this->user_image));
        }
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

public function customers()
{
    return $this->belongsToMany(
        Customer::class,
        'customer_admin',
        'admin_id',
        'customer_id'
    );
}

}
