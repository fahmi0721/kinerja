
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi">
            <input type="hidden" name="IdLowongan" id="IdLowongan">

             <div class="form-group">
                <label class="control-label col-sm-2">Nama Lowongan</label>
                <div class="col-sm-5">
                    <input type="text" name="NamaLowongan" class="form-control" id="NamaLowongan" placeholder="Nama Lowongan">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Penempatan</label>
                <div class="col-sm-5">
                    <input type="text" name="Penempatan" class="form-control" id="Penempatan" placeholder="Penempatan">
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-2">Tanggal</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="TglBuka" id="TglBuka" placeholder="Tanggal Buka" class="form-control">
                        <span class="input-group-addon">S/D</span>
                        <input type="text" name="TglTutup" id="TglTutup" placeholder="Tanggal Tutup" class="form-control">

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-5">
                    <textarea name="Keterangan" class="form-control" id="Keterangan" placeholder="Keterangan"></textarea>
                </div>
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
                <?php if($_SESSION['Level'] != "author"){ ?>
                <p>
                    <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
                </p>
                <?php } ?>
            <div class="table-responsive">
                <form id="SearchForm">
                <table class="table table-striped table-bordered" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Lowongan</th>
                            <th>Penempatan</th>
                            <th>Tanggal</th>
                            <th>Keterngan</th>
                            <th width="8%"><center>Aksi</center></th>
                        </tr>
                        
                    </thead>
                   
                </table>
                </form>
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