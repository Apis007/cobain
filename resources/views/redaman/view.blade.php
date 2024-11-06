@extends('layout')
@section('konten')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Redaman</h4>
            <div class="card-tools d-flex">
                <!-- Input tanggal untuk memilih tanggal impor -->
                <input type="date" id="importDate" name="importDate" class="form-control me-2" style="width: 180px;" placeholder="Pilih Tanggal">
                
                <!-- Tombol Import yang membuka form impor dalam modal -->
                <button class="btn btn-sm btn-success btn-form" title="Import Data">
                    <i class="fa fa-plus"></i> Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>Port</th>
                        <th>Redaman</th>
                        <th>Id Pelanggan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Paket</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal untuk Import Data -->
    <div class="modal fade" tabindex="-1" id="modal-form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Redaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Import Data -->
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@push('js')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable dengan parameter AJAX
            var tbl = new DataTable('#datatable', {
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("redaman.data") }}',
                    type: 'POST',
                    data: function (d) {
                        d.filterDate = $('#importDate').val(); // Kirim tanggal terpilih sebagai parameter
                        d._token = '{{ csrf_token() }}'; // Sertakan token CSRF
                    }
                },
                columns: [
                    { data: 'port' },
                    { data: 'redaman' },
                    { data: 'id_pelanggan' },
                    { data: 'nama' },
                    { data: 'alamat' },
                    { data: 'paket' },
                    {
                        data: 'created_at',
                        render: function (data) {
                            if (data) {
                                const options = {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric',
                                };
                                return new Date(data).toLocaleString('id-ID', options);
                            }
                            return '';
                        }
                    }
                ]
            });

            // Ketika tanggal diubah, muat ulang DataTable dengan filter baru
            $('#importDate').on('change', function() {
                tbl.ajax.reload(); // Muat ulang tabel dengan filter tanggal baru
            });

            // Ketika tombol Import diklik, pastikan tanggal dipilih dan valid
            $(document).on('click', '.btn-form', function() {
                let selectedDate = $('#importDate').val();
                let today = new Date().toISOString().split('T')[0];

                if (!selectedDate) {
                    Swal.fire("ERROR", "Silakan pilih tanggal impor terlebih dahulu!", 'error');
                    return;
                }

                if (selectedDate > today) {
                    Swal.fire("ERROR", "Urong wayae Boss!", 'error');
                    return;
                }

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
@endpush
