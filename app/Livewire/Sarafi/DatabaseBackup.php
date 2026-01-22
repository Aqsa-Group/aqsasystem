<?php

namespace App\Livewire\Sarafi;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DatabaseBackup extends Component
{
    use WithFileUploads;

    public $sqlFile;
    public $showImportModal = false;
    public $importStatus = '';
    public $importProgress = 0;
    public $lastBackupDate = null;

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        
        $backupFile = "backups/admin_{$adminId}_backup.sql";
        if (Storage::exists($backupFile)) {
            $this->lastBackupDate = Storage::lastModified($backupFile);
        }
    }

    public function backup()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $userIds = DB::connection('sarafi')
            ->table('users')
            ->where('id', $adminId)
            ->orWhere('admin_id', $adminId)
            ->pluck('id')
            ->toArray();

        $tables = DB::connection('sarafi')->select('SHOW TABLES');

        return response()->streamDownload(function () use ($tables, $adminId, $userIds) {
            echo "-- Admin Backup ID: {$adminId}\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $idMaps = [];

            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];

                $columns = DB::connection('sarafi')->getSchemaBuilder()->getColumnListing($tableName);
                $query = DB::connection('sarafi')->table($tableName);

                if ($tableName === 'users') {
                    $query->whereIn('id', $userIds);
                } elseif (in_array('admin_id', $columns)) {
                    $query->where('admin_id', $adminId);
                } elseif (in_array('user_id', $columns)) {
                    $query->whereIn('user_id', $userIds);
                } else {
                    continue;
                }

                $rows = $query->get();
                if ($rows->isEmpty()) continue;

                echo "-- Table: {$tableName}\n";

                foreach ($rows as $row) {
                    $row = collect($row);
                    $data = $row->toArray();

                    foreach ($data as $col => $val) {
                        if (preg_match('/_id$/', $col) && isset($idMaps[$col][$val])) {
                            $data[$col] = $idMaps[$col][$val];
                        }
                    }

                    $columnSql = implode(',', array_map(fn($col) => "`$col`", array_keys($data)));
                    $valueSql = implode(',', array_map(function($v) {
                        if ($v === null) return 'NULL';
                        return "'" . addslashes($v) . "'";
                    }, array_values($data)));
                    
                    echo "INSERT INTO `$tableName` ($columnSql) VALUES ($valueSql) ";
                    echo "ON DUPLICATE KEY UPDATE ";
                    
                    $updates = [];
                    foreach (array_keys($data) as $col) {
                        if ($col === 'id') {
                            $updates[] = "`id`=`id`";
                        } elseif ($col === 'created_at') {
                            $updates[] = "`created_at`=`created_at`";
                        } else {
                            $updates[] = "`$col`=VALUES(`$col`)";
                        }
                    }
                    echo implode(', ', $updates);
                    echo ";\n";

                    if (isset($data['id']) && isset($row['id'])) {
                        echo "SET @last_id_{$tableName}_{$row['id']} = LAST_INSERT_ID();\n";
                        $idMaps[$tableName][$row['id']] = "@last_id_{$tableName}_{$row['id']}";
                    }
                }

                echo "\n";
            }

            echo "SET FOREIGN_KEY_CHECKS=1;\n";
        }, "admin_{$adminId}_backup_" . date('Y-m-d_H-i-s') . ".sql");
    }

    public function import()
    {
        try {
            // اعتبارسنجی اولیه
            if (!$this->sqlFile) {
                Session::flash('message', 'لطفاً فایلی انتخاب کنید.');
                return;
            }

            $this->validate([
                'sqlFile' => 'required|file|mimes:sql,txt|max:10240',
            ]);

            // نمایش مودال
            $this->showImportModal = true;
            $this->importStatus = 'در حال شروع عملیات...';
            $this->importProgress = 10;

            // خواندن فایل
            $this->importStatus = 'در حال خواندن فایل...';
            $this->importProgress = 30;
            
            $content = $this->sqlFile->get();
            
            if (empty($content)) {
                throw new \Exception('فایل خالی است.');
            }

            // اجرای کوئری‌های SQL
            $this->importStatus = 'در حال اجرای کوئری‌های SQL...';
            $this->importProgress = 50;

            $success = $this->executeSqlFile($content);
            
            if ($success) {
                $this->importStatus = 'عملیات با موفقیت انجام شد!';
                $this->importProgress = 100;
                
                Session::flash('message', 'بازیابی اطلاعات با موفقیت انجام شد.');
                
                // رفرش صفحه بعد از 2 ثانیه
                $this->dispatch('import-success');
            } else {
                throw new \Exception('خطا در اجرای کوئری‌های SQL');
            }

        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            $this->importStatus = 'خطا: ' . $e->getMessage();
            $this->importProgress = 100;
            Session::flash('error', 'خطا در بازیابی: ' . $e->getMessage());
        }
    }

    /**
     * اجرای مستقیم کوئری‌های SQL
     */
    private function executeSqlFile($content)
    {
        try {
            // غیرفعال کردن بررسی کلیدهای خارجی برای سرعت بیشتر
            DB::connection('sarafi')->statement('SET FOREIGN_KEY_CHECKS = 0;');
            
            // تقسیم فایل به کوئری‌های جداگانه
            $queries = $this->splitSql($content);
            
            $total = count($queries);
            $processed = 0;
            
            foreach ($queries as $query) {
                $query = trim($query);
                
                if (empty($query) || str_starts_with(strtoupper($query), '--')) {
                    continue;
                }
                
                try {
                    DB::connection('sarafi')->statement($query);
                    $processed++;
                    
                    // بروزرسانی پیشرفت
                    $progress = 50 + (($processed / $total) * 40);
                    $this->importProgress = min($progress, 95);
                    $this->importStatus = "در حال اجرای کوئری‌ها ($processed/$total)...";
                    
                } catch (\Exception $e) {
                    Log::warning('Query failed: ' . substr($query, 0, 100) . '... Error: ' . $e->getMessage());
                    // ادامه می‌دهیم اگر یک کوئری شکست خورد
                }
            }
            
            // فعال کردن مجدد بررسی کلیدهای خارجی
            DB::connection('sarafi')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            
            Log::info('Successfully executed ' . $processed . ' out of ' . $total . ' queries');
            return true;
            
        } catch (\Exception $e) {
            Log::error('executeSqlFile error: ' . $e->getMessage());
            // در هر صورت بررسی کلیدهای خارجی را فعال کن
            try {
                DB::connection('sarafi')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            } catch (\Exception $e2) {
                Log::error('Failed to re-enable foreign key checks: ' . $e2->getMessage());
            }
            return false;
        }
    }

    /**
     * تقسیم SQL به کوئری‌های جداگانه (ساده شده)
     */
    private function splitSql($sql)
    {
        // حذف کامنت‌ها
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // تقسیم بر اساس سمی‌کالن
        $queries = explode(';', $sql);
        
        // فیلتر کردن کوئری‌های خالی
        $queries = array_filter(array_map('trim', $queries), function($query) {
            return !empty($query) && !str_starts_with(strtoupper($query), 'DELIMITER');
        });
        
        // اضافه کردن سمی‌کالن به انتهای هر کوئری
        $queries = array_map(function($query) {
            return rtrim($query, ';') . ';';
        }, $queries);
        
        return $queries;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importStatus = '';
        $this->importProgress = 0;
        $this->sqlFile = null;
        
        // رفرش صفحه برای پاک کردن فایل
        $this->dispatch('refresh-page');
    }

    public function render()
    {
        return view('livewire.sarafi.database-backup');
    }
}