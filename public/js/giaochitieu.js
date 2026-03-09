$("#kpiSelector").on("input", function () {
    let val = $(this).val();
    var option = $("#kpiTemplates option").filter(function () {
        return this.value === val;
    });

    if (option.length) {
        $("#target_kpi_id").val(option.data("id"));
        $("#target_chi_tieu").val(option.data("chitieu"));
        $("#target_don_vi").val(option.data("donvi"));
        $("#target_chu_ky").val(option.data("chuky"));
    } else {
        $("#target_kpi_id").val("");
    }
});
$("#selectAll").click(function (e) {
    $(".user-checkbox").prop("checked", this.checked);
    // $("#selectAll").click(function (e) {
    //     $(".user-checkbox").prop("checked", false);
    // });
});
$("#search-user-chitieu").on("click", function (e) {
    e.preventDefault();

    $.ajax({
        url: "/giaochitieu",
        method: "GET",
        data: {
            usersearch: $("#userSearch").val(),
        },
        success: function (response) {
            $("#giaochitieu-table-user").html(response.html);
        },
        error: function (xhr) {
            alert("Lỗi: " + xhr.status);
            console.log(xhr.responseText);
        },
    });
});
