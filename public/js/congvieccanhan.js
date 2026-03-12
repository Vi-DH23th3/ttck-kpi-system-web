$('.btn-nop-bao-cao').click(function(){
    let id = $(this).data('id');
    let ten = $(this).data('ten');
    let chitieu = $(this).data('chitieu');
    $('#display_ten_kpi').text(ten);
    $('#display_chi_tieu').text(chitieu);
    $('#input_phan_cong_cong_viec_id').val(id);
    //$('#pccv_id').val(id);
    $('#modalNopBaoCao').modal('show');
})