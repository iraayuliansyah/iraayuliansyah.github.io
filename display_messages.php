<!DOCTYPE html>
<html>
<head>
    <title>Messages Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {    
            background-color: rgb(248, 148, 215);
            color: white;
        }
        tr:nth-child(even){
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #fadef4;
        }
        .action-buttons a {
            padding: 8px 12px;
            margin: 0 5px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
        }
        .edit-button {
            background-color: #4CAF50;
        }
        .delete-button {
            background-color:#f44336;
        }
        .action-buttons a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<h2>Messages Table</h2>

<table>
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Nomor</th>
        <th>Subjek</th>
        <th>Pesan</th>
        <th>Aksi</th>
    </tr>
    <?php
    $filename = "messages.txt";
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $messageLines = [];
    $message = [];
    foreach ($lines as $line) {
        if (strpos($line, 'Nama:') === 0 && !empty($message)) {
            $messageLines[] = $message;
            $message = [];
        }
        $message[] = $line;
    }
    if (!empty($message)) {
        $messageLines[] = $message;
    }

    foreach ($messageLines as $index => $message) {
        echo "<tr data-index='$index'>";
        foreach ($message as $line) {
            $parts = explode(': ', $line);
            if (count($parts) == 2) {
                echo "<td>{$parts[1]}</td>";
            }
        }
        echo '<td>
                <button class="btn btn-edit" onclick="editRow(this)">Ubah</button>
                <button class="btn btn-delete" onclick="deleteRow(this)">Hapus</button>
              </td>';
        echo "</tr>";
    }
    ?>
</table>

<script>
function editRow(button) {
    var row = button.parentNode.parentNode;
    var cells = row.getElementsByTagName("td");

    // Ganti isi sel dengan input field untuk mengedit
    for (var i = 0; i < cells.length - 1; i++) {
        var cell = cells[i];
        var cellText = cell.innerText;
        cell.innerHTML = "<input type='text' value='" + cellText + "'>";
    }

    // Ganti tombol "Ubah" dengan tombol "Simpan"
    button.innerText = "Simpan";
    button.onclick = function() { saveRow(button); };
}

function saveRow(button) {
    var row = button.parentNode.parentNode;
    var cells = row.getElementsByTagName("td");
    var index = row.getAttribute("data-index");

    var nama = cells[0].getElementsByTagName("input")[0].value;
    var email = cells[1].getElementsByTagName("input")[0].value;
    var nomor = cells[2].getElementsByTagName("input")[0].value;
    var subjek = cells[3].getElementsByTagName("input")[0].value;
    var pesan = cells[4].getElementsByTagName("input")[0].value;

    var formData = new FormData();
    formData.append('index', index);
    formData.append('nama', nama);
    formData.append('email', email);
    formData.append('nomor', nomor);
    formData.append('subjek', subjek);
    formData.append('pesan', pesan);

    fetch('edit_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Perbarui sel dengan nilai yang diedit
            cells[0].innerText = nama;
            cells[1].innerText = email;
            cells[2].innerText = nomor;
            cells[3].innerText = subjek;
            cells[4].innerText = pesan;

            // Ganti tombol "Simpan" dengan tombol "Ubah"
            button.innerText = "Ubah";
            button.onclick = function() { editRow(button); };
        } else {
            alert("Error updating record: " + response.statusText);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error updating record: " + error);
    });
}

function deleteRow(button) {
    var row = button.parentNode.parentNode;
    var index = row.getAttribute("data-index");

    fetch('delete_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'index=' + index
    })
    .then(response => {
        if (response.ok) {
            row.parentNode.removeChild(row);
        } else {
            alert("Error deleting record: " + response.statusText);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error deleting record: " + error);
    });
}
</script>

</body>
</html>