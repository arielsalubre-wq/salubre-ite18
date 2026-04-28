<?php
session_start(); // Ensure session is started
?>
<?php
require_once '../config.php';
include '../inc/dashHeader.php'; 

$bill_id = $_GET['bill_id'];
$table_id = $_GET['table_id'];

$currency = "₱"; // ✅ Philippine Peso

function createNewBillRecord($table_id) {
    global $link;
    
    $bill_time = date('Y-m-d H:i:s');
    
    $insert_query = "INSERT INTO Bills (table_id, bill_time) VALUES ('$table_id', '$bill_time')";
    if ($link->query($insert_query) === TRUE) {
        return $link->insert_id;
    } else {
        return false;
    }
}
?>
<style>/* =========================
   WRAPPER THEME
========================= */
.wrapper{  
    width: 1300px; 
    padding-left: 200px; 
    padding-top: 20px;  
    background:#f5f5f5;
    min-height:100vh;
}

/* CONTAINER */
.container-fluid .row > div{
    background:#fff;
    border:1px solid #eee;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

/* TITLE */
h3{
    color:#ff3758;
    font-weight:bold;
}

/* INPUT */
.form-control{
    border:1px solid #ddd;
}

/* SEARCH BUTTON */
.btn-dark{
    background:#ff3758;
    border:none;
    color:#fff;
}

.btn-dark:hover{
    background:#e6324e;
}

/* SHOW ALL BUTTON */
.btn-light{
    border:1px solid #ff3758;
    color:#ff3758;
    background:#fff;
}

.btn-light:hover{
    background:#ff3758;
    color:#fff;
}

/* ADD TO CART BUTTON */
.btn-primary{
    background:#ff3758 !important;
    border:none !important;
    color:#fff !important;
}

.btn-primary:hover{
    background:#e6324e !important;
}

/* DELETE BUTTON */
.btn-dark{
    background:#ff3758 !important;
    border:none !important;
    color:#fff !important;
}

/* SUCCESS (PAY BILL) */
.btn-success{
    background:#28a745 !important;
    border:none !important;
}

/* WARNING (NEW CUSTOMER) */
.btn-warning{
    background:#ffb300 !important;
    border:none !important;
    color:#000 !important;
}

/* TABLE BORDER FIX */
.table{
    border:1px solid #ddd;
    border-collapse: collapse;
    background:#fff;
}

/* HEADER */
.table thead th{
    background:#ff3758;
    color:#fff;
    border:1px solid #ff3758;
}

/* CELLS (THIS FIXES INVISIBLE LINES) */
.table tbody td{
    border:1px solid #e6e6e6;
    padding:8px;
    background:#fff;
}

/* ROW SEPARATION */
.table tbody tr{
    border-bottom:1px solid #e0e0e0;
}

/* ALERTS */
.alert-danger{
    background:#28a745 !important;   /* green */
    color:#fff !important;
    border:none !important;
}

.alert-success{
    background:#ff3758 !important;
    color:#fff !important;
    border:none !important;
}
#cart-section{
    max-width: 350px;   /* reduced width */
}



</style>
<!DOCTYPE html>
<html>
<head>
    <link href="../css/pos.css" rel="stylesheet" />
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-md-6 order-md-1 m-1" id="item-select-section ">
            <div class="container-fluid pt-4 pl-500 row" style=" margin-left: 10rem;width: 81% ;">

                <div class="mt-5 mb-2">
                    <h3 class="pull-left">Food & Drinks</h3>
                </div>

                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" required id="search" name="search" class="form-control" placeholder="Search Food & Drinks">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col" style="text-align: right;">
                                <a href="orderItem.php?bill_id=<?php echo $bill_id; ?>&table_id=<?php echo $table_id; ?>" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div style="max-height: 45rem;overflow-y: auto;">
                <?php
                require_once "../config.php";

                if (isset($_POST['search']) && !empty($_POST['search'])) {
                    $search = $_POST['search'];
                    $query = "SELECT * FROM Menu 
                              WHERE item_type LIKE '%$search%' 
                              OR item_category LIKE '%$search%' 
                              OR item_name LIKE '%$search%' 
                              OR item_id LIKE '%$search%' 
                              ORDER BY item_id;";
                } else {
                    $query = "SELECT * FROM Menu ORDER BY item_id;";
                }

                $result = mysqli_query($link, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo "<thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Add</th>
                            </tr>
                          </thead><tbody>";

                    while ($row = mysqli_fetch_array($result)) {

                        echo "<tr>";
                        echo "<td>{$row['item_id']}</td>";
                        echo "<td>{$row['item_name']}</td>";
                        echo "<td>{$row['item_category']}</td>";
                        echo "<td>{$currency} " . number_format($row['item_price'],2) . "</td>";

                        $payment_time_query = "SELECT payment_time FROM Bills WHERE bill_id = '$bill_id'";
                        $payment_time_result = mysqli_query($link, $payment_time_query);

                        $has_payment_time = false;
                        if ($payment_time_result && mysqli_num_rows($payment_time_result) > 0) {
                            $payment_time_row = mysqli_fetch_assoc($payment_time_result);
                            if (!empty($payment_time_row['payment_time'])) {
                                $has_payment_time = true;
                            }
                        }

                        if (!$has_payment_time) {
                            echo '<td>
                                <form method="get" action="addItem.php">
                                    <input type="hidden" name="table_id" value="' . $table_id . '">
                                    <input type="hidden" name="item_id" value="' . $row['item_id'] . '">
                                    <input type="hidden" name="bill_id" value="' . $bill_id . '">
                                    <input type="number" name="quantity" style="width:120px" placeholder="1 to 1000" required min="1" max="1000">
                                    <input type="hidden" name="addToCart" value="1">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </td>';
                        } else {
                            echo "<td>Bill Paid</td>";
                        }

                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo '<div class="alert alert-danger"><em>No menu items were found.</em></div>';
                }
                ?>
                </div>

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-4 order-md-2 m-1" id="cart-section">

            <div class="container-fluid pt-5 pl-600 pr-6 row mt-3" style="max-width: 200%; width:150%;">
                <div class="cart-section">
                    <h3>Cart</h3>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
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
                        $tax = 0.1;

                        if ($cart_result && mysqli_num_rows($cart_result) > 0) {
                            while ($cart_row = mysqli_fetch_assoc($cart_result)) {

                                $item_id = $cart_row['item_id'];
                                $item_name = $cart_row['item_name'];
                                $item_price = $cart_row['item_price'];
                                $quantity = $cart_row['quantity'];
                                $total = $item_price * $quantity;
                                $bill_item_id = $cart_row['bill_item_id'];

                                $cart_total += $total;

                                echo "<tr>";
                                echo "<td>$item_id</td>";
                                echo "<td>$item_name</td>";
                                echo "<td>{$currency} " . number_format($item_price,2) . "</td>";
                                echo "<td>$quantity</td>";
                                echo "<td>{$currency} " . number_format($total,2) . "</td>";
                                echo "<td>
                                        <a class='btn btn-dark' href='deleteItem.php?bill_id=$bill_id&table_id=$table_id&bill_item_id=$bill_item_id&item_id=$item_id'>
                                        Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No Items in Cart.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>

                    <hr>

                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Cart Total</strong></td>
                            <td><?php echo $currency . " " . number_format($cart_total,2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Cart Taxed</strong></td>
                            <td><?php echo $currency . " " . number_format($cart_total * $tax,2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Grand Total</strong></td>
                            <td><?php echo $currency . " " . number_format($cart_total + ($cart_total * $tax),2); ?></td>
                        </tr>
                    </table>

                    <?php
                    $payment_time_query = "SELECT payment_time FROM Bills WHERE bill_id = '$bill_id'";
                    $payment_time_result = mysqli_query($link, $payment_time_query);

                    $has_payment_time = false;
                    if ($payment_time_result && mysqli_num_rows($payment_time_result) > 0) {
                        $payment_time_row = mysqli_fetch_assoc($payment_time_result);
                        if (!empty($payment_time_row['payment_time'])) {
                            $has_payment_time = true;
                        }
                    }

                    if ($has_payment_time) {
                        echo '<div class="alert alert-success">Bill has already been paid.</div>';
                        echo '<a href="receipt.php?bill_id=' . $bill_id . '" class="btn btn-light">Print Receipt</a>';
                    } elseif (($cart_total + ($cart_total * $tax)) > 0) {
                        // ✅ PAY BILL BUTTON KEPT
                        echo '<br><a href="idValidity.php?bill_id=' . $bill_id . '" class="btn btn-success">Pay Bill</a>';
                    } else {
                        echo '<h3>Add Item To Cart to Proceed</h3>';
                    }
                    ?>

                </div>

                <?php
                // ✅ NEW CUSTOMER BUTTON KEPT
                echo '<form class="mt-3" action="newCustomer.php" method="get">';
                echo '<input type="hidden" name="table_id" value="' . $table_id . '">';
                echo '<button type="submit" name="new_customer" value="true" class="btn btn-warning">New Customer</button>';
                echo '</form>';
                ?>

            </div>
        </div>

    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>