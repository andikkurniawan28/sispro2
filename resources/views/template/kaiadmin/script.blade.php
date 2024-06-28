  <!--   Core JS Files   -->
  <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>

  <!-- jQuery Scrollbar -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

  <!-- Chart JS -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/chart.js/chart.min.js') }}"></script>

  <!-- jQuery Sparkline -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

  <!-- Chart Circle -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

  <!-- Datatables -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/datatables/datatables.min.js') }}"></script>

  <!-- Bootstrap Notify -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

  <!-- jQuery Vector Maps -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/assets/js/plugin/jsvectormap/world.js') }}"></script>

  <!-- Sweet Alert -->
  <script src="{{ asset('kaiadmin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <!-- Kaiadmin JS -->
  <script src="{{ asset('kaiadmin/assets/js/kaiadmin.min.js') }}"></script>

  <!-- Select2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <!-- Bootstrap Select JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/js/bootstrap-select.min.js"></script>

  <!-- Kaiadmin DEMO methods, don't include it in your project! -->
  <!-- <script src="{{ asset('kaiadmin/assets/js/setting-demo.js') }}"></script>
  <script src="{{ asset('kaiadmin/assets/js/demo.js') }}"></script> -->
  <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
          type: "line",
          height: "70",
          width: "100%",
          lineWidth: "2",
          lineColor: "#177dff",
          fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
          type: "line",
          height: "70",
          width: "100%",
          lineWidth: "2",
          lineColor: "#f3545d",
          fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
          type: "line",
          height: "70",
          width: "100%",
          lineWidth: "2",
          lineColor: "#ffa534",
          fillColor: "rgba(255, 165, 52, .14)",
      });
  </script>
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          @if (session('success'))
              Swal.fire({
                  icon: 'success',
                  title: 'Sukses',
                  text: '{{ session('success') }}',
              });
          @endif
          @if (session('fail'))
              Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: '{{ session('fail') }}',
              });
          @endif
          @if ($errors->any())
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: `
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
              });
          @endif
      });
  </script>

  </body>

  </html>
