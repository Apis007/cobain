@extends('layout')
@section('konten')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Teknisi</h4>
            <div class="card-tools">
                <button class="btn btn-sm btn-success btn-form" data-url="{{route('teknisi.add')}}" title="Tambah Data"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No Hp</th>
                        <th>Alamat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
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
        var tbl = new DataTable('#datatable',{
            processing: true,
            serverSide: true,
            ajax:{
                url: '{{route("teknisi.data")}}', type:'POST'
            },
            columns:[
                {data:'nama'},
                {data:'no_hp'},
                {data:'alamat'},
                {data:'action'},
            ]
        });
        $(document).on('click','.btn-form',function(){
            let title = $(this).prop('title')??'Form';
            let url = $(this).data('url');
            let modal = $('#modal-form');
            modal.find('.modal-title').html(title);
            modal.find('.modal-body').html('<center><i class="fa fa-spinner fa-spin"></i> <i>loading...</i></center>');
            modal.modal('show');
            
            $.ajax({
                url: url, type: 'GET',
                error:(e)=>{ Swal.fire("ERRORS","Error Bro Engko Neh",'error'); setTimeout(() => { modal.modal('hide'); }, 500);  },
                success:(r)=>{
                    modal.find('.modal-body').html(r);
                }
            });
        });
        $(document).on('click','.btn-delete',function(){
            let url = $(this).data('url');
            Swal.fire({
                title: "Hapus Data ?",
                text: "Data akan dihapus secara permanen",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    return new Promise((resolve, reject)=>{
                        $.ajax({
                            url:url, type:'GET', dataType:'JSON',
                            error:(e)=>{ resolve({success:false,message:e.statusText}); },
                            success:(r)=>resolve(r)
                        })
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((r) => {
                if (r.isConfirmed) {
                    if(r.value.success){
                        tbl.ajax.reload();
                        Swal.fire('SUCCESS',r.value.message,'success');
                    }else{
                        Swal.fire('ERROR',r.value.message,'error');
                    }
                }
            });
        });
    </script>
@endpush