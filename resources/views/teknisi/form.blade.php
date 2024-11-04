<form action="" method="post" id="form-teknisi">
    @csrf
    <input type="hidden" name="id" value="{{@$teknisi->id}}">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control"  value="{{@$teknisi->nama}}" required>
    </div>
    <div class="form-group">
        <label>No Hp</label>
        <input type="number" name="no_hp" class="form-control"  value="{{@$teknisi->no_hp}}" required>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control"  value="{{@$teknisi->alamat}}" required>
    <div class="form-group text-end">
        <button class="btn btn-success">Simpan Data</button>
    </div>
</form>

<script>
    $('#form-teknisi').on('submit', function(ev){
        ev.preventDefault();
        $.ajax({
            url: '{{route("teknisi.save")}}', type: 'POST', data: $(this).serializeArray(), dataType:'JSON',
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