<form
    action="{{ route('redaman.import') }}"
    method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Pilih File Excel:</label>
        <input type="file" name="file" class="form-control-file" required="required"></div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Import</button>
        </div>
    </div>
</form>
<script>
    $('#form-redaman').on('submit', function(ev){
        ev.preventDefault();
        $.ajax({
            url: '{{route("redaman.save")}}', type: 'POST', data: $(this).serializeArray(), dataType:'JSON',
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