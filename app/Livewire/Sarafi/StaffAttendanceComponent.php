<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\StaffAttendance;
use App\Models\Sarafi\Staffs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Illuminate\Support\Facades\Log;

class StaffAttendanceComponent extends Component
{
    use WithPagination;

    public $staffs = [];
    public $selectedStaff = null;
    public $selectedDate;
    public $attendanceData = [];
    public $monthFilter;
    public $yearFilter;
    public $leaveType = 'none';
    public $isPaid = true;
    public $morningTime;
    public $eveningTime;
    public $note;

    // فیلترهای جدید برای جدول روزانه
    public $filterEmployee = '';
    public $filterStatus = '';
    public $filterMonth = '';

    // پراپرتی جدید برای تشخیص حالت نمایش
    public $isMonthlyView = false;

    protected $listeners = ['refreshAttendance' => 'loadAttendance'];

    public function mount()
    {
        $today = Jalalian::now();
        $this->selectedDate = $today->format('Y-m-d');
        $this->monthFilter = $today->getMonth();
        $this->yearFilter = $today->getYear();

        $this->loadStaffs();
        $this->loadAttendance();
    }

    public function loadStaffs()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $query = Staffs::where('admin_id', $adminId);

        if ($this->filterEmployee) {
            $query->where('id', $this->filterEmployee);
        }

        $this->staffs = $query->orderBy('name')->get()->toArray();
    }

    public function loadAttendance()
    {
        $this->attendanceData = [];
        $this->morningTime = '';
        $this->eveningTime = '';
        $this->note = '';

        if ($this->selectedStaff) {
            $attendance = StaffAttendance::where('staff_id', $this->selectedStaff)
                ->where('attendance_date', $this->selectedDate)
                ->first();

            if ($attendance) {
                $this->attendanceData = [
                    'morning_present' => $attendance->morning_present,
                    'evening_present' => $attendance->evening_present,
                    'leave_type' => $attendance->leave_type,
                    'is_paid' => $attendance->is_paid
                ];
                $this->morningTime = $attendance->morning_time ? substr($attendance->morning_time, 0, 5) : '';
                $this->eveningTime = $attendance->evening_time ? substr($attendance->evening_time, 0, 5) : '';
                $this->note = $attendance->note;
            } else {
                $this->attendanceData = [
                    'morning_present' => false,
                    'evening_present' => false,
                    'leave_type' => 'none',
                    'is_paid' => true
                ];
            }
        }
    }

    public function updatedSelectedStaff($value)
    {
        $this->selectedStaff = $value;
        $this->loadAttendance();
    }

    public function updatedSelectedDate($value)
    {
        $this->loadAttendance();
    }

    public function updatedFilterEmployee()
    {
        $this->loadStaffs();
    }

    public function updatedFilterStatus()
    {
        $this->dispatch('refreshAttendance');
    }

    public function updatedFilterMonth($value)
    {
        // تغییر در فیلتر ماه - بررسی اینکه آیا ماه انتخاب شده یا نه
        $this->isMonthlyView = !empty($value);

        if ($value) {
            // تاریخ انتخابی را به اولین روز آن ماه تغییر بده
            $this->selectedDate = sprintf('%04d-%02d-01', $this->yearFilter, $value);
        } else {
            // اگر ماه انتخاب نشد، برگرد به تاریخ امروز
            $today = Jalalian::now();
            $this->selectedDate = $today->format('Y-m-d');
        }

        $this->dispatch('refreshAttendance');
    }

    public function updatedYearFilter($value)
    {
        $this->dispatch('refreshAttendance');
    }

    public function resetFilters()
    {
        $this->filterEmployee = '';
        $this->filterStatus = '';
        $this->filterMonth = '';
        $this->isMonthlyView = false;

        $today = Jalalian::now();
        $this->selectedDate = $today->format('Y-m-d');
        $this->monthFilter = $today->getMonth();
        $this->yearFilter = $today->getYear();

        $this->loadStaffs();
        $this->loadAttendance();

        session()->flash('message', 'فیلترها با موفقیت ریست شدند.');
    }

    public function getFilteredStaffsArray()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // اول همه کارمندان را بگیریم
        $query = Staffs::where('admin_id', $adminId);

        if ($this->filterEmployee) {
            $query->where('id', $this->filterEmployee);
        }

        $allStaffs = $query->orderBy('name')->get()->toArray();

        $filteredStaffs = [];

        foreach ($allStaffs as $staff) {
            // اگر فیلتر ماه فعال باشد (حالت ماهانه)
            if ($this->filterMonth && $this->isMonthlyView) {
                // ساخت الگوی تاریخ برای ماه انتخابی
                $datePattern = sprintf('%04d-%02d-%%', $this->yearFilter, $this->filterMonth);

                // بررسی کن آیا در این ماه حضور و غیاب ثبت شده یا نه
                $hasAttendanceInMonth = StaffAttendance::where('staff_id', $staff['id'])
                    ->where('attendance_date', 'LIKE', $datePattern)
                    ->exists();

                // اگر این کارمند در این ماه حضور نداشته، نشانش نده
                if (!$hasAttendanceInMonth) {
                    continue;
                }
            }

            // اگر فیلتر وضعیت فعال باشد و حالت روزانه باشد
            if ($this->filterStatus && !$this->isMonthlyView) {
                // حضور و غیاب برای تاریخ انتخابی (در حالت روزانه)
                $attendance = StaffAttendance::where('staff_id', $staff['id'])
                    ->where('attendance_date', $this->selectedDate)
                    ->first();

                if ($this->filterStatus === 'حاضر') {
                    // اگر هیچ حضوری نداشته باشد
                    if (!$attendance || (!$attendance->morning_present && !$attendance->evening_present)) {
                        continue;
                    }
                } elseif ($this->filterStatus === 'غیرحاضر') {
                    // اگر حضوری داشته باشد
                    if ($attendance && ($attendance->morning_present || $attendance->evening_present)) {
                        continue;
                    }
                }
            }

            $filteredStaffs[] = $staff;
        }

        return $filteredStaffs;
    }

    public function printReport()
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            // تعیین اینکه گزارش برای کل ماه است یا فقط روز جاری
            $isMonthlyReport = $this->filterMonth && $this->isMonthlyView;

            // اطلاعات کارمندان با توجه به فیلترها
            $staffsArray = $this->getFilteredStaffsArray();

            // آمار کلی
            $totalStaffs = count($staffsArray);
            $totalPresent = 0;
            $totalAbsent = 0;
            $totalLeave = 0;
            $totalSalary = 0;

            // داده‌های جدول
            $tableData = [];

            foreach ($staffsArray as $index => $staff) {
                if ($isMonthlyReport) {
                    // گزارش ماهانه - تمام روزهای ماه
                    $monthData = $this->getStaffMonthlyReportData($staff['id'], $this->yearFilter, $this->filterMonth);

                    $tableData = array_merge($tableData, $monthData);
                } else {
                    // گزارش روزانه - فقط تاریخ انتخابی
                    $attendance = StaffAttendance::where('staff_id', $staff['id'])
                        ->where('attendance_date', $this->selectedDate)
                        ->first();

                    $status = 'غایب';
                    $dailySalary = 0;

                    if ($attendance) {
                        $dailySalary = $attendance->daily_salary;
                        $totalSalary += $dailySalary;

                        if ($attendance->leave_type !== 'none') {
                            $status = 'مرخصی';
                            $totalLeave++;
                        } elseif ($attendance->morning_present || $attendance->evening_present) {
                            $status = 'حاضر';
                            $totalPresent++;
                        } else {
                            $status = 'غایب';
                            $totalAbsent++;
                        }

                        // تاریخ شمسی
                        $attendanceDate = Jalalian::fromFormat('Y-m-d', $attendance->attendance_date)->format('Y/m/d');
                    } else {
                        $status = 'غایب';
                        $totalAbsent++;
                        $attendanceDate = Jalalian::fromFormat('Y-m-d', $this->selectedDate)->format('Y/m/d');
                    }

                    $tableData[] = [
                        'index' => $index + 1,
                        'name' => $staff['name'] . ' ' . $staff['fathername'],
                        'job' => $staff['job'],
                        'morning_status' => $attendance && $attendance->morning_present ? 'حاضر' : 'غایب',
                        'morning_time' => $attendance && $attendance->morning_time
                            ? $attendance->morning_time
                            : '-',
                        'evening_status' => $attendance && $attendance->evening_present ? 'حاضر' : 'غایب',
                        'evening_time' => $attendance && $attendance->evening_time
                            ? $attendance->evening_time
                            : '-',
                        'leave_type' => $attendance ? $this->getLeaveTypeLabel($attendance->leave_type) : '-',
                        'status' => $status,
                        'attendance_date' => $attendanceDate,
                        'daily_salary' => number_format($dailySalary),
                    ];
                }
            }

            // عنوان گزارش
            if ($isMonthlyReport) {
                $reportTitle = "گزارش ماه {$this->getPersianMonthName($this->filterMonth)} سال {$this->yearFilter}";
                $reportDate = "{$this->yearFilter}/{$this->filterMonth}";
                $reportDay = 'گزارش ماهانه';
            } else {
                $selectedJalali = Jalalian::fromFormat('Y-m-d', $this->selectedDate);
                $reportDate = $selectedJalali->format('Y/m/d');
                $reportDay = $this->getPersianDayFromDate($this->selectedDate);
                $reportTitle = "گزارش حضور و غیاب تاریخ {$reportDate}";
            }

            // تنظیمات mPDF
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 9,
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_header' => 5,
                'margin_footer' => 5,
                'orientation' => 'L',
                'directionality' => 'rtl',
                'fontDir' => array_merge($fontDirs, [
                    public_path('fonts'),
                    storage_path('fonts'),
                ]),
                'fontdata' => $fontData + [
                    'dejavusans' => [
                        'R' => 'DejaVuSans.ttf',
                        'B' => 'DejaVuSans-Bold.ttf',
                        'I' => 'DejaVuSans-Oblique.ttf',
                        'BI' => 'DejaVuSans-BoldOblique.ttf',
                    ],
                    'vazir' => [
                        'R' => 'Vazir.ttf',
                        'B' => 'Vazir-Bold.ttf',
                    ]
                ],
                'tempDir' => storage_path('app/mpdf/tmp'),
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'autoArabic' => true,
            ]);

            // HTML برای PDF
            $html = $this->generateReportHtml(
                $reportTitle,
                $reportDate,
                $reportDay,
                $tableData,
                $totalStaffs,
                $totalPresent,
                $totalAbsent,
                $totalLeave,
                $totalSalary,
                $isMonthlyReport
            );

            $mpdf->WriteHTML($html);

            $fileName = 'attendance-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            $path = storage_path('app/public/reports/' . $fileName);

            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }

            $mpdf->Output($path, 'F');

            // dispatch event برای باز کردن PDF
            $this->dispatch('print-pdf', url: asset('storage/reports/' . $fileName));

            session()->flash('message', 'گزارش با موفقیت تولید شد و برای چاپ آماده است.');
        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            session()->flash('message', 'خطا در تولید گزارش: ' . $e->getMessage());
        }
    }

    private function getStaffMonthlyReportData($staffId, $year, $month)
    {
        $data = [];

        // تعداد روزهای ماه
        $daysInMonth = $this->getJalaliDaysInMonth($year, $month);

        // ساخت الگوی تاریخ برای جستجو
        $datePattern = sprintf('%04d-%02d-%%', $year, $month);

        // دریافت همه حضور و غیاب‌های این کارمند در این ماه
        $attendances = StaffAttendance::where('staff_id', $staffId)
            ->where('attendance_date', 'LIKE', $datePattern)
            ->get()
            ->keyBy(function ($item) {
                return $item->attendance_date;
            });

        // اطلاعات کارمند
        $staff = Staffs::find($staffId);

        // برای هر روز ماه
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $attendance = $attendances->get($date);

            $status = 'غایب';
            $dailySalary = 0;

            if ($attendance) {
                $dailySalary = $attendance->daily_salary;

                if ($attendance->leave_type !== 'none') {
                    $status = 'مرخصی';
                } elseif ($attendance->morning_present || $attendance->evening_present) {
                    $status = 'حاضر';
                } else {
                    $status = 'غایب';
                }
            }

            $data[] = [
                'index' => $day,
                'name' => $staff->name . ' ' . $staff->fathername,
                'job' => $staff->job,
                'morning_status' => $attendance && $attendance->morning_present ? 'حاضر' : 'غایب',
                'morning_time' => $attendance && $attendance->morning_time
                    ? $attendance->morning_time
                    : '-',
                'evening_status' => $attendance && $attendance->evening_present ? 'حاضر' : 'غایب',
                'evening_time' => $attendance && $attendance->evening_time
                    ? $attendance->evening_time
                    : '-',
                'leave_type' => $attendance ? $this->getLeaveTypeLabel($attendance->leave_type) : '-',
                'status' => $status,
                'attendance_date' => Jalalian::fromFormat('Y-m-d', $date)->format('Y/m/d'),
                'daily_salary' => number_format($dailySalary),
            ];
        }

        return $data;
    }

    private function generateReportHtml($title, $date, $day, $tableData, $totalStaffs, $totalPresent, $totalAbsent, $totalLeave, $totalSalary, $isMonthly)
    {
        $monthNames = [
            1 => 'حمل',
            2 => 'ثور',
            3 => 'جوزا',
            4 => 'سرطان',
            5 => 'اسد',
            6 => 'سنبله',
            7 => 'میزان',
            8 => 'عقرب',
            9 => 'قوس',
            10 => 'جدی',
            11 => 'دلو',
            12 => 'حوت',
        ];

        $html = '
        <!DOCTYPE html>
        <html lang="fa">
        <head>
            <meta charset="UTF-8">
            <title>' . $title . '</title>
            <style>
                @font-face {
                    font-family: "Vazir";
                    src: url("' . public_path('fonts/Vazir.ttf') . '") format("truetype");
                }
                
                body {
                    font-family: "Vazir", sans-serif;
                    direction: rtl;
                    text-align: right;
                    margin: 0;
                    padding: 20px;
                    font-size: 12px;
                }
                
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                }
                
                .header h1 {
                    color: #2563EB;
                    margin: 0;
                    font-size: 24px;
                }
                
                .header h2 {
                    color: #666;
                    margin: 10px 0;
                    font-size: 18px;
                }
                
                .date-info {
                    background: #f5f5f5;
                    padding: 15px;
                    border-radius: 10px;
                    margin: 20px 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                
                .stats {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 15px;
                    margin: 20px 0;
                }
                
                .stat-box {
                    background: #2563EB;
                    color: white;
                    padding: 15px;
                    border-radius: 10px;
                    text-align: center;
                }
                
                .stat-box h3 {
                    margin: 0;
                    font-size: 14px;
                }
                
                .stat-box .value {
                    font-size: 24px;
                    font-weight: bold;
                    margin: 10px 0;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-size: 10px;
                }
                
                th {
                    background: #2563EB;
                    color: white;
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #ddd;
                    font-size: 11px;
                }
                
                td {
                    padding: 8px;
                    text-align: center;
                    border: 1px solid #ddd;
                }
                
                tr:nth-child(even) {
                    background: #f9f9f9;
                }
                
                .footer {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #333;
                    text-align: left;
                    font-size: 10px;
                    color: #666;
                }
                
                .status-present {
                    color: #10B981;
                    font-weight: bold;
                }
                
                .status-absent {
                    color: #EF4444;
                    font-weight: bold;
                }
                
                .status-leave {
                    color: #F59E0B;
                    font-weight: bold;
                }
                
                .total-row {
                    background: #f0f9ff;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . $title . '</h1>
                <h2>سیستم حضور و غیاب صرافی</h2>
            </div>';

        if (!$isMonthly) {
            $html .= '
            <div class="stats">
               
            </div>';
        }

        $html .= '
            <table>
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th width="15%">نام کارمند</th>
                        <th width="10%">شغل</th>
                        <th width="8%">وضعیت صبح</th>
                        <th width="8%">زمان صبح</th>
                        <th width="8%">وضعیت عصر</th>
                        <th width="8%">زمان عصر</th>
                        <th width="10%">نوع مرخصی</th>
                        <th width="10%">وضعیت روز</th>
                        <th width="10%">تاریخ</th>
                        <th width="10%">معاش روز (افغانی)</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($tableData as $row) {
            $attendanceDate = $row['attendance_date'];

            $html .= '
                    <tr>
                        <td>' . $row['index'] . '</td>
                        <td>' . $row['name'] . '</td>
                        <td>' . $row['job'] . '</td>
                        <td class="status-' . ($row['morning_status'] == 'حاضر' ? 'present' : 'absent') . '">' . $row['morning_status'] . '</td>
                        <td>' . $row['morning_time'] . '</td>
                        <td class="status-' . ($row['evening_status'] == 'حاضر' ? 'present' : 'absent') . '">' . $row['evening_status'] . '</td>
                        <td>' . $row['evening_time'] . '</td>
                        <td>' . $row['leave_type'] . '</td>
                        <td class="status-' . ($row['status'] == 'حاضر' ? 'present' : ($row['status'] == 'مرخصی' ? 'leave' : 'absent')) . '">' . $row['status'] . '</td>
                        <td>' . $attendanceDate . '</td>
                        <td>' . $row['daily_salary'] . '</td>
                    </tr>';
        }

        if (!$isMonthly) {
            $html .= '
                    <tr class="total-row">
                        <td colspan="10" style="text-align: left; padding-left: 20px;"><strong>مجموع معاش روز:</strong></td>
                        <td><strong>' . number_format($totalSalary) . ' افغانی</strong></td>
                    </tr>';
        } else {
            // محاسبه مجموع ماهانه برای گزارش ماهانه
            $monthlyTotal = collect($tableData)->sum(function ($item) {
                return (float) str_replace(',', '', $item['daily_salary']);
            });

            $html .= '
                    <tr class="total-row">
                        <td colspan="10" style="text-align: left; padding-left: 20px;"><strong>مجموع معاش ماه:</strong></td>
                        <td><strong>' . number_format($monthlyTotal) . ' افغانی</strong></td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
          
        </body>
        </html>';

        return $html;
    }

    private function getLeaveTypeLabel($leaveType)
    {
        $labels = [
            'none' => 'بدون مرخصی',
            'morning' => 'مرخصی صبح',
            'evening' => 'مرخصی شام',
            'full_day' => 'مرخصی کامل'
        ];

        return $labels[$leaveType] ?? $leaveType;
    }

    private function getPersianMonthName($month)
    {
        $months = [
            1 => 'حمل',
            2 => 'ثور',
            3 => 'جوزا',
            4 => 'سرطان',
            5 => 'اسد',
            6 => 'سنبله',
            7 => 'میزان',
            8 => 'عقرب',
            9 => 'قوس',
            10 => 'جدی',
            11 => 'دلو',
            12 => 'حوت',
        ];

        return $months[$month] ?? 'نامشخص';
    }

    public function saveAttendance()
    {
        if (!$this->selectedStaff) {
            $this->dispatch('show-alert', [
                'title' => 'خطا',
                'message' => 'لطفاً کارمند را انتخاب کنید',
                'type' => 'error'
            ]);
            return;
        }

        // اعتبارسنجی زمان‌ها
        if ($this->attendanceData['morning_present'] && !$this->validateTime($this->morningTime)) {
            $this->dispatch('show-alert', [
                'title' => 'خطا',
                'message' => 'فرمت زمان صبح صحیح نیست (مثال: 08:30)',
                'type' => 'error'
            ]);
            return;
        }

        if ($this->attendanceData['evening_present'] && !$this->validateTime($this->eveningTime)) {
            $this->dispatch('show-alert', [
                'title' => 'خطا',
                'message' => 'فرمت زمان شام صحیح نیست (مثال: 13:00)',
                'type' => 'error'
            ]);
            return;
        }

        // بررسی منطقی بودن داده‌ها
        if ($this->attendanceData['leave_type'] === 'full_day') {
            $this->attendanceData['morning_present'] = false;
            $this->attendanceData['evening_present'] = false;
            $this->morningTime = null;
            $this->eveningTime = null;
        } elseif ($this->attendanceData['leave_type'] === 'morning') {
            $this->attendanceData['morning_present'] = false;
            $this->morningTime = null;
        } elseif ($this->attendanceData['leave_type'] === 'evening') {
            $this->attendanceData['evening_present'] = false;
            $this->eveningTime = null;
        }

        // ذخیره یا بروزرسانی حضور و غیاب
        $attendance = StaffAttendance::updateOrCreate(
            [
                'staff_id' => $this->selectedStaff,
                'attendance_date' => $this->selectedDate
            ],
            [
                'morning_present' => $this->attendanceData['morning_present'],
                'evening_present' => $this->attendanceData['evening_present'],
                'morning_time' => $this->attendanceData['morning_present']
                    ? $this->morningTime
                    : null,

                'evening_time' => $this->attendanceData['evening_present']
                    ? $this->eveningTime
                    : null,

                'leave_type' => $this->attendanceData['leave_type'],
                'is_paid' => $this->attendanceData['is_paid'],
                'note' => $this->note,
                'daily_salary' => 0
            ]
        );

        // محاسبه و ذخیره معاش روزانه
        $dailySalary = $attendance->calculateDailySalary();
        $attendance->update(['daily_salary' => $dailySalary]);

        $this->dispatch('show-alert', [
            'title' => 'موفقیت',
            'message' => 'حضور و غیاب با موفقیت ثبت شد',
            'type' => 'success'
        ]);

        $this->dispatch('refreshAttendance');
    }

    public function quickAttendance($staffId, $type, $time = null)
    {
        $attendance = StaffAttendance::where('staff_id', $staffId)
            ->where('attendance_date', $this->selectedDate)
            ->first();

        if (!$attendance) {
            $attendance = StaffAttendance::create([
                'staff_id' => $staffId,
                'attendance_date' => $this->selectedDate,
                'morning_present' => false,
                'evening_present' => false,
                'leave_type' => 'none',
                'is_paid' => true,
                'daily_salary' => 0
            ]);
        }

        $currentTime = $time ?: Carbon::now('Asia/Kabul')->format('h:i A');

        switch ($type) {
            case 'morning_present':
                if (!$attendance->morning_present) {
                    $attendance->morning_time = $currentTime;
                    $attendance->morning_present = true;
                    $attendance->leave_type = $attendance->leave_type === 'morning' ? 'none' : $attendance->leave_type;
                } else {
                    $attendance->morning_time = null;
                    $attendance->morning_present = false;
                }
                break;

            case 'evening_present':
                if (!$attendance->evening_present) {
                    $attendance->evening_time = $currentTime;
                    $attendance->evening_present = true;
                    $attendance->leave_type = $attendance->leave_type === 'evening' ? 'none' : $attendance->leave_type;
                } else {
                    $attendance->evening_time = null;
                    $attendance->evening_present = false;
                }
                break;

            case 'full_day_leave':
                $attendance->leave_type = $attendance->leave_type === 'full_day' ? 'none' : 'full_day';
                if ($attendance->leave_type === 'full_day') {
                    $attendance->morning_present = false;
                    $attendance->evening_present = false;
                    $attendance->morning_time = null;
                    $attendance->evening_time = null;
                }
                break;
        }

        // محاسبه و ذخیره معاش روزانه
        $dailySalary = $attendance->calculateDailySalary();
        $attendance->daily_salary = $dailySalary;

        $attendance->save();

        $this->dispatch('refreshAttendance');
    }


    public function recordCurrentTime($type)
    {
        $currentTime = Carbon::now('Asia/Kabul')->format('h:i A');

        if ($type === 'morning') {
            $this->morningTime = $currentTime;
            $this->attendanceData['morning_present'] = true;
            $this->attendanceData['leave_type'] = 'none';
        } elseif ($type === 'evening') {
            $this->eveningTime = $currentTime;
            $this->attendanceData['evening_present'] = true;
            $this->attendanceData['leave_type'] = 'none';
        }
    }


    private function validateTime($time)
    {
        if (empty($time)) return true;

        // فرمت HH:MM AM/PM
        return preg_match('/^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i', $time);
    }

    public function generateMonthlyReport()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $staffs = Staffs::where('admin_id', $adminId)->get();
        $report = [];

        // تعداد روزهای ماه شمسی
        $totalDays = $this->getJalaliDaysInMonth($this->yearFilter, $this->monthFilter);

        foreach ($staffs as $staff) {
            // ساخت الگوی تاریخ برای جستجو (مثلاً 1404-10-%)
            $datePattern = sprintf('%04d-%02d-%%', $this->yearFilter, $this->monthFilter);

            // جستجو بر اساس الگوی تاریخ شمسی
            $attendances = StaffAttendance::where('staff_id', $staff->id)
                ->where('attendance_date', 'LIKE', $datePattern)
                ->get();

            $totalSalary = $attendances->sum('daily_salary');

            // محاسبه روزهای کامل
            $fullDays = $attendances->filter(function ($attendance) {
                return $attendance->morning_present && $attendance->evening_present;
            })->count();

            // محاسبه نیمه روزها
            $halfDays = $attendances->filter(function ($attendance) {
                return !$attendance->morning_present && $attendance->evening_present ||
                    $attendance->morning_present && !$attendance->evening_present;
            })->count();

            // محاسبه روزهای مرخصی
            $leaveDays = $attendances->filter(function ($attendance) {
                return $attendance->leave_type !== 'none';
            })->count();

            $report[] = [
                'staff' => $staff,
                'total_salary' => $totalSalary,
                'full_days' => $fullDays,
                'half_days' => $halfDays,
                'leave_days' => $leaveDays,
                'absent_days' => $totalDays - ($fullDays + $halfDays + $leaveDays)
            ];
        }

        return $report;
    }

    private function getJalaliDaysInMonth($year, $month)
    {
        $daysInMonth = [
            1 => 31,   // فروردین
            2 => 31,   // اردیبهشت
            3 => 31,   // خرداد
            4 => 31,   // تیر
            5 => 31,   // مرداد
            6 => 31,   // شهریور
            7 => 30,   // مهر
            8 => 30,   // آبان
            9 => 30,   // آذر
            10 => 30,  // دی
            11 => 30,  // بهمن
            12 => 29   // اسفند
        ];

        // بررسی سال کبیسه برای اسفند
        if ($month == 12 && $this->isJalaliLeapYear($year)) {
            return 30;
        }

        return $daysInMonth[$month] ?? 30;
    }

    private function isJalaliLeapYear($year)
    {
        // فرمول تشخیص سال کبیسه هجری شمسی
        $remainders = [1, 5, 9, 13, 17, 22, 26, 30];
        return in_array($year % 33, $remainders);
    }

    // در متد render() تغییرات زیر را اضافه کنید:
    public function render()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // لیست کارمندان برای dropdown
        $staffList = Staffs::where('admin_id', $adminId)
            ->orderBy('name')
            ->paginate(10);

        // گزارش ماهانه
        $monthlyReport = $this->generateMonthlyReport();
        $totalMonthlySalary = collect($monthlyReport)->sum('total_salary');

        // اطلاعات کارمند انتخاب شده
        $selectedStaffData = $this->selectedStaff ? Staffs::find($this->selectedStaff) : null;

        // تاریخ شمسی انتخابی و روز هفته
        $persianDate = Jalalian::fromFormat('Y-m-d', $this->selectedDate)->format('Y/m/d');
        $persianDay = $this->getPersianDayFromDate($this->selectedDate);

        $persianMonths = [
            1 => 'حمل',
            2 => 'ثور',
            3 => 'جوزا',
            4 => 'سرطان',
            5 => 'اسد',
            6 => 'سنبله',
            7 => 'میزان',
            8 => 'عقرب',
            9 => 'قوس',
            10 => 'جدی',
            11 => 'دلو',
            12 => 'حوت',
        ];

        return view('livewire.sarafi.staff-attendance-component', [
            'staffList' => $staffList,
            'monthlyReport' => $monthlyReport,
            'totalMonthlySalary' => $totalMonthlySalary,
            'selectedStaff' => $selectedStaffData,
            'currentAttendance' => $this->attendanceData,
            'persianDate' => $persianDate,
            'persianDay' => $persianDay,
            'persianMonths' => $persianMonths,
            'staffsArray' => $this->getFilteredStaffsArray(),
            'isMonthlyView' => $this->isMonthlyView,
            'selectedDate' => $this->selectedDate // این خط را اضافه کنید
        ]);
    }

    private function getPersianDayFromDate($dateString)
    {
        try {
            $parts = explode('-', $dateString);
            if (count($parts) !== 3) {
                return '';
            }

            list($year, $month, $day) = $parts;

            // تبدیل به Jalalian برای محاسبه روز هفته
            $jalaliDate = Jalalian::fromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $day));
            $dayOfWeek = $jalaliDate->getDayOfWeek();

            return $this->getPersianDayName($dayOfWeek);
        } catch (\Exception $e) {
            return '';
        }
    }

    private function getPersianDayName($dayOfWeek)
    {
        $days = [
            0 => 'شنبه',
            1 => 'یکشنبه',
            2 => 'دوشنبه',
            3 => 'سه‌شنبه',
            4 => 'چهارشنبه',
            5 => 'پنج‌شنبه',
            6 => 'جمعه'
        ];

        return $days[$dayOfWeek] ?? '';
    }

    // متد برای حذف حضور و غیاب
    public function deleteAttendance($staffId, $date = null)
    {
        $date = $date ?: $this->selectedDate;

        $attendance = StaffAttendance::where('staff_id', $staffId)
            ->where('attendance_date', $date)
            ->first();

        if ($attendance) {
            $attendance->delete();

            session()->flash('message', 'حضور و غیاب حذف شد');

            $this->dispatch('refreshAttendance');
        }
    }
}
