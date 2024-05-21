<?php include('navbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Stockin</title>
</head>

<body>
    <div class="container">
        <h1><i class="fa-solid fa-folder-plus fa-2x"></i> History</h1> <br>
        <table class="table table-hover">
            <thead class="table-warning text-center">
                <tr style="vertical-align: middle;">
                    <th rowspan="2">#</th>
                    <th rowspan="2">Stockin No.</th>
                    <th rowspan="2">List Amount</th>
                    <th rowspan="2">User Add</th>
                    <th rowspan="2">Memo</th>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php


                $stmt = $pdo->prepare("SELECT i.*, u.u_username, COUNT(*) AS count 
                FROM 
                stockin i 
                LEFT JOIN user u ON u.u_userid = i.i_username  
                GROUP BY i.i_no
                ORDER BY i.i_date_add DESC");


             // Execute the statement
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

             // Loop through products and output each row
             foreach ($products as $index => $product) {
             echo "<tr class='text-center' data-id='" . htmlspecialchars($product['i_no']) . "'>";
             echo "<td>" . ($index + 1) . "</td>"; // Display No starting from 1
             echo "<td><a class='stockin-link' style='cursor:pointer;'>" . htmlspecialchars($product['i_no']) . "</a></td>";
             echo "<td>" . htmlspecialchars($product['count']) . "</td>";
             echo "<td>" . htmlspecialchars($product['u_username']) . "</td>";
             echo "<td class='text-start'>" .($product['i_memo'] != null ? '[ ' .htmlspecialchars($product['i_memo']). ' ]' : ''). "</td>";
             echo "<td>" . htmlspecialchars($product['i_date']) . "</td>";
             echo "<td style='color:" . ($product['i_status'] == 1 ? 'green' : 'orange') . "'>" . ($product['i_status'] == 1 ? 'Purchased' : 'Returned') . "</td>";
             echo "</tr>";
             }
            
            ?>
            </tbody>
        </table>
    </div>


    <!-- Modal Structure -->
    <div id="stockinModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="stockinModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockinModalLabel"><i class="fa-solid fa-circle-info fa-lg"></i> Stockin
                        Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="stockinModalBody">
                    <!-- Data will be dynamically populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-footer" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle click event -->
    <script>
    $(document).ready(function() {
        $('body').on('click', 'a.stockin-link', function(e) {
            e.preventDefault();
            var i_no = $(this).text(); // Get the stockin number
            // AJAX request to fetch data related to the stockin number
            $.ajax({
                url: 'fetch_stockin_details.php', // Path to your PHP file to fetch details
                method: 'POST',
                data: {
                    i_no: i_no
                },
                success: function(response) {
                    $('#stockinModalBody').html(
                        response); // Populate modal body with fetched data
                    $('#stockinModal').modal('show'); // Show the modal
                },
                error: function() {
                    alert('Error fetching stockin details.');
                }
            });
        });
    });
    </script>
    </div>
</body>

</html>