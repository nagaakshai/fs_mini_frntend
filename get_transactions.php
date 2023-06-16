<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['accno'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Account number is missing'));
        exit();
    }

    $accno = $_GET['accno'];
    $transactions = array();
    $file = fopen('transactions.txt', 'r');
    while (($line = fgets($file)) !== false) {
        $transactionData = explode(', ', $line);
        $transaction = array(
            'accno' => $transactionData[0],
            'checkno' => $transactionData[1],
            'date' => $transactionData[2],
            'desc' => $transactionData[3],
            'amount' => $transactionData[4]
        );
        if ($transaction['accno'] === $accno) {
            $transactions[] = $transaction;
        }
    }
    fclose($file);
    echo json_encode($transactions);
}
?>
