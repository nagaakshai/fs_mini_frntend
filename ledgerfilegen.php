<!DOCTYPE html>
<html>
<head>
    <style>
        .container1 {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container1">
    <?php 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_POST['generateLedger'])) {
            // Read transactions.txt file
            $transactions = file('transactions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Create an array to store ledger data
            $ledger = array();

            foreach ($transactions as $transaction) {
                $fields = explode(',', $transaction);

                $accno = $fields[0];
                $amount = floatval($fields[4]);

                if (!isset($ledger[$accno])) {
                    $ledger[$accno] = array('transactions' => array(), 'balance' => 0);
                }

                // Add the transaction to the ledger
                $ledger[$accno]['transactions'][] = array(
                    'checkno' => $fields[1],
                    'date' => $fields[2],
                    'desc' => $fields[3],
                    'amount' => $amount
                );

                // Update the balance in the ledger
                $ledger[$accno]['balance'] += $amount;
            }

            // Sort the ledger by account number in increasing order
            ksort($ledger);

            // Write the ledger data to a new file
            $file = fopen('ledger.txt', 'w');
            fwrite($file, "Check No\tDate\t\t\tDescription\t\tAmount\n");
            fwrite($file, "\n");
            foreach ($ledger as $accno => $account) {
                fwrite($file, "Account Number: $accno\n");
                fwrite($file, "\n");

                // Write the table rows
                foreach ($account['transactions'] as $transaction) {
                    $checkno = $transaction['checkno'];
                    $date = date('d-m-Y', strtotime($transaction['date']));
                    $desc = $transaction['desc'];
                    $amount = $transaction['amount'];

                    // Format the table cells
                    $formattedRow = sprintf("%-10s\t%-18s\t%-25s\t%-8s\n", $checkno, $date, $desc, $amount);
                    fwrite($file, $formattedRow);
                }

                // Write the balance amount
                $balance = $account['balance'];
                fwrite($file, "\n");
                fwrite($file, "\t\t\t\tBalance Amount: $balance\n");
                fwrite($file, "\n");
                fwrite($file, "==============================================================================\n");
            }
            fclose($file);

            // Redirect to the ledger file
            header("Location: ledger.txt");
            exit();
        }
    }
    ?>
    </div>
</body>
</html>
