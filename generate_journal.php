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
            if (isset($_POST['generateJournal'])) {
                // Read transactions.txt file
                $transactions = file('transactions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                // // Sort transactions by date
                // usort($transactions, function ($a, $b) {
                //     $aFields = explode(',', $a);
                //     $bFields = explode(',', $b);
                //     $aDate = strtotime($aFields[2]);
                //     $bDate = strtotime($bFields[2]);
                //     if ($aDate === $bDate) {
                //         // Sort by account number if the dates are the same
                //         return strcmp($aFields[0], $bFields[0]);
                //     }
                //     return $aDate - $bDate;
                // });

                // Generate the journal content
                $journalContent = '';
                foreach ($transactions as $transaction) {
                    $fields = explode(',', $transaction);
                    foreach ($fields as $key => $field) {
                        if ($key === 2) {
                            $date = date('d-m-Y', strtotime($field));
                            $journalContent .= $date . ' | ';
                        } else {
                            $journalContent .= $field . ' | ';
                        }
                    }
                    $journalContent .= "\n";
                }
                // Write the journal content to the file, overwriting the previous content
                file_put_contents('journal.txt', $journalContent);
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
