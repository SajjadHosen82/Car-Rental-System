<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "includes/db_connect.php";
require_once "includes/header.php";

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: cars.php");
    exit;
}

$car_id = (int)$_GET['id'];

$car_query = mysqli_query($conn, "SELECT * FROM cars WHERE id = $car_id");
$car = mysqli_fetch_assoc($car_query);

if (!$car) {
    echo "<div class='alert alert-danger'>Car not found</div>";
    require_once "includes/footer.php";
    exit;
}

/* ================= FORM SUBMIT ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pickup_location =
        $_POST['pickup_road'] . ', ' .
        $_POST['pickup_area'] . ', ' .
        $_POST['pickup_thana'] . ', ' .
        $_POST['pickup_district'];

    $destination =
        $_POST['dest_road'] . ', ' .
        $_POST['dest_area'] . ', ' .
        $_POST['dest_thana'] . ', ' .
        $_POST['dest_district'];

    $start_date  = $_POST['start_date'];
    $end_date    = $_POST['end_date'];
    $pickup_time = $_POST['pickup_time'];
    $drive_option = $_POST['drive_option'];
    $phone = $_POST['phone'];
    $note  = $_POST['note'];

    $days = (strtotime($end_date) - strtotime($start_date)) / (60*60*24) + 1;
    if ($days < 1) $days = 1;

    $base_price = $days * $car['price_per_day'];
    $driver_cost = ($drive_option === 'With Driver') ? ($days * 4000) : 0;
    $total_price = $base_price + $driver_cost;

    mysqli_query($conn, "
        INSERT INTO bookings
        (user_id, car_id, pickup_location, destination, start_date, end_date,
         pickup_time, drive_option, phone, note, total_price, status)
        VALUES
        ('$user_id','$car_id','$pickup_location','$destination',
         '$start_date','$end_date','$pickup_time','$drive_option',
         '$phone','$note','$total_price','Pending')
    ");

    header("Location: dashboard.php");
    exit;
}
?>

<style>
.autocomplete-list{
  position:absolute;
  background:#fff;
  border:1px solid #ddd;
  width:100%;
  max-height:180px;
  overflow-y:auto;
  z-index:9999;
}
.autocomplete-item{
  padding:8px 10px;
  cursor:pointer;
}
.autocomplete-item:hover{
  background:#f1f1f1;
}
</style>

<div class="container my-5">
  <div class="card shadow-lg p-4">

    <h3 class="mb-4">Booking: <?= htmlspecialchars($car['name']); ?></h3>

    <form method="post">

      <h5>Pickup Location</h5>
      <div class="row">
        <div class="col-md-6 mb-3 position-relative">
          <input type="text" id="pickup_district" name="pickup_district" class="form-control" placeholder="District" autocomplete="off" required>
          <div id="pickup_district_list" class="autocomplete-list"></div>
        </div>
        <div class="col-md-6 mb-3 position-relative">
          <input type="text" id="pickup_thana" name="pickup_thana" class="form-control" placeholder="Thana / Upazila" autocomplete="off" required>
          <div id="pickup_thana_list" class="autocomplete-list"></div>
        </div>
        <div class="col-md-6 mb-3">
          <input type="text" name="pickup_area" class="form-control" placeholder="Village / Area" required>
        </div>
        <div class="col-md-6 mb-3">
          <input type="text" name="pickup_road" class="form-control" placeholder="Road / House No" required>
        </div>
      </div>

      <h5 class="mt-4">Destination</h5>
      <div class="row">
        <div class="col-md-6 mb-3 position-relative">
          <input type="text" id="dest_district" name="dest_district" class="form-control" placeholder="District" autocomplete="off" required>
          <div id="dest_district_list" class="autocomplete-list"></div>
        </div>
        <div class="col-md-6 mb-3 position-relative">
          <input type="text" id="dest_thana" name="dest_thana" class="form-control" placeholder="Thana / Upazila" autocomplete="off" required>
          <div id="dest_thana_list" class="autocomplete-list"></div>
        </div>
        <div class="col-md-6 mb-3">
          <input type="text" name="dest_area" class="form-control" placeholder="Village / Area" required>
        </div>
        <div class="col-md-6 mb-3">
          <input type="text" name="dest_road" class="form-control" placeholder="Road / House No" required>
        </div>
      </div>

      <h5 class="mt-4">Schedule</h5>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label>Start Date</label>
          <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
          <label>End Date</label>
          <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
          <label>Pickup Time</label>
          <input type="time" name="pickup_time" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Driving Option</label>
          <select name="drive_option" id="drive_option" class="form-control">
            <option value="Self Drive">Self Drive</option>
            <option value="With Driver">With Driver</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label>Phone Number</label>
          <input type="text" name="phone" class="form-control" placeholder="+880" required>
        </div>
      </div>

      <div class="mb-3">
        <label>Special Note (optional)</label>
        <textarea name="note" class="form-control" rows="3"></textarea>
      </div>

      <div class="total-amount-box my-4">
       <div class="total-title">Total Amount</div>
       <div class="total-price" id="totalAmount">৳ 0</div>
     </div>


      <button class="btn btn-warning btn-lg w-100 mt-3">
        ✅ Confirm Booking
      </button>

    </form>
  </div>
</div>

<script>
const districts=[
 "Dhaka","Chattogram","Rajshahi","Khulna","Barishal",
 "Sylhet","Rangpur","Mymensingh","Cumilla","Gazipur",
 "Narayanganj","Noakhali","Bogura","Jessore","Pabna"
];
const thanas=[
 "Dhanmondi","Gulshan","Mirpur","Uttara","Mohammadpur",
 "Banani","Tejgaon","Motijheel","Kotwali","Sadar"
];

function setupAutocomplete(inputId,listId,data){
  const input=document.getElementById(inputId);
  const list=document.getElementById(listId);

  input.addEventListener("input",()=>{
    list.innerHTML="";
    const val=input.value.toLowerCase();
    if(val.length<2) return;

    data.filter(i=>i.toLowerCase().startsWith(val)).forEach(i=>{
      const d=document.createElement("div");
      d.className="autocomplete-item";
      d.innerText=i;
      d.onclick=()=>{input.value=i;list.innerHTML="";};
      list.appendChild(d);
    });
  });

  document.addEventListener("click",e=>{
    if(!input.contains(e.target)) list.innerHTML="";
  });
}

setupAutocomplete("pickup_district","pickup_district_list",districts);
setupAutocomplete("pickup_thana","pickup_thana_list",thanas);
setupAutocomplete("dest_district","dest_district_list",districts);
setupAutocomplete("dest_thana","dest_thana_list",thanas);

/* ===== TOTAL PRICE ===== */
const pricePerDay = <?= (int)$car['price_per_day']; ?>;
const startDate=document.querySelector('[name="start_date"]');
const endDate=document.querySelector('[name="end_date"]');
const driveOption=document.getElementById("drive_option");
const totalBox=document.getElementById("totalAmount");

function daysCount(){
  if(!startDate.value||!endDate.value) return 1;
  const s=new Date(startDate.value);
  const e=new Date(endDate.value);
  const d=(e-s)/(1000*60*60*24)+1;
  return d>0?d:1;
}

function updateTotal(){
  const days=daysCount();
  let total=days*pricePerDay;
  if(driveOption.value==="With Driver") total+=days*4000;
  totalBox.innerText="৳ "+total.toLocaleString("en-BD");
}

[startDate,endDate,driveOption].forEach(el=>el.addEventListener("change",updateTotal));
updateTotal(); // page load fix
</script>

<?php require_once "includes/footer.php"; ?>
