document.addEventListener('DOMContentLoaded', function() {
    var searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var accNoInput = document.getElementById('accno');
            var accNo = accNoInput.value.trim();
            if (accNo !== '') {
                // Send the account number to get_transactions.php
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        displayTransactions(response);
                    }
                };
                xhr.open('GET', 'get_transactions.php?accno=' + encodeURIComponent(accNo), true);
                xhr.send();
            }
        });
    } else {
        console.error('Search form element not found.');
    }
});

function displayTransactions(transactions) {
    var resultContainer = document.getElementById('result-container');
    resultContainer.innerHTML = ''; // Clear previous results

    if (transactions.length > 0) {
        var table = document.createElement('table');
        table.classList.add('transaction-table');

        // Create table headers
        var headers = ['Account No', 'Check No', 'Date', 'Description', 'Amount'];
        var headerRow = document.createElement('tr');
        headers.forEach(function(header) {
            var th = document.createElement('th');
            th.textContent = header;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        // Create table rows for each transaction
        transactions.forEach(function(transaction) {
            var row = document.createElement('tr');
            Object.values(transaction).forEach(function(value) {
                var cell = document.createElement('td');
                cell.textContent = value;
                row.appendChild(cell);
            });
            table.appendChild(row);
        });

        resultContainer.appendChild(table);
    } else {
        var message = document.createElement('p');
        message.textContent = 'No transactions found for the specified account number.';
        resultContainer.appendChild(message);
    }
}
