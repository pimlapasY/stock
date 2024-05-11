<?php
// Include your database connection file
include('connect.php');

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted data
    $s_collections = $_POST['s_collection'];
    $s_product_names = $_POST['s_product_name'];
    $s_handss = $_POST['s_hands'];
    $s_colors = $_POST['s_color'];
    $s_sizes = $_POST['s_size'];
    $s_cost_prices = $_POST['s_cost_price'];
    $s_sale_prices = $_POST['s_sale_price'];

    // Prepare the SQL statement to update stock quantities
    $stmt = $pdo->prepare("UPDATE stock 
                            SET s_qty = s_qty + :qtyValue 
                            WHERE s_collection = :s_collection 
                            AND s_product_name = :s_product_name 
                            AND s_hands = :s_hands 
                            AND s_color = :s_color 
                            AND s_size = :s_size 
                            AND s_cost_price = :s_cost_price 
                            AND s_sale_price = :s_sale_price 
                            AND s_location = :s_location");

    // Loop through the submitted data to update stock quantities
    foreach ($s_collections as $index => $s_collection) {
        // Extract relevant information for each product
        $s_product_name = $s_product_names[$index];
        $s_hands = $s_handss[$index];
        $s_color = $s_colors[$index];
        $s_size = $s_sizes[$index];
        $s_cost_price = $s_cost_prices[$index];
        $s_sale_price = $s_sale_prices[$index];

        // Construct the input IDs to get the updated quantities
        $qtyValueSAMT_id = 'qtyValueSAMT' . ($index + 1);
        $qtyValueSAKABA_id = 'qtyValueSAKABA' . ($index + 1);

        // Get the updated quantities
        $qtyValueSAMT = isset($_POST[$qtyValueSAMT_id]) ? $_POST[$qtyValueSAMT_id] : 0;
        $qtyValueSAKABA = isset($_POST[$qtyValueSAKABA_id]) ? $_POST[$qtyValueSAKABA_id] : 0;

        // Execute the prepared statement for SAMT
        $stmt->execute(array(
            ':qtyValue' => $qtyValueSAMT,
            ':s_collection' => $s_collection,
            ':s_product_name' => $s_product_name,
            ':s_hands' => $s_hands,
            ':s_color' => $s_color,
            ':s_size' => $s_size,
            ':s_cost_price' => $s_cost_price,
            ':s_sale_price' => $s_sale_price,
            ':s_location' => 'SAMT'
        ));

        // Execute the prepared statement for SAKABA
        $stmt->execute(array(
            ':qtyValue' => $qtyValueSAKABA,
            ':s_collection' => $s_collection,
            ':s_product_name' => $s_product_name,
            ':s_hands' => $s_hands,
            ':s_color' => $s_color,
            ':s_size' => $s_size,
            ':s_cost_price' => $s_cost_price,
            ':s_sale_price' => $s_sale_price,
            ':s_location' => 'SAKABA'
        ));
    }

    // Return a success message
    echo "Stock quantities updated successfully.";
} else {
    // If the form data was not submitted, return an error message
    echo "Error: Form data not submitted.";
}
?>