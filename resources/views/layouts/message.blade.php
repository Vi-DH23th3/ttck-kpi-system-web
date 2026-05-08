  @if (session('info'))
    <script>
      Swal.fire({
          icon: "info",
          title: "Thông báo",
          text: "{{ session('info') }}",
          showConfirmButton: false,      
          timer: 3000,                  
          timerProgressBar: true
      });
    </script>
  @endif

@if(session('import_errors'))
<script>
    let errorHtml = '<ul style="text-align:left;">';

    @foreach(session('import_errors') as $error)
        @if(is_array($error))
            errorHtml += `<li>
                Dòng {{ $error["row"] ?? 'N/A' }}:
                {{ is_array($error["errors"]) ? implode(", ", $error["errors"]) : $error["errors"] }}
            </li>`;
        @endif
    @endforeach

    errorHtml += '</ul>';

    Swal.fire({
        icon: "error",
        title: "Lỗi import",
        html: errorHtml,
        showConfirmButton: true
    });
</script>
@endif
    @if(session('success') || isset($success))
      <script>
        console.log('Nhân viên đang làm việc này');
     
      Swal.fire({
          icon: "success",
          title: "Thành công",
          text: "{{ session('success') }}",
          showConfirmButton: false,      
          timer: 3000,                  
          timerProgressBar: true
      });
    </script>
    @endif
    @if ($errors->any() || session('error'))
     @push('script')
    <script>
        let erHtml = '<ul style="text-align:left;">';

        @foreach ($errors->all() as $error)
            erHtml += '<li>{{ addslashes($error) }}</li>';
        @endforeach

        @if(session('error'))
            erHtml += '<li>{{ addslashes(session('error')) }}</li>';
        @endif

        erHtml += '</ul>';

        Swal.fire({
            icon: "error",
            title: "Thất bại",
            html: erHtml
        });
    </script>
    @endpush
@endif
</script>

