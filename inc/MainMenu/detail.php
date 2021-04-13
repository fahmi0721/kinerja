
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdMenu" id="IdMenu" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Nama Menu</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control' name='NamaMenu' id='NamaMenu' placholder='Nama User' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Direktori</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control' name='Direktori' id='Direktori' placholder='Direktori' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Icon</label>
                <div class="col-sm-2">
                    <div class='input-group'>
                        <input type='text' data-toggle='tooltip' title='Press Enter To Show Icon' name='Icon' onkeyup='ShowIcon(event)' id='Icon' class='form-control' placeholder='ICon'>
                        <span class='input-group-addon' id='ShowIcon'><i class='fa'></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Parent Menu</label>
                <div class="col-sm-4" id='ShowParentMenu'></div>
            </div>

            
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button type="button" onclick="SubmitData()" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                    <button type="button" onclick="Clear()" class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
                </div>
            </div>
        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <p>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Menu</th>
                            <th>Direktori</th>
                            <th>Parent Menu</th>
                            <th width='5%'><center>Icon<center></th>
                            <th width='5%'><center>Status<center></th>
                            <th width="8%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                </table>
            </div>
            </div>
            
        </div> 

    </div>
    



    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>


<div class='modal fade in' id='modal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Konfirmasi Delete</h5>
</div>
<div class='modal-body'>

    <div id="proses_del"></div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitData()"><i class="fa fa-check-square"></i> &nbsp;Hapus</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="Clear()"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>

</div>
</div>
</div>
</div>