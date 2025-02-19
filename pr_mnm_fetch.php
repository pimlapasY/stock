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

        $sql = "SELECT o.*, p.*, pr.*, store.*
                FROM pr
                LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                LEFT JOIN store ON o.o_store = store.st_id
                ";

        // Adding conditions based on payment status
        if ($payment == 0) {
            if ($store == 'all') {
                $sql .= " WHERE (o.o_payment IS NOT NULL OR o.o_payment IS NULL)";
            } elseif ($store == 'samt') {
                $sql .= " WHERE o.o_store = '1'";
            } elseif ($store == 'sakaba') {
                $sql .= " WHERE o.o_store != '1'";
            } else {
                $sql .= " WHERE (o.o_payment IS NOT NULL OR o.o_payment IS NULL)";
            }
        } elseif ($payment == 1) {
            if ($store == 'all') {
                $sql .= " WHERE (o.o_payment = 2 OR o.o_payment IS NULL)";
            } elseif ($store == 'samt') {
                $sql .= " WHERE o.o_store = '1' AND (o.o_payment = 2 OR o.o_payment IS NULL)";
            } elseif ($store == 'sakaba') {
                $sql .= " WHERE o.o_store != '1' AND (o.o_payment = 2 OR o.o_payment IS NULL)";
            } else {
                $sql .= " WHERE (o.o_payment = 2 OR o.o_payment IS NULL)";
            }
        } elseif ($payment == 2) {
            if ($store == 'all') {
                $sql .= " WHERE o.o_payment = 1";
            } elseif ($store == 'samt') {
                $sql .= " WHERE o.o_store = '1' AND o.o_payment = 1";
            } elseif ($store == 'sakaba') {
                $sql .= " WHERE o.o_store != '1' AND o.o_payment = 1";
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
        $sql .= " ORDER BY  pr.pr_id DESC, pr.pr_code DESC, pr.pr_date_update, pr.pr_date_add DESC";

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

                 //เช็คสถานะว่ามีการเปลี่ยน ให้ข้อมูลเก่าโดนซ่อน
            if ($product['pr_exchange'] == null) {
                echo '<tr>';
                echo '<td class="text-center"><input class="select-checkbox form-check-input" type="checkbox" name="selected_ids[]" value="' . $product['pr_id'] . '"></td>';
            } else {
                echo '<tr class="table-secondary" id="exchangeID' . $product['pr_code'] . '" hidden>';
                echo  '<td></td>';
            }

            $classColor = ($product['pr_status'] == '1' && $product['pr_exchange'] == null) ? 'success' :
              (($product['pr_status'] == '2' && $product['pr_exchange'] == null) ? 'warning' :
              'secondary');


            echo '<td> <a class="btn btn-outline-'.$classColor.' btn-rounded edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_id'] . '\')">'
                            . $product['pr_code'] .
                            '</a></td>';

               

                echo '<td>' . $product['st_name']  . '</td>';
                echo '<td>' . $product['pr_mg_code'] . '</td>';
                echo '<td>' . $product['p_product_name'] . '</td>';
                echo '<td>' . $product['p_size'] . '</td>';
                echo '<td>' . $product['p_color'] . '</td>';
                echo '<td>' . $product['p_hands'] . '</td>';
                echo '<td class="text-center bg-success-subtle">' . $product['pr_qty'] . '</td>';
                //วันที่ขายออก หรือ สร้าง stock out
                echo '<td>' . $product['o_out_date'] . '</td>';
                //ชื่อลูกค้า
                echo '<td>' . $data_reasons[2] . '</td>';
                //ช่องทางการชำระเงิน
                echo "<td class='text-center'>";

                $badgeMap = [
                    1 => ['class' => 'success', 'text' => 'cash'],
                    2 => ['class' => 'primary', 'text' => 'QR'],
                    3 => ['class' => 'warning', 'text' => 'shopify'],
                ];
                $badge = $badgeMap[$data_reasons[1]] ?? ['class' => 'danger', 'text' => 'sale sample'];

                echo "<span class='badge badge-{$badge['class']} rounded-pill d-inline'>{$badge['text']}</span>";
                echo "</td>";

                //เช็ค payment       
                echo '<td class="text-center">';

                if (!($data_reasons[0] == 'out to' || $product['pr_mg_code'] == null)) {
                    $btnClass = $product['o_payment'] == 1 ? 'btn-success' : 'btn-outline-warning';
                    $icon = $product['o_payment'] == 1 ? 'fa-check' : 'fa-hourglass-half';
                    echo '<a class="btn ' . $btnClass . ' btn-sm btn-floating update-payment" onclick="updatePayment(\'' . $product['o_mg_code'] . '\', ' . $product['o_payment'] . ')"><i class="fa-solid ' . $icon . '"></i></a>';
                }

                echo '</td>';
                

                echo "<td class='text-center'>";

                $status = $product['pr_status'];
                $pr_date = $product['pr_date_add'] ? (new DateTime($product['pr_date_add']))->format('m-Y') : '';
                
                if ($status == '1') {
                    echo "<span class='badge badge-success'>success $pr_date</span>";
                } elseif ($status == '2') {
                    echo "<span class='badge badge-warning'>*delivered*</span>";
                } elseif ($status == '3') {
                    echo "<span class='badge badge-info'>stock in</span>";
                }else {
                    echo "<span class='badge badge-secondary'>PR pending</span>";
                }
                
                echo "</td>";


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