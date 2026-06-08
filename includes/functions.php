<?php
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) session_start();

// include DB
require_once __DIR__ . '/db_connect.php';

/* -------------------------
   Auth / User functions
   ------------------------- */

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
}

function registerUser($conn, $name, $email, $password, $role = 'user') {
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        return "This email is already registered.";
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $hash, $role);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();
    if ($ok) return true;
    return "Registration failed: " . $err;
}

function loginUser($conn, $email, $password) {
    $email = mysqli_real_escape_string($conn, $email);
    $stmt = $conn->prepare("SELECT id,name,password,role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return "Incorrect password.";
        }
    } else {
        $stmt->close();
        return "No account found with that email.";
    }
}

function logoutUser() {
    session_unset();
    session_destroy();
    // redirect handled by caller
}

/* -------------------------
   Car functions
   ------------------------- */

function addCar($conn, $name, $model, $price, $imageName = null) {
    $stmt = $conn->prepare("INSERT INTO cars (name,model,price_per_day,image) VALUES (?,?,?,?)");
    $stmt->bind_param("ssds", $name, $model, $price, $imageName);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();
    if ($ok) return true;
    return "Add car failed: " . $err;
}

function updateCar($conn, $id, $name, $model, $price, $imageName = null) {
    if ($imageName) {
        $stmt = $conn->prepare("UPDATE cars SET name=?, model=?, price_per_day=?, image=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $model, $price, $imageName, $id);
    } else {
        $stmt = $conn->prepare("UPDATE cars SET name=?, model=?, price_per_day=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $model, $price, $id);
    }
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();
    if ($ok) return true;
    return "Update car failed: " . $err;
}

function deleteCar($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM cars WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function getAllCars($conn) {
    $res = $conn->query("SELECT * FROM cars ORDER BY id DESC");
    return $res;
}

function getAvailableCars($conn) {
    $res = $conn->query("SELECT * FROM cars WHERE status='available' ORDER BY id DESC");
    return $res;
}

function getCarById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r;
}

/* -------------------------
   Booking functions
   ------------------------- */

function bookCar($conn, $user_id, $car_id, $pickup, $destination, $start, $end, $total) {
    $stmt = $conn->prepare("INSERT INTO bookings (user_id,car_id,pickup_location,destination,start_date,end_date,total_price) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("iissssd",$user_id,$car_id,$pickup,$destination,$start,$end,$total);
    $ok = $stmt->execute();
    $stmt->close();
    if ($ok) {
        // mark car as booked
        $stmt2 = $conn->prepare("UPDATE cars SET status='booked' WHERE id=?");
        $stmt2->bind_param("i", $car_id);
        $stmt2->execute();
        $stmt2->close();
        return true;
    }
    return false;
}

function getUserBookings($conn, $user_id) {
    $stmt = $conn->prepare("SELECT b.*, c.name AS car_name FROM bookings b JOIN cars c ON b.car_id=c.id WHERE b.user_id=? ORDER BY b.id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    return $res;
}

function getAllBookings($conn) {
    $res = $conn->query("SELECT b.*, u.name AS user_name, c.name AS car_name FROM bookings b JOIN users u ON b.user_id=u.id JOIN cars c ON b.car_id=c.id ORDER BY b.id DESC");
    return $res;
}

function changeBookingStatus($conn, $id, $status) {
    $stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

/* -------------------------
   User admin functions
   ------------------------- */

function getAllUsers($conn) {
    $res = $conn->query("SELECT id,name,email,role FROM users ORDER BY id DESC");
    return $res;
}
function deleteUserById($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

/* -------------------------
   Utility
   ------------------------- */

function searchCars($conn, $q) {
    $term = "%".$q."%";
    $stmt = $conn->prepare("SELECT * FROM cars WHERE name LIKE ? OR model LIKE ? ORDER BY id DESC");
    $stmt->bind_param("ss",$term,$term);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    return $res;
}
?>
