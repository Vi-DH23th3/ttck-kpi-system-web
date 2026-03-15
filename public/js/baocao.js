$('.btn-xem-bao-cao').click(function(){
    let noidung = $('#file_minh_chung');
    let modal = $('.body-xem-bao-cao');
    modal.html(noidung);
    $('#modalXemBaoCao').modal('show');
    noidung.removeClass('d-none');
})
$('.btn-tralaibc').click(function(){
    let id = $(this).data('tlid');
    $('.tlbc').val(id);
    $('#modalTraLai').modal('show');
})