<?php 
//add found item
session_start();
include 'header.php';

// Check if user is logged in and is a recorder
if (!isset($_SESSION['user_id']) || !$_SESSION['is_recorder']) {
    header("Location: login.php");
    exit();
}

require_once '../db/db_connect.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Validate and sanitize input
//     $item_type = trim(filter_var($_POST['type'], FILTER_SANITIZE_STRING));
//     $brand = trim(filter_var($_POST['brand'], FILTER_SANITIZE_STRING));
//     $color = trim(filter_var($_POST['color'], FILTER_SANITIZE_STRING));
//     $additional_info = trim(filter_var($_POST['addInfo'], FILTER_SANITIZE_STRING));
//     $found_time = $_POST['date'];
//     $locations = $_POST['locations'] ?? [];

//     $errors = [];

//     // Validate required fields -
//     if (empty($item_type)) $errors[] = "Item type is required";
//     if (empty($brand)) $errors[] = "Brand is required";
//     if (empty($color)) $errors[] = "Color is required";
//     if (empty($additional_info)) $errors[] = "Additional information is required";
//     if (empty($found_time)) $errors[] = "Found time is required";
//     if (empty($locations)) $errors[] = "At least one location must be selected";

//     if (empty($errors)) {
//         try {
//             $pdo->beginTransaction();
            
//             // Insert into found_items
//             $stmt = $pdo->prepare("INSERT INTO found_items (item_type, brand, color, additional_info, found_time, recorder_id) 
//                                 VALUES (?, ?, ?, ?, ?, ?)");
//             $stmt->execute([$item_type, $brand, $color, $additional_info, $found_time, $_SESSION['user_id']]);
            
//             $item_id = $pdo->lastInsertId();
            
//             //inserts locs
//             if (!empty($locations)) {
//                 $stmt = $pdo->prepare("INSERT INTO item_locations (item_id, item_type, location) VALUES (?, 'found', ?)");
//                 foreach ($locations as $location) {
//                     $stmt->execute([$item_id, $location]);
//                 }
//             }
            
//             // if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
//             //     $image = $_FILES['image'];
//             //     $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                
//             //     if (in_array($image['type'], $allowed_types)) {
//             //         // CLOUDINARY LOGIC SOON
//             //     }
//             // }
            
//             $pdo->commit();
//             $_SESSION['success'] = "Item successfully recorded!";
//             header("Location: dashboard.php");
//             exit();
            
//         } catch (PDOException $e) {
//             $pdo->rollBack();
//             $error = "Database error: " . $e->getMessage();
//         }
//     } else {
//         $error = implode("<br>", $errors);
//     }
// }

// Fetch locations from database
$stmt = $pdo->query("SELECT * FROM locations ORDER BY category, name");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group locations by category
$grouped_locations = [];
foreach ($locations as $location) {
    $grouped_locations[$location['category']][] = $location;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Finder - Recorder Item Info</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../css/info.css">
</head>

<body>
    <div class="container">
        <section class="question">
            <h1>Record Found Item Information:</h1>
        </section>
        <div class="item_form_container">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success"><?php echo $_SESSION['success']; ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form id="infoForm" action="foundFormHandler.php" method="post" enctype="multipart/form-data">
                <div class="page page-1 index active">
                    <h2>What is the item you found?</h2>
                    <div class="form_group">
                        <input type="text" name="type" 
                               placeholder="Item Type (e.g., Phone, Wallet, Keys)" 
                               value="<?php echo isset($_POST['type']) ? htmlspecialchars($_POST['type']) : ''; ?>" 
                               required>
                    </div>
                    <div class="form_group">
                        <input type="text" name="brand" 
                               placeholder="Brand (e.g., Apple, Nike, Samsung)" 
                               value="<?php echo isset($_POST['brand']) ? htmlspecialchars($_POST['brand']) : ''; ?>" 
                               required>
                    </div>
                    <div class="form_group">
                        <input type="text" name="color" 
                               placeholder="Color (e.g., Black, Red, Blue)" 
                               value="<?php echo isset($_POST['color']) ? htmlspecialchars($_POST['color']) : ''; ?>" 
                               required>
                    </div>
                    <div class="form_group">
                        <input type="text" name="addInfo" 
                               placeholder="Additional Information (e.g., Model, Distinguishing Features)" 
                               value="<?php echo isset($_POST['addInfo']) ? htmlspecialchars($_POST['addInfo']) : ''; ?>" 
                               required>
                    </div>
                    <button type="button" class="next-btn">Continue</button>
                </div>

                <div class="page page-2">
                    <h2>When was the item found?</h2>
                    <h3>*Please provide accurate time if possible*</h3>
                    <div class="form_group">
                        <input type="datetime-local" 
                               name="date" 
                               value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>" 
                               required>
                    </div>
                    <div class="form-buttons">
                        <button type="button" class="prev-btn">Go Back</button>
                        <button type="button" class="next-btn">Continue</button>
                    </div>
                </div>

                <div class="page page-3">
                    <h2>Upload Item Image</h2>
                    <h3>*Recommended for better identification*</h3>
                    <img id="upload_image" src="./../assets/placeholderImg.svg" alt="Preview of the found item">
                    <label id="item_img" for="input-file">Upload Image</label>
                    <div class="form_group">
                        <input id="input-file" 
                               type="file" 
                               name="image" 
                               accept="image/jpeg,image/png,image/jpg">
                    </div>
                    <div class="form-buttons">
                        <button type="button" class="prev-btn">Go Back</button>
                        <button type="button" class="next-btn">Continue</button>
                    </div>
                </div>

                <div class="page page-4">
                <h2>Where did you find the item?</h2>
                <h3>*Can select multiple locations*</h3>
                
                <input type="text" 
                    class="location-search" 
                    placeholder="Search locations..." 
                    id="locationSearch">
                
                <div class="location-section">
                    <div class="locations-main">
                        <div class="locations-container">
                            <?php foreach ($grouped_locations as $category => $locs): ?>
                                <div class="location-group">
                                    <h3><?php echo htmlspecialchars($category); ?></h3>
                                    <div class="location-checkboxes">
                                        <?php foreach ($locs as $location): ?>
                                            <div class="location-checkbox">
                                                <input type="checkbox" 
                                                    name="locations[]" 
                                                    id="loc<?php echo $location['location_id']; ?>" 
                                                    value="<?php echo htmlspecialchars($location['name']); ?>">
                                                <label for="loc<?php echo $location['location_id']; ?>">
                                                    <?php echo htmlspecialchars($location['name']); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="locations-selected">
                        <h3>Selected Locations (<span id="selectedCount">0</span>)</h3>
                        <div class="selected-list" id="selectedList"></div>
                    </div>
                </div>
                <div class="form-buttons">                                    
                    <button type="button" class="prev-btn">Go Back</button>
                    <button type="submit" class="submit-btn" disabled>Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <?php include 'background-under.php'; ?>

    <script src="../script.js"></script>
    <script src="../jquery-3.6.1.min.js"></script>
</body>
</html>