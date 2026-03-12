<!-- return về view thì Laravel sẽ render trực tiếp nội dung file Blade đó ra. 
 Lúc này, phương thức ->with('success', ...) sẽ đẩy biến success vào View Data 
 Dùng return redirect()->...->with('success', ...). Lúc này thông báo được lưu vào Session-->
      @if (session('info'))
  <script>
    Swal.fire({
        icon: "info",
        title: "Thông báo",
        text: "{{ session('info') }}",
        showConfirmButton: false,      // Bỏ nút OK
        timer: 3000,                   // Tự đóng sau 3 giây
        timerProgressBar: true
    });
  </script>
  @endif
  @if(session('import_errors'))
    <ul class="import_errors">
      @foreach(session('import_errors') as $error)
        @if(is_array($error))
          <li>
            Dòng {{ $error["row"] ?? 'N/A' }}: 
            {{ is_array($error["errors"]) ? implode(", ", $error["errors"]) : $error["errors"] }}
          </li>
        @endif
      @endforeach
    </ul>
    <script>
      let errorHtml = $(".import_errors");
      Swal.fire({
          icon: "error",
          title: "Lỗi",
          html: errorHtml,
          showConfirmButton: true,      // Bỏ nút OK
          //timer: 3000,                   // Tự đóng sau 3 giây
          timerProgressBar: true
      });
    </script>
  @endif
    @if(session('success') || isset($success))
      <script>
      Swal.fire({
          icon: "success",
          title: "Thành công",
          text: "{{ session('success') }}",
          showConfirmButton: false,      // Bỏ nút OK
          timer: 3000,                   // Tự đóng sau 3 giây
          timerProgressBar: true
      });
    </script>
    @endif
     @if(session('error'))
      <script>
      Swal.fire({
          icon: "error",
          title: "Thất bại",
          text: "{{ session('error') }}",
          showConfirmButton: true,      // Bỏ nút OK
          // timer: 3000,                   // Tự đóng sau 3 giây
          // timerProgressBar: true
          //position: 'top-end'
      });
    </script>
    @endif
</script>

