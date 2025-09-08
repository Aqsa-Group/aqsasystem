<?php

 header('Content-Type: application/json; charset=utf-8');
 header('Access-Control-Allow-Origin: *');
 header("Access-Control-Allow-Methods: POST, GET");
 header('Access-Control-Allow-Headers: Content-Type');
 

 $userName = "aqsasystem_aqsa";
 $password = "Herat@2025";
 $db = "aqsasystem_marketpanel";
 $host = "localhost";


 $conn = new mysqli($host,$userName,$password,$db);
 $conn->set_charset("utf8mb4");


  if ($conn->connect_error) {
     die(json_encode([
        "success" => false,
        "message" => "Connection Faild".$conn->connect_error
     ]));
  }

    $method = $_SERVER["REQUEST_METHOD"];
    $input = [];

// خواندن داده‌ها بسته به نوع درخواست
if ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input) $input = $_POST;
} elseif ($method === "GET") {
    $input = $_GET;
} else {
    echo json_encode([
        "success" => false,
        "message" => "Only GET and POST methods are supported."
    ]);
    exit;
}


if (isset($input['action'])) {
    $action = $input['action'];

    if ($action == "checkUser") {
        checkUser($conn, $input);
    } elseif ($action == "showUserPassword") {
        showUserPassword($conn, $input);
    } elseif ($action == "getPayments") {
        getPayments($conn, $input);
    } elseif ($action == "acount_details") {
        Acountdetails($conn, $input);
    } else if ($action == "advertisment") {
         Advertisment($conn,$input);
    }else if($action == "shopdetails"){
        ShopDetails($conn , $input);
    }else if($action == "notifications"){
         Notifications($conn,$input);
    }else{
        echo json_encode([
            "success" => false,
            "message" => "Invalid action."
        ]);
    }
 } else {
    echo json_encode([
        "success" => false,
        "message" => "Missing 'action' parameter."
    ]);
}


# -------------- For Login Checking -------------

function checkUser($conn, $input) {
    $userName = $input['userName'];
    $password = $input['password'];

    $sql = "SELECT id, username FROM shopkeepers WHERE username =? AND password =?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "SQL Prepare Error: " . $conn->error
        ]);
        return;
    }

    $stmt->bind_param("ss", $userName, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    $item = [];

    if ($row = $result->fetch_assoc()) {
        $item = $row;
        echo json_encode([
            "success" => true,
            "data" => $item
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "🚨 نام کاربری یا رمز عبور اشتباه است"
        ]);
    }
}


function showUserPassword($conn, $input) {
    $userName = $input['username'];
    $phoneNumber = $input['phonenumber'];
    $nationId = $input['nationId'];

    $sql = "SELECT password FROM shopkeepers WHERE username =? AND phone =? AND national_id =? ";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "SQL Prepare Error: " . $conn->error
        ]);
        return;
    }

    $stmt->bind_param("sss", $userName, $phoneNumber, $nationId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success"=> true,
            "message"=> $row
        ]);
    } else {
        echo json_encode([
            "success"=> false,
            "message" => "اطلاعات نادرست است"
        ]);
    }
}

# -------------- For Payment page ---------------

function getPayments($conn, $input) {
    $shopkeeper_id = $input['shopkeeper_id'];

    $sql = '
SELECT 
    deposit_logs.new_paid, 
    deposit_logs.created_at, 
    deposit_logs.expanses_type,
    shopkeepers.fullname, 
    shopkeepers.shopkeeper_image,
    accountings.past_degree,
    accountings.current_degree,
    accountings.paid_date,
    accountings.expiration_date
FROM deposit_logs
JOIN shopkeepers 
    ON deposit_logs.shopkeeper_id = shopkeepers.id
LEFT JOIN accountings
    ON deposit_logs.shopkeeper_id = accountings.shopkeeper_id
   AND deposit_logs.expanses_type = accountings.expanses_type
   AND accountings.created_at = (
       SELECT MAX(a2.created_at)
       FROM accountings a2
       WHERE a2.shopkeeper_id = deposit_logs.shopkeeper_id
         AND a2.expanses_type = deposit_logs.expanses_type
         AND a2.created_at <= deposit_logs.created_at
   )
WHERE deposit_logs.shopkeeper_id = ?
ORDER BY deposit_logs.created_at DESC;

';


    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $shopkeeper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];

    while ($row = $result->fetch_assoc()) {
        if (!empty($row['shopkeeper_image'])) {
            $row['photo_url'] = "https://aqsasystem.com/storage/" . $row['shopkeeper_image'];
        } else {
            $row['photo_url'] = null;
        }

        $items[] = $row;
    }

    if (!empty($items)) {
        echo json_encode([
            "success" => true,
            "message" => $items
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "🚨 اطلاعات پرداخت ها یافت نشد",
        ]);
    }
}



# -------------- For Account Page ---------------

function Acountdetails($conn, $input) {
    $shopkeeper_id = $input['shopkeeper_id']; 

    $sql = "SELECT 
        fullname,
        shopkeeper_image, 
        contract_start,
        contract_end,
        contract_duration,
        shop_activity,
        contract_number,
        warranty_document
    FROM shopkeepers
    WHERE id = ?"; 

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "SQL Prepare Error: " . $conn->error
        ]);
        return;
    }

    $stmt->bind_param('i', $shopkeeper_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $photoPath = $row['shopkeeper_image'];
        $contractPhoto = $row['warranty_document'];
        $row['photo_url'] = "https://aqsasystem.com/storage/" . $photoPath;
        $row['contractPhoto']="https://aqsasystem.com/storage/" .$contractPhoto;

        echo json_encode([
            "success" => true,
            "data" => $row
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "data" => "🚨 اطلاعات یافت نشد!"
        ]);
    }
}

# ------------- For Advertisment Page -----------

function Advertisment($conn, $input) {
    $type = isset($input['type']) ? $input['type'] : null;

    $result = [
        'shops' => [],
        'booths' => [],
        'lands' => [],
        'homes' => []
    ];

    $base_url = "https://aqsasystem.com/storage/";

    // =========================== Shops ============================
    
    if (!$type || $type === 'دوکان') {
        $sql = $sql = "SELECT 
    advertisments.property,
    advertisments.for,
    advertisments.address,
    advertisments.price,
    advertisments.currency,
    advertisments.image,
    advertisments.created_at AS adver_create_at,
    shops.created_at,
    shops.number,
    shops.floor,
    shops.size,
    markets.name AS market_name,
    markets.location
FROM shops
LEFT JOIN advertisments ON advertisments.shop_id = shops.id
JOIN markets ON shops.market_id = markets.id
WHERE shops.shopkeeper_id IS NULL 
  AND shops.customer_id IS NULL;
";


        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $row['type'] = 'دوکان';
            $row['image_urls'] = parseImageUrls($row['image'], $base_url);
            $result["shops"][] = $row;
        }
    }

    // =========================== Booths ============================
    if (!$type || $type === 'غرفه') {
        $sql = $sql = "SELECT 
    advertisments.property,
    advertisments.for,
    advertisments.address,
    advertisments.price,
    advertisments.currency,
    advertisments.image,
     advertisments.created_at AS adver_create_at,
    booths.created_at,
    booths.number,
    booths.floor,
    booths.size,
    markets.name AS market_name,
    markets.location
FROM booths
LEFT JOIN advertisments ON advertisments.booth_id = booths.id
JOIN markets ON booths.market_id = markets.id
WHERE booths.shopkeeper_id IS NULL;
";


        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $row['type'] = 'غرفه';
            $row['image_urls'] = parseImageUrls($row['image'], $base_url);
            $result["booths"][] = $row;
        }
    }

    // =========================== Lands ============================
    if (!$type || $type === 'زمین') {
        $sql = "SELECT 
            property, `for`, address, width, hight, price, currency, image, created_at
        FROM advertisments 
        WHERE property = 'زمین'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $row['type'] = 'زمین';
            $row['image_urls'] = parseImageUrls($row['image'], $base_url);
            $result["lands"][] = $row;
        }
    }

    // =========================== Homes ============================
    if (!$type || $type === 'خانه') {
        $sql = "SELECT 
            property, `for`, address, width, hight, price, currency, image, created_at
        FROM advertisments 
        WHERE property = 'خانه'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $row['type'] = 'خانه';
            $row['image_urls'] = parseImageUrls($row['image'], $base_url);
            $result["homes"][] = $row;
        }
    }

    echo json_encode([
        "success" => true,
        "data" => $result
    ]);
}

# ------------- For Shop Details Page -----------

function ShopDetails($conn, $input) {
    $shopkeeper_id = $input['shopkeeper_id'];
    
    $sql = "SELECT
      shops.number,
      shops.floor,
      shops.size,
      shops.type,
      shops.price,
      shops.metar_serial,
      markets.name,
      markets.location
      FROM shops
      JOIN markets ON shops.market_id = markets.id 
      WHERE shops.shopkeeper_id = ?";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $shopkeeper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $shops = [];
    while ($row = $result->fetch_assoc()) {
        $row['type_lable'] = 'دوکان';
        $shops[] = $row;
    }

    $sql2 = "SELECT
      booths.number,
      booths.floor,
      booths.size,
      booths.type,
      booths.price,
      booths.metar_serial,
      markets.name,
      markets.location
      FROM booths
      JOIN markets ON booths.market_id = markets.id 
      WHERE booths.shopkeeper_id = ?";
    $stmt2 = $conn->prepare($sql2); 
    $stmt2->bind_param("i", $shopkeeper_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $booths = [];
    while ($row2 = $result2->fetch_assoc()) {
        $row2['type_lable'] = "غرفه";
        $booths[] = $row2;
    }

    if (count($shops) > 0 || count($booths) > 0) {
        echo json_encode([
            "success" => true,
            "shops" => $shops,
            "booths" => $booths
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "هیچ دوکان یا غرفه‌ای یافت نشد 😢"
        ]);
    }
}


# ------------ For Notification Page ------------

function Notifications($conn, $input) {
    $shopkeeper_id = $input['shopkeeper_id'];

    $sql = "SELECT * FROM accountings WHERE shopkeeper_id = ? AND cleared = 0 ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $shopkeeper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        $type = $row['expanses_type'];
        $notification = ['type' => 'ناشناخته', 'data' => []];

        switch ($type) {
            case "پول برق":
                $notification['type'] = 'پول برق';
                $notification['data'] = [
                    "meter_no" => $row["meter_serial"],
                    "prev_reading" => (int)$row["past_degree"],
                    "current_reading" => (int)$row["current_degree"],
                    "amount" => (float)$row["price"],
                    "currency" => $row["currency"],
                    "created_at" => $row["created_at"]
                ];
                break;

            case "پول آب":
                $notification['type'] = 'پول آب';
                $notification['data'] = [
                   "created_at" => $row["created_at"],
                    "amount" => (float)$row["price"],
                    "currency" => $row["currency"]
                ];
                break;

            case "کرایه":
                $notification['type'] = 'کرایه';
                $notification['data'] = [
                    "created_at" => $row["created_at"],
                    "amount" => (float)$row["price"],
                    "currency" => $row["currency"],
                    "remained" => $row['remained'],
                ];
                break;

            case "مالیه":
                $notification['type'] = 'مالیه';
                $notification['data'] = [
                    "amount" => (float)$row["price"],
                    "currency" => $row["currency"],
                    "created_at" => $row["created_at"]
                ];
                break;

            case "صفایی":
                $notification['type'] = 'صفایی';
                $notification['data'] = [
                    "amount" => (float)$row["price"],
                    "currency" => $row["currency"],
                    "created_at" => $row["created_at"]
                ];
                break;

            default:
                $notification['type'] = 'ناشناخته';
                $notification['data'] = [
                    "message" => "نوع ناشناخته: $type",
                    "created_at" => $row["created_at"]
                ];
        }

        $notifications[] = $notification;
    }

    echo json_encode($notifications, JSON_UNESCAPED_UNICODE);
    $conn->close();
}

# ------------ For Extra Methods Page -----------

function parseImageUrls($imageJson, $base_url) {
    if (empty($imageJson)) return [];

    $clean = stripslashes($imageJson);
    $decoded = json_decode($clean, true);

    if (!is_array($decoded)) return [];

    $imageUrls = [];

    // اطمینان از اینکه base_url با / تمام می‌شود
    if (substr($base_url, -1) !== '/') {
        $base_url .= '/';
    }

    foreach ($decoded as $imgPath) {
        // تبدیل بک‌اسلش به اسلش
        $imgPath = str_replace('\\', '/', $imgPath);

        // اطمینان از وجود یک اسلش بین base_url و imgPath
        $imgPath = ltrim($imgPath, '/');

        // بررسی و افزودن اسلش بین پوشه ها اگر لازم بود
        // به فرض اگر مسیر "uploadsproperty_image" است باید به "uploads/property_image" تبدیل شود

        // برای این کار، اگر بین uploads و property_image اسلش نیست، دستی اضافه می‌کنیم:
        if (strpos($imgPath, 'uploadsproperty_image') !== false) {
            $imgPath = str_replace('uploadsproperty_image', 'uploads/property_image', $imgPath);
        }

        $imageUrls[] = $base_url . $imgPath;
    }

    return $imageUrls;
}















?>