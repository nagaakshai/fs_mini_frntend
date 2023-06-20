<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accno = $_POST['accno'];
    $checkno = $_POST['checkno'];
    $date = $_POST['date'];
    $desc = $_POST['desc'];
    $amount = $_POST['amount'];

    if (isCheckNumberUnique($checkno)) {
        $transactionData = "$accno, $checkno, $date, $desc, $amount\n";
        $file = fopen('transactions.txt', 'a');
        fwrite($file, $transactionData);
        fclose($file);
        $response = array('status' => 'success', 'message' => 'Transaction added successfully');
        echo json_encode($response);
        exit();
    } 
    else {
        $response = array('status' => 'error', 'message' => 'Check number already exists for the given account');
        echo json_encode($response);
        exit();
    }
}
/**
 * Check if the check number is unique.
 *
 * @param string $checkno The check number to check.
 * @return bool True if the check number is unique, false otherwise.
 */
function isCheckNumberUnique($checkno){
    if(!file_exists('transactions.txt')) {
        return true;
    }
    $file = file_get_contents('transactions.txt');
    $transactions = explode("\n", $file);
    foreach ($transactions as $transaction) {
        $data = explode(", ", $transaction);
        if(count($data) < 2) {
            return true;
        }
        $existingCheckNo = $data[1];
        if ($existingCheckNo === $checkno) {
            return false;
        }
    }
    return true; 
}
?>