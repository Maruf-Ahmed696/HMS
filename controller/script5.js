let chart;

document.getElementById("reportForm").addEventListener("submit", function(e){
    e.preventDefault();
    generateReport();
});

function generateReport(){
    const results = document.getElementById("reportResults");
    results.style.display = "block";

    const data = [12, 19, 8, 15, 22, 17, 10];
    const labels = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];

    document.getElementById("summary1").innerText = data.reduce((a,b)=>a+b,0);
    document.getElementById("summary2").innerText = Math.round(data.reduce((a,b)=>a+b,0)/data.length);
    document.getElementById("summary3").innerText = Math.max(...data);

    const tbody = document.querySelector("#reportTable tbody");
    tbody.innerHTML = "";
    labels.forEach((d,i)=>{
        tbody.innerHTML += `<tr><td>${d}</td><td>${data[i]}</td></tr>`;
    });

    drawChart(labels,data);
}

function drawChart(labels,data){
    if(chart) chart.destroy();
    const ctx = document.getElementById("reportChart");
    chart = new Chart(ctx,{
        type:"bar",
        data:{
            labels,
            datasets:[{
                label:"Report Data",
                data,
                backgroundColor:"#2563eb"
            }]
        }
    });
}

function generateQuickReport(type){
    alert("Quick Report: " + type.toUpperCase());
    generateReport();
}

function exportCSV(){
    alert("CSV Exported (Demo)");
}
