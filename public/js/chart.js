document.addEventListener("DOMContentLoaded", function () {
    const labels = window.chartData.labels;
    const data = window.chartData.data;
    const ctx = document.getElementById("kpiChart");
    if (!ctx) return;

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Số báo cáo",
                    data: data,
                },
            ],
        },
    });

    const canvasElement = document.getElementById("tkKPIChart");
    const ctx2 = canvasElement.getContext("2d");

    const hoanthanh = Number(canvasElement.dataset.ht) || 0;
    const dangthuchien = Number(canvasElement.dataset.dth) || 0;
    const chuadat = Number(canvasElement.dataset.cd) || 0;
    const dangno = Number(canvasElement.dataset.dn) || 0;

    const myChart = new Chart(ctx2, {
        type: "pie",
        data: {
            labels: ["Hoàn thành", "Chưa đạt", "Đang thực hiện", "Đang nợ"],
            datasets: [
                {
                    label: "Số lượng KPI",
                    data: [hoanthanh, chuadat, dangthuchien, dangno],
                    backgroundColor: ["#21d55a", "red", "#17a2b8", "#cd5533ff"],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });
});
