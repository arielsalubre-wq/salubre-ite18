<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php
include '../inc/dashHeader.php';
require_once '../config.php';
$query = "SELECT * FROM Kitchen WHERE time_ended IS NULL";
$result = mysqli_query($link, $query);
?>

<style>
.wrapper{ 
    width: 1300px; 
    padding-left: 200px; 
    padding-top: 20px;  
    background:#f5f5f5;
    min-height:100vh;
}

/* CONTAINER */
.wrapper .container-fluid{
    background:#fff;
    border:1px solid #eee;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

/* TITLE */
h2{
    color:#ff3758;
    font-weight:bold;
}

/* TABLE */
.table{
    border:1px solid #ddd; /* OUTER BORDER */
    border-collapse:collapse;
}

/* HEADER */
table thead th{
    background:#ff3758;
    color:#fff;
    text-align:center;
    border:1px solid #fff;  /* header separation lines */
}

/* BODY CELLS (VISIBLE LINES) */
table tbody td{
    background:#fff;
    color:#111;
    border:1px solid #ddd;  /* MAIN GRID LINES */
    text-align:center;
}

/* ROW HOVER (optional but nice for POS) */
table tbody tr:hover{
    background:#f9f9f9;
}

/* BUTTON */
.btn-warning{
    background:#ff3758;
    border:none;
    color:#fff;
}

.btn-warning:hover{
    background:#e6324e;
}

.btn-danger:hover{
    background:#28a745 !important;
    border:none;
    color:#fff;
}

.no-records-row td{
    color:#28a745;
    font-weight:bold;
    text-align:center;
}
</style>

<link href="../css/pos.css" rel="stylesheet" />
<meta http-equiv="refresh" content="5">

<div class="wrapper">

    <div class="container-fluid pt-5 pl-600 mt-5">

        <div class="">

            <div class="col" style="text-align: left; display:flex; justify-content:space-between;">

                <h2>Kitchen Orders</h2>

                <a href="../posBackend/kitchenBackend/undo.php?UndoUnshow=true" class="btn btn-warning mb-2">
                    Undo
                </a>

            </div>

        </div>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Kitchen ID</th>
                    <th>Table ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Time Submitted</th>
                    <th>Time Ended</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $kitchen_id = $row['kitchen_id'];
                        $table_id = $row['table_id'];
                        $item_id = $row['item_id'];
                        $quantity = $row['quantity'];
                        $time_submitted = $row['time_submitted'];
                        $time_ended = $row['time_ended'];

                        // Get item name from Menu table
                        $itemQuery = "SELECT item_name FROM Menu WHERE item_id = '$item_id'";
                        $itemResult = mysqli_query($link, $itemQuery);
                        $itemRow = mysqli_fetch_assoc($itemResult);
                        $item_name = $itemRow['item_name']??"Deleted";

                        echo '<tr>';
                        echo '<td>' . $kitchen_id . '</td>';
                        echo '<td>' . $table_id . '</td>';
                        echo '<td>' . $item_name . '</td>';
                        echo '<td>' . $quantity . '</td>';
                        echo '<td>' . $time_submitted . '</td>';
                        echo '<td>' . ($time_ended ?: 'Not Ended') . '</td>';
                        echo '<td>';
                        if (!$time_ended) {
                            echo '<a href="../posBackend/kitchenBackend/kitchen-panel-back.php?action=set_time_ended&kitchen_id=' . $kitchen_id . '" class="btn btn-danger">Done</a>';
                        }
                        
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr class="no-records-row">
        <td colspan="7">No records in the Kitchen table.</td>
      </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


