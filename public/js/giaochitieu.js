let defaultHtml = $("#kpi-datalist-container").html();
$("#dmcvSelector").on("input", function () {
    let val = $(this).val(); // Lấy tên công việc đang gõ/chọn
    // Tìm trong datalist xem có option nào có value khớp với input không
    let option = $("#dmcvTemplates option").filter(function () {
        return $(this).val() === val;
    });
    
    if (option.length) {
       
        $("#target_dmcv_id").val(option.data("dmcv"));
        let dmid = option.data("dmcv");
        //console.log('Tự nhập:' + dmid);
        let realName = option.data("realname");
        $.ajax({
            _token: $('meta[name="csrf-token"]').attr("content"),
            url: "/giaochitieu",
            method: "GET",
            data: { dm_cv_id: dmid },
            success: function (response) {
                $(".input-dmcv").val(response.tvdm.ten_cong_viec); //val() là hàm của jQuery để đặt giá trị cho input;
                let html = "";
                response.tvkpi.forEach(function (item) {
                    //let uniqueValue = `${item.ten_kpi} | Mã: ${item.id}`;
                    html += `<option value="${item.ten_kpi} | #${item.id}" data-id="${item.id}" 
                                        data-chitieu="${item.chi_tieu}" 
                                        data-donvi="${item.don_vi}" 
                                        data-chuky="${item.chu_ky}" data-realname="${item.ten_kpi}">
                                        Mẫu: ${item.chi_tieu} ${item.don_vi}      
                                    </option>`;
                });
                let datalistHtml = `<datalist id="kpiTemplates">${html}</datalist>`;

                $("#kpi-datalist-container").html(datalistHtml);
            },
        });
        $('.dmcv-phongban').addClass('d-none');
    }
    else{
        $("#target_dmcv_id").val(""); 
        $("#kpi-datalist-container").html(defaultHtml);
        $('.dmcv-phongban').removeClass('d-none');
    }
});
$(document).on("input", "#kpiSelector", function () {
    //Event Delegation (ủy quyền sự kiện) bằng cách bắt sự kiện từ thẻ cha hoặc document
    let val = $(this).val(); //tên
    $("#target_ten_kpi").val(val);
    var option = $("#kpiTemplates option").filter(function () {
        return this.value === val;
    });
    if (option.length) {
        let realId = option.data("id");
        let realName = option.data("realname");
        $("#target_kpi_id").val(realId);
        $("#target_chi_tieu").val(option.data("chitieu"));
        $("#target_don_vi").val(option.data("donvi"));
        $("#target_chu_ky").val(option.data("chuky"));
        $("#target_ten_kpi").val(realName);

        let $this = $(this);
        setTimeout(function() {
            $this.val(realName); 
        }, 100);
    } else {
        $("#target_kpi_id").val("");
        $("#target_ten_kpi").val(val);
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
//Trang giao chỉ tiêu bằng file
$(document).ready(function () {
    $(document).on("click", ".btn-add-user-kpi", function () {
        let index = $(this).data("index");

        $("#kpi-index").val(index);
        $("#display-index").text(index);
        $(".user-checkbox").prop("checked", false);
        $("#selectAll").click(function (e) {
            $(".user-checkbox").prop("checked", this.checked);
        });
    });
    $("#submit-user-kpi").click(function (e) {
        let index = $("#kpi-index").val();
        let td = $('td[data-kpi-users="' + index + '"]');
        let html = "";
        $(".user-checkbox:checked").each(function () {
            let userId = $(this).val();
            let userName = $(this).data("name");
            html += `
                    <div class="badge bg-info text-white p-2 d-flex align-items-center gap-1">
                        ${userName}
                        <input type="hidden" name="tasks[${index}][user_ids][]" value="${userId}">
                        <i class="bi bi-x-circle text-danger cursor-pointer" onclick="$(this).parent().remove()"></i>
                    </div>
                `;
        });
        // console.log(td);
        td.html(html);
        let offcanvasElement = document.getElementById("offcanvasAddUserKPI");
        let instance = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (instance) {
            instance.hide();
        }
    });
});
