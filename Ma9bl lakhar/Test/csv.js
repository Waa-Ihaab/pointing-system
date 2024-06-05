function downloadCSV(filename, data) {
    const csv = convertToCSV(data);
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, filename);
    } else {
        const link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}

function convertToCSV(data) {
    const headers = ['Username', 'Tache', 'Timestamp', 'Type'];
    const rows = [headers];
    for (const row of data) {
        rows.push(Object.values(row));
    }
    return rows.map(row => row.join(',')).join('\n');
}

document.getElementById('downloadCSV').addEventListener('click', function () {
    const table = document.querySelector('table');
    const rows = table.querySelectorAll('tr');
    const data = [];
    // Start iterating from the second row to skip the header row
    for (let i = 1; i < rows.length; i++) {
        const rowData = [];
        const cols = rows[i].querySelectorAll('td');
        for (const col of cols) {
            rowData.push(col.textContent.trim());
        }
        data.push(rowData);
    }
    downloadCSV('user_data.csv', data);
});
