<?php
session_start();
require_once '../config.php';
include '../inc/dashHeader.php'; 

$bill_id = $_GET['bill_id'];
$staff_id = $_GET['staff_id'];
$member_id = intval($_GET['member_id']);
$reservation_id = $_GET['reservation_id'];

function peso($amount) {
    return '₱ ' . number_format($amount, 2);
}
?>
<style>
body{
    background:#f5f5f5;
    color:#111;
}

/* CARD */
.card{
    background:#fff;
    border:1px solid #ddd;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    width: 100%;
}

/* HEADER */
.card-header{
    background:#fff;
    color:#ff3758;
    font-weight:bold;
    border-bottom:2px solid #ff3758;
}
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Bill (Cash Payment)</h3>
                </div>
                <div class="card-body">
                    <h5>Bill ID: <?php echo $bill_id; ?></h5>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>

<?php
$cart_query = "SELECT bi.*, m.item_name, m.item_price 
               FROM bill_items bi
               JOIN Menu m ON bi.item_id = m.item_id
               WHERE bi.bill_id = '$bill_id'";

$cart_result = mysqli_query($link, $cart_query);

$cart_total = 0;
$tax = 0.12; // 🇵🇭 12% VAT (Philippines standard)

if ($cart_result && mysqli_num_rows($cart_result) > 0) {
    while ($cart_row = mysqli_fetch_assoc($cart_result)) {

        $item_id = $cart_row['item_id'];
        $item_name = $cart_row['item_name'];
        $item_price = $cart_row['item_price'];
        $quantity = $cart_row['quantity'];

        $total = $item_price * $quantity;
        $cart_total += $total;

        echo '<tr>';
        echo '<td>' . $item_id . '</td>';
        echo '<td>' . $item_name . '</td>';
        echo '<td>' . peso($item_price) . '</td>';
        echo '<td>' . $quantity . '</td>';
        echo '<td>' . peso($total) . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">No Items in Cart.</td></tr>';
}
?>

                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="text-right">
                        <?php
                        $tax_amount = $cart_total * $tax;
                        $GRANDTOTAL = $cart_total + $tax_amount;

                        echo "<strong>Total:</strong> " . peso($cart_total) . "<br>";
                        echo "<strong>VAT (12%):</strong> " . peso($tax_amount) . "<br>";
                        echo "<strong>Grand Total:</strong> " . peso($GRANDTOTAL);
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- PAYMENT SECTION -->
<div id="cash-payment" class="container-fluid mt-5 pt-5 pl-5 pr-5 mb-5">
    <div class="row">

        <div class="col-md-6">
            <h1>Cash Payment</h1>

            <form action="" method="get">
                <div class="form-group">
                    <label>Payment Amount</label>
                    <input type="number" min="0" name="payment_amount" class="form-control" required>
                </div>

                <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
                <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">
                <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
                <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                <input type="hidden" name="GRANDTOTAL" value="<?php echo $GRANDTOTAL; ?>">

                <button type="submit" class="btn btn-dark mt-2">Pay</button>
            </form>
        </div>

        <div class="col-md-6">

<?php
function calculateChange($paymentAmount, $GrandTotal) {
    return $paymentAmount - $GrandTotal;
}

if (isset($_GET['payment_amount'])) {

    $payment_amount = floatval($_GET['payment_amount']);

    // Check if already paid
    $check = $link->query("SELECT payment_time FROM Bills WHERE bill_id = $bill_id");

    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();

        if ($row['payment_time'] !== null) {
            echo '<div class="alert alert-warning">Already paid.</div>';
            echo '<a href="posTable.php" class="btn btn-dark">Back</a>';
            echo '<a href="receipt.php?bill_id='.$bill_id.'" class="btn btn-light">Receipt</a>';
            exit;
        }
    }

    // Payment validation
    if ($payment_amount >= $GRANDTOTAL) {

        $change = calculateChange($payment_amount, $GRANDTOTAL);

        echo '<div class="alert alert-dark">';
        echo "Change: " . peso($change);
        echo '</div>';

        $time = date('Y-m-d H:i:s');

        $update = "UPDATE Bills SET 
            payment_method='Cash',
            payment_time='$time',
            staff_id=$staff_id,
            member_id=$member_id,
            reservation_id=$reservation_id
            WHERE bill_id=$bill_id";

        if ($link->query($update)) {

            // Add points
            $points = intval($GRANDTOTAL);
            $link->query("UPDATE Memberships SET points = points + $points WHERE member_id = $member_id");

            echo '<div class="alert alert-success">Bill Paid Successfully!</div>';
            echo '<a href="posTable.php" class="btn btn-dark">Back</a>';
            echo '<a href="receipt.php?bill_id='.$bill_id.'" class="btn btn-light">Print Receipt</a>';

        } else {
            echo "Error: " . $link->error;
        }

    } else {
        echo '<div class="alert alert-warning">Insufficient payment.</div>';
    }
}
?>

        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>