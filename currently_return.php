<?php
// Include database connection
include 'connect.php';

if (isset($_POST['ids'])) {
/////////////////////////////////////////////////////////////////////////////////////////////
    $currentDate = date("ymd");

    // Check if the product already exists in the stock table
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stockin");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    // Increment count by 1 for the new row
    $count++;
    $i_no = $currentDate . $count; //ต้องอยู่นอกลูปเพราะว่าต้องการให้เป็น ID เดียวกันก่อน insert 1 ครั้ง
    $usercode_add =  $_SESSION['id'];

////////////////////////////////////////////////////////////////////////////////////////////
  $ids = $_POST['ids'];
  $memo = $_POST['memo'];
  $returnDate = $_POST['returnDate'];

  // Prepare the SQL query to fetch details for selected IDs
  $inQuery = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $pdo->prepare("SELECT o.*, p.*, s.s_qty
                          FROM stockout o
                          LEFT JOIN product p ON o.o_product_id = p.p_product_id
                          LEFT JOIN stock s ON s.s_product_id = o.o_product_id
                          WHERE o.o_mg_code IN ($inQuery)");

  // Bind the parameters
  foreach ($ids as $index => $id) {
    $stmt->bindValue($index + 1, $id);
  }

  // Execute the statement to fetch product details
  $stmt->execute();
  $selectedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
/////////////////////////////////////////////////////////////////////////////////////////////

  // Prepare the insert statement for stockin table (outside the loop)
  $sql_stockin = "INSERT INTO stockin (i_no, i_product_id, i_mg_code, i_qty, i_product_code, i_status, i_username, i_total_price,	i_current_qty, i_memo,  i_date,  i_date_add) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())";
  $stmt_stockin = $pdo->prepare($sql_stockin);

  // Prepare the update statement for stock table (outside the loop)
  $sql_update_stock = "UPDATE stock SET s_qty = s_qty + ? , s_return_date = now() WHERE s_product_id = ?";
  $stmt_update_stock = $pdo->prepare($sql_update_stock);

  //update return
  $sql_update_stockout = "UPDATE stockout SET o_return = 1 WHERE o_mg_code = ?";
  $stmt_update_stockout = $pdo->prepare($sql_update_stockout);


  $insertedRows = 0;
  foreach ($selectedProducts as $index => $product) {
    // Bind value for each insert
    $stmt_stockin->bindValue(1, $i_no);
    $stmt_stockin->bindValue(2, $product['o_product_id']);
    $stmt_stockin->bindValue(3, $product['o_mg_code']);
    $stmt_stockin->bindValue(4, $product['o_out_qty']);
    $stmt_stockin->bindValue(5, $product['o_product_code']);
    $stmt_stockin->bindValue(6, '2');
    $stmt_stockin->bindValue(7, $usercode_add);

    $total_price = ($product['o_out_qty']* $product['p_cost_price']);
    $stmt_stockin->bindValue(8, $total_price);
    $stmt_stockin->bindValue(9,  $product['s_qty']);
    $stmt_stockin->bindValue(10,  $memo);
    $stmt_stockin->bindValue(11,  $returnDate);



    // Execute insert for each product
    if ($stmt_stockin->execute()) {
      $insertedRows++;

      // Update stock quantity in separate statement
      $stmt_update_stock->bindValue(1, $product['o_out_qty']);
      $stmt_update_stock->bindValue(2, $product['o_product_id']);
      $stmt_update_stock->execute();
      
      //stock out
      $stmt_update_stockout->bindValue(1, $product['o_mg_code']); // Ensure $product['o_mg_code'] is available and valid
      $stmt_update_stockout->execute();
    }


  }
/////////////////////////////////////////////////////////////////////////////////////////////

  if ($insertedRows > 0) {
    echo 'Successfully inserted ' . $insertedRows . ' rows';
  } else {
    echo 'No data inserted'; // Consider adding more specific error message
  }
} else {
  echo 'No data received';
}
?>