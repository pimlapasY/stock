<?php
// Include database connection
include 'connect.php';

// Check if reasons is set
if (isset($_POST['store'])) {
    // Get reasons value
    $store = $_POST['store'];
    $month = $_POST['month'];
    $payment = $_POST['payment'];
    $year = $_POST['year'];
    $pr_status = $_POST['prStatusSelected'];

    $currentDay = date('d');
    /* $currentDay = 19; */

    $currentMonth = date('m');
    $currentYear = date('Y');

    $previousMonth = date('m', strtotime('-1 month')); // Previous month
    $nextMonth = date('m', strtotime('+1 month')); // Next month
    $startDate = "$currentYear-$previousMonth-21";
    $endDate = "$currentYear-$currentMonth-20";
    $updateDate = "$currentYear-$nextMonth-20";

    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($currentDay == 20) {
            // Prepare the update statement
            $stmt = $pdo->prepare("UPDATE pr
                                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                                    SET pr.pr_date = :updateDate
                                    WHERE o.o_payment = 2 OR o.o_payment IS NULL");

            // Bind the update date parameter
            $stmt->bindParam(':updateDate', $updateDate);

            // Execute the update
            $stmt->execute();
        }

        $sql = "SELECT o.*, p.*, pr.*
                FROM pr
                LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code";

        // Adding conditions based on payment status
        if ($payment == 0) {
            if ($store == 'all') {
                $sql .= " WHERE (o.o_payment IS NOT NULL OR o.o_payment IS NULL)";
            } elseif ($store == 'samt') {
                $sql .= " WHERE o.o_reasons NOT LIKE '%sale,2%'";
            } elseif ($store == 'sakaba') {
                $sql .= " WHERE o.o_reasons LIKE '%sale,2%'";
            } else {
                $sql .= " WHERE (o.o_payment IS NOT NULL OR o.o_payment IS NULL)";
            }
        } elseif ($payment == 1) {
            if ($store == 'all') {
                $sql .= " WHERE (o.o_payment = 2 OR o.o_payment IS NULL)";
            } elseif ($store == 'samt') {
                $sql .= " WHERE o.o_reasons NOT LIKE '%sale,2%' AND (o.o_payment = 2 OR o.o_payment IS NULL)";
            } elseif ($store == 'sakaba') {
                $sql .= " WHERE o.o_reasons LIKE '%sale,2%' AND (o.o_payment = 2 OR o.o_payment IS NULL)";
            } else {
                $sql .= " WHERE (o.o_payment = 2 OR o.o_payment IS NULL)";
            }
        } elseif ($payment == 2) {
            if ($store == 'all') {
                $sql .= " WHERE o.o_payment = 1";
            } elseif ($store == 'samt') {
                $sql .= " WHERE o.o_reasons NOT LIKE '%sale,2%' AND o.o_payment = 1";
            } elseif ($store == 'sakaba') {
                $sql .= " WHERE o.o_reasons LIKE '%sale,2%' AND o.o_payment = 1";
            } else {
                $sql .= " WHERE o.o_payment = 1";
            }
        }

        // Adding conditions based on month
        if ($month != 'month') {
            $sql .= " AND MONTH(pr.pr_date) = :month";
        }

        // Adding conditions based on year
        if ($year != 'years') {
            $sql .= " AND YEAR(pr.pr_date) = :year";
        }

        // Adding conditions based on pr_status
        if ($pr_status == 1) {
            $sql .= " AND pr.pr_status = 1";
        } elseif ($pr_status == 2) {
            $sql .= " AND pr.pr_status = 2";
        } elseif ($pr_status == 99) {
            $sql .= " AND pr.pr_status IS NULL";
        } else {
            $sql .= " AND (pr.pr_status = 1 OR pr.pr_status = 2 OR pr.pr_status IS NULL)";
        }

        // Adding the order by clause
        $sql .= " ORDER BY pr.pr_code DESC, pr.pr_date_update, pr.pr_date_add DESC";

        $stmt = $pdo->prepare($sql);

        // Binding parameters
        if ($month != 'month') {
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        }

        if ($year != 'years') {
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        }

        // Executing the statement
        $stmt->execute();

        // Fetching all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are no products
        if (empty($products)) {
            echo '<tr><td class="text-center" colspan="15"><i class="fa-regular fa-folder-open"></i> No products available</td></tr>'; // Adjust colspan based on the number of columns
        } else {
            // Output the table rows
            foreach ($products as $index => $product) {
                $data_reasons = explode(",", $product['o_reasons']);

                if ($product['pr_exchange'] == null) {
                    echo '<tr>';
                } else {
                    echo '<tr class="table-secondary" id="exchangeID' . $product['pr_code'] . '" hidden>';
                }

                if ($product['pr_status'] == null && $product['pr_exchange'] == null) {
                    echo '<td>
                    <a class="btn btn-outline-secondary btn-rounded edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_id'] . '\')">'
                    . $product['pr_code'] .
                        '</a></td>';
                } elseif ($product['pr_status'] == '1' && $product['pr_exchange'] == null) {
                    echo '<td>
                    <a class="btn btn-outline-success btn-rounded edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_id'] . '\')">'
                    . $product['pr_code'] .
                        '</a></td>';
                } elseif ($product['pr_status'] == '2' && $product['pr_exchange'] == null) {
                    echo '<td>
                    <a class="btn btn-outline-warning btn-rounded  edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_id'] . '\')">'
                    . $product['pr_code'] .
                        '</a></td>';
                } else {
                    echo '<td>
                    <a class="btn btn-outline-secondary btn-rounded edit-button" id="showExchange">'
                    . $product['pr_code'] .
                        '</a></td>';
                }

                echo '<td>' . ($data_reasons[1] == '2' ? 'SAKABA' : 'SAMT') . '</td>';
                echo '<td>' . $product['pr_mg_code'] . '</td>';
                echo '<td>' . $product['p_product_name'] . '</td>';
                echo '<td>' . $product['p_size'] . '</td>';
                echo '<td>' . $product['p_color'] . '</td>';
                echo '<td>' . $product['p_hands'] . '</td>';
                echo '<td class="text-center bg-success-subtle">' . $product['pr_qty'] . '</td>';
                echo '<td>' . $product['o_out_date'] . '</td>';
                echo '<td>' . $data_reasons[3] . '</td>';
                echo "<td class='text-center'>";

                if ($data_reasons[2] == 1) {
                    echo '<i class="fa-solid fa-money-bill" style="color: green;"></i><br>cash';
                } elseif ($data_reasons[2] == 2) {
                    echo "<i class='fa-solid fa-qrcode' style='color: blue;'></i><br>QR";
                } elseif ($data_reasons[2] == 3) {
                    echo "<i class='fa-solid fa-cart-shopping' style='color: orange;'></i><br>shopify";
                } elseif ($data_reasons[0] == 'out to') {
                    //empty
                } else {
                    echo "<p style='color: red;'>FREE</p>"; // Default case
                }
                echo "</td>";
                echo '<td class="text-center">';

                if ($data_reasons[0] == 'out to') {
                    // Do nothing or handle the case where $data_reasons[0] is 'out to'
                } elseif ($product['o_payment'] == 1) {
                    echo '<a class="btn btn-success btn-sm btn-floating update-payment" onclick="updatePayment(\'' . $product['o_mg_code'] . '\', ' . $product['o_payment'] . ')"><i class="fa-solid fa-check"></i></a>';
                } elseif ($product['o_payment'] == 2 || $product['o_payment'] == null) {
                    echo '<a class="btn btn-outline-warning btn-sm btn-floating update-payment" onclick="updatePayment(\'' . $product['o_mg_code'] . '\', ' . $product['o_payment'] . ')"><i class="fa-solid fa-hourglass-half"></i></a>';
                }

                echo "</td>";

                if ($product['pr_status'] == '1') {
                    //ส่ง PO ไปแล้ว
                    echo "<td class='text-center' style='color:" . ($product['o_pr_code'] !== null ? 'green;' : 'red;') . "'>";

                    echo ($product['o_pr_code'] !== null ? 'issued<br>' : 'unissue');
                    // Check if 'pr_date_add' is not null and create a DateTime object
                    $pr_date = $product['pr_date_add'] !== null ? new DateTime($product['pr_date_add']) : null;

                    // Format the date to 'Y-m' (year and month) if the DateTime object is created
                    $formatted_date = $pr_date ? $pr_date->format('m-Y') : '';
                    echo $formatted_date;
                    echo "</td>";
                } elseif ($product['pr_status'] == '2') {
                    //กดรับแล้ว
                    echo "<td class='text-center text-warning'>*delivered*</td>";
                } else {
                    echo "<td class='text-center text-secondary'>PR pending</td>";
                }

                if ($product['pr_exchange'] == null) {
                    echo '<td class="text-center"><input class="select-checkbox form-check-input" type="checkbox" name="selected_ids[]" value="' . $product['o_mg_code'] . '"></td>';
                } else {
                    echo '<td></td>';
                }

                if ($product['pr_memo'] === 'Exchange' && $product['pr_exchange'] == null) {
                    echo '<td class="text-center">
                    <a class="btn btn-light btn-rounded edit-button" id="showExchange" onclick="openExchange(\'' . $product['pr_code'] . '\')"> <i class="fa-solid fa-square-minus"></i> '
                    . $product['pr_memo'] . '</a></td>';
                } else {
                    echo '<td class="text-center"> ' . $product['pr_memo'] . '</a></td>';
                }

                echo '</tr>';
            }
        }
    } catch (PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}
?>