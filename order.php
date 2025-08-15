<?php include('partials-front/menu.php'); ?>
<?php include('partials-front/rightMenu.php'); ?>

<?php
// Check whether food_id is set
if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];

    // Get food details
    $sql = "SELECT * FROM tbl_food WHERE id=$food_id";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);

    if ($count == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name'];
    } else {
        header('location:' . SITEURL);
    }
} else {
    header('location:' . SITEURL);
}
?>

<section class="food-search">
    <div class="container">
        <h2 class="text-center text-white">Fill out this form to confirm your order.</h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Selected Food</legend>
                <div class="food-menu-img">
                    <?php
                    if ($image_name == "") {
                        echo "<div class='error'>Image not available.</div>";
                    } else {
                    ?>
                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Food Image" class="img-responsive img-curve">
                    <?php
                    }
                    ?>
                </div>

                <div class="food-menu-desc">
                    <h3><?php echo $title; ?></h3>
                    <input type="hidden" name="food" value="<?php echo $title; ?>">

                    <p class="food-price">$<?php echo $price; ?></p>
                    <input type="hidden" name="price" value="<?php echo $price; ?>">

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1" required>
                </div>
            </fieldset>

            <!-- Delivery Details -->
            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Full Name</div>
                <input type="text" name="full-name" placeholder="E.g. John Doe" class="input-responsive" required>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="E.g. 9876543210" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" placeholder="E.g. example@email.com" class="input-responsive" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                <!-- Error message directly below input fields -->
                <p id="formError" style="color:red; font-weight:bold; display:none; margin-top:5px;"></p>
            </fieldset>

            <!-- Payment Section -->
            <fieldset>
                <legend>Payment</legend>
                <div class="order-label">Scan QR to Pay</div>
                <div style="text-align:center; margin-bottom:15px;">
                    <img src="<?php echo SITEURL; ?>images/qrpay.jpg" alt="QR Code" style="max-width:200px; border:1px solid #ccc; padding:10px;">
                    <p>Scan this QR code to complete payment.</p>
                </div>
                <button type="button" id="paidBtn" class="btn btn-success">I Have Paid</button>
                <p id="countdownText" style="display:none; margin-top:10px; font-weight:bold;"></p>
            </fieldset>

            <!-- Confirm Order button -->
            <input type="submit" name="submit" value="Confirm Order" id="confirmBtn" class="btn btn-primary" disabled>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $food = $_POST['food'];
            $price = $_POST['price'];
            $qty = $_POST['qty'];
            $total = $price * $qty;
            $order_date = date("Y-m-d h:i:sa");
            $status = "Ordered";
            $customer_name = $_POST['full-name'];
            $customer_contact = $_POST['contact'];
            $customer_email = $_POST['email'];
            $customer_address = $_POST['address'];

            $sql2 = "INSERT INTO tbl_order SET 
                        food = '$food',
                        price = $price,
                        qty = $qty,
                        total = $total,
                        order_date = '$order_date',
                        status = '$status',
                        customer_name = '$customer_name',
                        customer_contact = '$customer_contact',
                        customer_email = '$customer_email',
                        customer_address = '$customer_address'
                    ";
            $res2 = mysqli_query($conn, $sql2);

            if ($res2 == true) {
                $_SESSION['order'] = "<div class='success text-center'>Food ordered successfully.</div>";
                header('location:' . SITEURL);
            } else {
                $_SESSION['order'] = "<div class='error text-center'>Failed to order food.</div>";
                header('location:' . SITEURL);
            }
        }
        ?>
    </div>
</section>

<script>
let paid = false;

function validateInfo() {
    let name = document.querySelector('input[name="full-name"]').value.trim();
    let phone = document.querySelector('input[name="contact"]').value.trim();
    let email = document.querySelector('input[name="email"]').value.trim();
    let address = document.querySelector('textarea[name="address"]').value.trim();

    if (!name || !phone || !email || !address) {
        document.getElementById('formError').style.display = 'block';
        document.getElementById('formError').textContent = "Please fill in all delivery details before making a payment.";
        return false;
    }
    document.getElementById('formError').style.display = 'none';
    return true;
}

document.getElementById('paidBtn').addEventListener('click', function() {
    if (!validateInfo()) {
        return;
    }

    let countdown = 3;
    let countdownText = document.getElementById('countdownText');
    countdownText.style.display = 'block';
    countdownText.textContent = "Verifying payment... " + countdown + "s";

    let timer = setInterval(function() {
        countdown--;
        countdownText.textContent = "Verifying payment... " + countdown + "s";
        if (countdown <= 0) {
            clearInterval(timer);
            countdownText.textContent = "Payment successful! You can now confirm your order.";
            document.getElementById('confirmBtn').disabled = false;
            paid = true;
        }
    }, 1000);
});

document.querySelector('form.order').addEventListener('submit', function(e) {
    if (!validateInfo()) {
        e.preventDefault();
        return;
    }
    if (!paid) {
        e.preventDefault();
        document.getElementById('formError').style.display = 'block';
        document.getElementById('formError').textContent = "You have not completed the payment. Please scan the QR code and confirm payment before placing the order.";
        return;
    }
});
</script>
