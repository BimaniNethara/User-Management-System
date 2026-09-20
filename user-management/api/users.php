<?php
header("Content-Type: application/json");
require_once "../config/database.php";

//View users
if ($_SERVER["REQUEST_METHOD"] === "GET") {

// Get a specific user for the Edit page
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

    if ($id > 0) {

        $stmt = $connection->prepare(
            "SELECT id, name, about_you, birthday,mobile_number, email, country
             FROM users
             WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(["message" => "User not found"]);
        }
        $stmt->close();
        exit;
    }

    // Get filter values
    $country = trim($_GET["country"] ?? "");
    $from = $_GET["from"] ?? "";
    $to = $_GET["to"] ?? "";

    // Build SQL query
    $sql = "SELECT id, name, about_you, birthday, mobile_number, email, country
            FROM users
            WHERE 1=1";

    $params = [];
    $types = "";

// Filter by country
    if ($country !== "") {
        $sql .= " AND country = ?";
        $params[] = $country;
        $types .= "s";
    }

// Filter birthday from
    if ($from !== "") {
        $sql .= " AND birthday >= ?";
        $params[] = $from;
        $types .= "s";
    }

// Filter birthday to
    if ($to !== "") {
        $sql .= " AND birthday <= ?";
        $params[] = $to;
        $types .= "s";
    }
//order by users in table ascending order
    $sql .= " ORDER BY id ASC";


    $stmt = $connection->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    //excecute and get the users
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
    $stmt->close();
    exit;
}

//Update user details

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

    if ($id <= 0) {
        echo json_encode([
            "message" => "Invalid user ID"
        ]);
        exit;
    }

    //get the updated data
    $data = json_decode(file_get_contents("php://input"), true);


    //read the JSON dat send from JS
    $name = trim($data["name"] ?? "");
    $about = trim($data["about"] ?? "");
    $birthday = $data["birthday"] ?? "";
    $mobile = trim($data["mobile"] ?? "");
    $email = trim($data["email"] ?? "");
    $country = trim($data["country"] ?? "");


    //Check empty fields
    if ($name === "" ||$about === "" ||$birthday === "" ||$mobile === "" ||$email === "" ||$country === "" ) {
        echo json_encode(["message" => "All fields are required" ]);
        exit;
    }

//email address validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["message" => "Invalid email address" ]);
        exit;
    }

//Mobile Number validation
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        echo json_encode([
            "message" => "Mobile number must contain 10 digits"
        ]);
        exit;
    }

//Update database
    $sql = "UPDATE users SET name = ?, about_you = ?,  birthday = ?, mobile_number = ?,email = ?,country = ?
            WHERE id = ?";

    $stmt = $connection->prepare($sql);

    $stmt->bind_param( "ssssssi",$name,$about, $birthday,$mobile,$email,$country,$id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "User updated successfully"]);
    } else {
        echo json_encode(["message" => "Failed to update user"]);
    }
    $stmt->close();
    exit;
}

//Add new user

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "message" => "Method not allowed"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"] ?? "");
$about = trim($data["about"] ?? "");
$birthday = $data["birthday"] ?? "";
$mobile = trim($data["mobile"] ?? "");
$email = trim($data["email"] ?? "");
$country = trim($data["country"] ?? "");

//validate new user
if ( $name === "" ||$about === "" ||$birthday === "" ||$mobile === "" || $email === "" ||$country === "") {
    echo json_encode(["message" => "All fields are required"]);
    exit;
}

//email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "Invalid email address" ]);
    exit;
}

//Mobile number validation
if (!preg_match("/^[0-9]{10}$/", $mobile)) {
    echo json_encode([
        "message" => "Mobile number must contain 10 digits"
    ]);
    exit;
}

//Insrt data into users table
$sql = "INSERT INTO users(name, about_you, birthday, mobile_number, email, country)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $connection->prepare($sql);

$stmt->bind_param("ssssss",$name,$about,$birthday,$mobile,$email,$country);

if ($stmt->execute()) {
    echo json_encode(["message" => "User added successfully"]);

} else {
    echo json_encode(["message" => "Failed to add user"]);
}

$stmt->close();
$connection->close();

?>