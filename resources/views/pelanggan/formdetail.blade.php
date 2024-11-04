<form action="" method="post" id="form-pelanggan">
    @csrf
    <input type="hidden" name="id" value="{{@$pelanggan->id}}">
    <div class="form-group">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama" class="form-control"  value="{{@$pelanggan->nama}}" readonly>
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control"  value="{{@$pelanggan->alamat}}" readonly>
    </div>
    <div class="form-group">
        <label>Status</label>
        <input type="text" name="status" class="form-control"  value="{{@$pelanggan->status}}" readonly>
    </div>
    <div class="form-group">
        <label>Paket</label>
        <input type="text" name="paket" class="form-control"  value="{{@$pelanggan->paket}}" readonly>
    </div>
</form>

<script>
    $('#form-pelanggan').on('submit', function(ev){
        ev.preventDefault();
        $.ajax({
            url: '{{route("pelanggan.save")}}', type: 'POST', data: $(this).serializeArray(), dataType:'JSON',
            beforeSend:()=>{ $.blockUI({message:'loading bro...'}); },
            complete:()=>{ $.unblockUI(); },
            error:(e)=>{ Swal.fire("ERRORS",e.statusText,'error');},
            success:(r)=>{
                if(r.success){
                    $('.modal').modal('hide');
                    Swal.fire("SUCCESS",r.message,'success');
                    tbl.ajax.reload();
                }else{
                    Swal.fire("ERROR",r.message,'error');
                }
            }
        });
    })
</script>