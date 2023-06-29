<!DOCTYPE html>
<html>
<head>
    <title>Ledger</title>
    <link rel="stylesheet" href="style.css">
    <style>

    
    .tr{
        padding: 10px 20px;
            color: crimson;
            background-color: #fbd0d9;
            border: none;
            cursor: pointer;
            font-size:20px;
            }

    .tr:hover{
    background:crimson;
    color:#fff;
}
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body style="background-image: url(./111.jpg);background-size: cover;background-position: center center;">
    <div class="result-container" style="background-image: url(./111.jpg);background-size: cover;background-position: center center;">
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
            'date' => date('d-m-Y', strtotime($fields[2])),
            'desc' => $fields[3],
            'amount' => $amount
        );

        // Update the balance in the ledger
        $ledger[$accno]['balance'] += $amount;
    }

    // Sort the ledger by account number in increasing order
    ksort($ledger);

    // Sort transactions for each account by date in ascending order
    foreach ($ledger as &$account) {
        usort($account['transactions'], function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            return $dateA - $dateB;
        });
    }
    unset($account); 

    // Generate month-wise summary for each account
    $monthSummary = array();

    foreach ($ledger as $accountNumber => $account) {
        foreach ($account['transactions'] as $transaction) {
            $month = date('m-Y', strtotime($transaction['date']));

            if (!isset($monthSummary[$accountNumber][$month])) {
                $monthSummary[$accountNumber][$month] = 0;
            }

            $monthSummary[$accountNumber][$month] += $transaction['amount'];
        }
    }
?>
<center style="background-color:white">
    <?php
    // Print the month-wise summary for each account
    foreach ($monthSummary as $accountNumber => $summary) {?>
    <h2><?php
        echo "Account Number: " . $accountNumber . "<br>";
?>
        </h2>
<?php
        echo "Month-wise Summary:<br>";

        foreach ($summary as $month => $totalBalance) {
            echo "Month: " . $month . ", Total Balance: " . $totalBalance . "<br>";
        }

        echo "<br>";
    }

?>
</center>
    <table style="background-color:white">
        <tr>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>Check no</th>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
        </tr>
        <?php foreach ($ledger as $accno => $account) { ?>
        <tr>
            <th colspan="<?php echo count($account['transactions']) + 4; ?>"><?php echo "Account no: ".$accno; ?></th>
        </tr>
        <?php foreach ($account['transactions'] as $transaction) { ?>
        <tr>
            <td></td>
            <td><?php echo $transaction['checkno']; ?></td>
            <td><?php echo $transaction['date']; ?></td>
            <td><?php echo $transaction['desc']; ?></td>
            <td><?php echo $transaction['amount']; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="5" style="text-align: center;">Balance Amount: <?php echo $account['balance']; ?></td>
        </tr>
        <tr>
            <td colspan="5" style="background-color: #f2f2f2;"></td>
        </tr>
        <?php } ?>
    </table>
    <?php 
    if (isset($_GET['generateLedger'])) {
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
       // header("Location: ledger.txt");
        exit();
    }
    ?>
    <center>
        <a><br></a>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <p>
    <button type="submit" name="generateLedger" class="tr">Generate Ledger File</button>
</form>
<a><br></a>
<p>
    <a href="ledg_jour.html" style="text-decoration:none;">Back</a>
<br>
</p>
    <?php } ?>
    </center>
    <a><br></a>
    </div>
</body>
</html>
