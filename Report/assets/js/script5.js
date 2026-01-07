let chartInstance = null;

function generateReport(){
    fetch("../ajax/report.php")
        .then(res => res.json())
        .then(data => {
            if(!Array.isArray(data) || data.length === 0){
                alert("No report data found");
                return;
            }

            const labels = data.map(d => d.d);
            const values = data.map(d => d.v);

            drawChart(labels, values);
            fillTable(labels, values);
        });
}

function drawChart(labels, values){
    const ctx = document.getElementById("reportChart").getContext("2d");

    if(chartInstance){
        chartInstance.destroy();
    }

    chartInstance = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Booked Seats",
                data: values
            }]
        }
    });
}

function fillTable(labels, values){
    const tbody = document.querySelector("#reportTable tbody");
    tbody.innerHTML = "";

    labels.forEach((label, index) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${label}</td>
            <td>${values[index]}</td>
        `;
        tbody.appendChild(tr);
    });
}

function exportCSV(){
    window.location.href = "../ajax/export_csv.php";
}
