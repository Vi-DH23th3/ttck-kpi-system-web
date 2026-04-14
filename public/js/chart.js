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

    // 1. Lấy phần tử canvas
    // const canvasElement = document.getElementById("ChartJs");
    // const ctx = canvasElement.getContext("2d");

    // // 2. Lấy dữ liệu từ dataset (ép kiểu về số để biểu đồ chạy chuẩn)
    // const tongviec = Number(canvasElement.dataset.tv);
    // const dangthuchien = Number(canvasElement.dataset.dt);
    // const choduyet = Number(canvasElement.dataset.cd);
    // const quahan = Number(canvasElement.dataset.qh);
    // const hoanthanh = Number(canvasElement.dataset.dht);
    // // 3. Khởi tạo biểu đồ
    // const myChart = new Chart(ctx, {
    //     type: "bar",
    //     data: {
    //         labels: [
    //             "Tổng việc",
    //             "Hoàn thành",
    //             "Đang thực hiện",
    //             "Chờ phê duyệt",
    //             "Quá hạn",
    //         ],
    //         datasets: [
    //             {
    //                 label: "Số lượng",
    //                 data: [tongviec, hoanthanh, dangthuchien, choduyet, quahan],
    //                 backgroundColor: [
    //                     "rgba(54, 162, 235, 0.6)",
    //                     "rgba(8, 55, 226, 0.6)",
    //                     "rgba(255, 206, 86, 0.6)",
    //                     "rgba(75, 192, 192, 0.6)",
    //                     "rgba(255, 99, 132, 0.6)",
    //                 ],
    //                 borderColor: [
    //                     "rgba(54, 162, 235, 1)",
    //                     "rgba(99, 127, 228, 0.6)",
    //                     "rgba(255, 206, 86, 1)",
    //                     "rgba(75, 192, 192, 1)",
    //                     "rgba(255, 99, 132, 1)",
    //                 ],
    //                 borderWidth: 1,
    //             },
    //         ],
    //     },
    //     options: {
    //         //là các tùy chọn cho biểu đồ
    //         responsive: true, //nghĩa là: biểu đồ sẽ tự động co giãn theo kích thước màn hình
    //         plugins: {
    //             //nghĩa là: các tùy chọn cho các thành phần của biểu đồ
    //             legend: {
    //                 //nghĩa là: các tùy chọn cho chú thích
    //                 position: "bottom", // Để dưới cho dễ nhìn
    //             },
    //             title: {
    //                 display: true,
    //                 text: "Thống kê trạng thái công việc",
    //             },
    //         },
    //     },
    // });
});
// const datduoc = Number(cvE.dataset.dc) || 0;
// const conlai = Number(cvE.dataset.cl) || 0;

// // Kiểm tra nếu cả hai đều bằng 0
// const isEmpty = datduoc === 0 && conlai === 0;

// const chartPB = new Chart(ct, {
//     type: "doughnut",
//     data: {
//         labels: isEmpty ? ["Chưa có dữ liệu"] : ["Đã hoàn thành", "Còn lại"],
//         datasets: [
//             {
//                 // Nếu trống, vẽ 1 vòng tròn 100% màu xám. Nếu có dữ liệu, vẽ bình thường.
//                 data: isEmpty ? [1] : [datduoc, conlai],
//                 backgroundColor: isEmpty ? ["#f8f9fa"] : ["#28a745", "#e9ecef"],
//                 borderWidth: isEmpty ? 1 : 0,
//                 borderColor: "#dee2e6",
//             },
//         ],
//     },
//     options: {
//         responsive: true,
//         cutout: "80%",
//         plugins: {
//             legend: { display: !isEmpty, position: "bottom" }, // Ẩn chú thích nếu không có dữ liệu
//             tooltip: { enabled: !isEmpty }, // Tắt bảng hiện thông số khi rê chuột nếu là 0
//             title: {
//                 display: true,
//                 text: isEmpty
//                     ? "Chưa có chỉ tiêu nào"
//                     : "Thống kê trạng thái công việc",
//             },
//         },
//     },
// });
document.querySelectorAll(".chartPB").forEach((canvas) => {
    new Chart(canvas.getContext("2d"), {
        type: "doughnut",
        data: {
            labels: ["Đã hoàn thành", "Còn lại"],
            datasets: [
                {
                    data: [
                        Number(canvas.dataset.dc),
                        Number(canvas.dataset.cl),
                    ],
                    backgroundColor: ["#28a745", "#e9ecef"],
                    borderWidth: 0,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: canvas.dataset.label,
                },
                legend: { display: false },
            },
        },
    });
});
