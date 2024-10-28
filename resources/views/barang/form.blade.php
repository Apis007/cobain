<form action="" method="post" id="form-barang">
    @csrf
    <input type="hidden" name="id" value="{{@$barang->id}}">
    <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" name="nama" class="form-control"  value="{{@$barang->nama}}" required>
    </div>
    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="hrg" class="form-control"  value="{{@$barang->harga}}" required>
    </div>
    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control"  value="{{@$barang->stok}}" required>
    </div>
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="desc" class="form-control">{{@$barang->deskripsi}}</textarea>
    </div>
    <div class="form-group text-end">
        <button class="btn btn-success">Simpan Data</button>
    </div>
</form>

<script>
    $('#form-barang').on('submit', function(ev){
        ev.preventDefault();
        $.ajax({
            url: '{{route("barang.save")}}', type: 'POST', data: $(this).serializeArray(), dataType:'JSON',
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