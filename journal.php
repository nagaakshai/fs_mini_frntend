<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal</title>
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

        table,th,td {
            border: 1px solid black;
            padding: auto;
            border-collapse: collapse;
        }

        th,td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <center>
        <h1><a><br></a></h1>
        <div id="result-container">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>&nbsp;Account No&nbsp;</th>
                        <th>&nbsp;&nbsp;Check No&nbsp;&nbsp;</th>
                        <th>&nbsp;&nbsp;Date&nbsp;&nbsp;</th>
                        <th>&nbsp;&nbsp;Description&nbsp;&nbsp;</th>
                        <th>&nbsp;Amount&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Read transactions.txt file
                    $transactions = file('transactions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                    // Sort transactions by date
                    usort($transactions, function ($a, $b) {
                        $aFields = explode(',', $a);
                        $bFields = explode(',', $b);
                        $aDate = strtotime($aFields[2]);
                        $bDate = strtotime($bFields[2]);
                        if ($aDate === $bDate) {
                            // Sort by account number if the dates are the same
                            return strcmp($aFields[0], $bFields[0]);
                        }
                        return $aDate - $bDate;
                    });

                    foreach ($transactions as $transaction) {
                        $fields = explode(',', $transaction);
                        echo '<tr>';
                        foreach ($fields as $key => $field) {
                            if ($key === 2) {
                                $date = date('d-m-Y', strtotime($field)); // Format date as "date-month-year"
                                echo '<td>&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;</td>';
                            } else {
                                echo '<td>&nbsp;&nbsp;' . $field . '&nbsp;&nbsp;</td>';
                            }
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a><br></a>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <p>
    <button type="submit" name="generateLedger" class="tr">Generate Journol File</button>
</form>
<a><br></a>
<a href="ledg_jour.html">Back</a>
<a><br></a>
    </center>
    <br>
</body>

</html>
