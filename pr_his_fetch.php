<?php
// Include database connection
include 'connect.php';



// Check if reasons is set
if (isset($_POST['store'])) {

    // Get reasons value
    $store = $_POST['store'];
    $month = $_POST['month'];
    $currentMonth = date('m');
    $currentYear = date('Y');

    $previousMonth = date('m', strtotime('-1 month')); // Previous month
    $nextMonth = date('m', strtotime('+1 month')); // Next month
    $startDate = "$currentYear-$previousMonth-21";
    $endDate = "$currentYear-$currentMonth-20";


    try {
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement based on reasons
        if ($store == 'all') {
            $sql = "SELECT o.*, p.*, pr.*, st.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    LEFT JOIN store st ON st.st_id = o.o_store
                    WHERE MONTH(pr.pr_date) = :month";
        } elseif ($store == 'samt') {
            $sql = "SELECT o.*, p.*, pr.*, st.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    LEFT JOIN store st ON st.st_id = o.o_store
                    WHERE o.o_store = '1' AND MONTH(pr.pr_date) = :month";
        } elseif ($store == 'sakaba') {
            $sql = "SELECT o.*, p.*, pr.*, st.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    LEFT JOIN store st ON st.st_id = o.o_store
                    WHERE o.o_store != '1' AND MONTH(pr.pr_date) = :month";
        } else {
            $sql = "SELECT o.*, p.*, pr.*, st.*
                    FROM pr
                    LEFT JOIN product p ON pr.pr_product_id = p.p_product_id
                    LEFT JOIN stockout o ON o.o_mg_code = pr.pr_mg_code
                    LEFT JOIN store st ON st.st_id = o.o_store
                    WHERE MONTH(pr.pr_date) = :month";
        }

        // Add ORDER BY clause to the SQL statement
        $sql .= " AND pr.pr_status = '3' ORDER BY pr.pr_date_add DESC";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // ผูกค่าเดือนกับพารามิเตอร์ใน SQL
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch all rows as an associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the table rows
        foreach ($products as $index => $product) {
            echo '<tr>';
            echo '<td class="text-center"><input class="select-checkbox form-check-input" type="checkbox" name="selected_ids[]" value="' . $product['o_mg_code'] . '"></td>';
            echo '<td>
            <a class="btn btn-light btn-rounded edit-button" id="showExchange" onclick="openEditModal(\'' . $product['pr_code'] . '\')" style="display: none;">
                <i class="fa-solid fa-right-left"></i>
            </a>' . $product['pr_code'] . '</td>';
            echo  '<td>' . ($product['st_name']) . '</td>';
            echo  '<td>' . $product['pr_mg_code'] . '</td>';
            echo  '<td>' . $product['p_product_name'] . '</td>';
            echo  '<td>' . $product['p_size'] . '</td>';
            echo  '<td>' . $product['p_color'] . '</td>';
            echo  '<td>' . $product['p_hands'] . '</td>';
            echo  '<td class="text-center bg-success-subtle ">' . $product['pr_qty'] . '</td>';
            echo  '<td class="text-center">' . $product['pr_date'] . '</td>';
            echo  '<td>' . $product['o_customer'] . '</td>';
            echo "<td class='text-center'>";


            $badgeMap = [
                1 => ['class' => 'success', 'text' => 'cash'],
                2 => ['class' => 'primary', 'text' => 'QR'],
                3 => ['class' => 'warning', 'text' => 'shopify'],
            ];
            $badge = $badgeMap[$product['o_payment_option']] ?? ['class' => 'danger', 'text' => 'sale sample'];

            echo "<span class='badge badge-{$badge['class']} rounded-pill d-inline'>{$badge['text']}</span>";
            echo "</td>";


            //เช็ค payment       
            echo '<td class="text-center">';

            if (!($product['o_reasons'] == 'out to' || $product['pr_mg_code'] == null)) {
                $btnClass = $product['o_payment'] == 1 ? 'badge-success' : 'badge-secondary';
                $textPayment = $product['o_payment'] == 1 ? 'success' : 'pending';
                echo '<a class="badge ' . $btnClass . ' update-payment">' . $textPayment . '</a>';
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
            } else {
                echo "<span class='badge badge-secondary'>PR pending</span>";
            }

            echo "</td>";

            echo "<td class='text-center'>" . $product['o_memo'] . "</td>";
            echo '</tr>';
        }
    } catch (PDOException $e) {
        // If an error occurs, output it
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}