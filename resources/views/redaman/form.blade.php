<form id="form-redaman" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Pilih File Excel:</label>
        <input type="file" name="file" class="form-control-file" required="required">
        
        <!-- Input hidden untuk menyertakan tanggal yang dipilih -->
        <input type="hidden" name="importDate" id="importDateInput">
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Import</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Buka modal form dan set tanggal yang dipilih ke input hidden
        $(document).on('click', '.btn-form', function() {
            // Dapatkan tanggal dari input utama dan set di form modal
            let selectedDate = $('#importDate').val(); // Ambil tanggal dari input utama
            $('#importDateInput').val(selectedDate); // Set tanggal ke input hidden di form modal
            $('#modal-form').modal('show'); // Tampilkan modal
        });

        // Kirim form menggunakan AJAX
        $('#form-redaman').on('submit', function(ev) {
            ev.preventDefault(); // Mencegah pengiriman form standar

            let formData = new FormData(this); // Membuat objek FormData untuk mengirim file dan data lainnya

            $.ajax({
                url: '{{ route("redaman.import") }}', // Rute ke fungsi import di controller
                type: 'POST',
                data: formData,
                processData: false, // Mencegah jQuery memproses data
                contentType: false, // Mencegah jQuery mengatur tipe konten
                beforeSend: () => {
                    $.blockUI({ message: 'Mengimpor data...' });
                },
                complete: () => {
                    $.unblockUI();
                },
                success: (response) => {
                    if(response.success){
                        $('#modal-form').modal('hide');
                        Swal.fire("SUCCESS", response.message, 'success');
                        tbl.ajax.reload(); // Reload tabel untuk menampilkan data yang diimpor
                    } else {
                        Swal.fire("ERROR", response.message, 'error');
                    }
                },
                error: (xhr, status, error) => {
                    Swal.fire("ERROR", xhr.responseText, 'error');
                }
            });
        });
    });
</script>
