
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdRab" id="IdRab" value="">
            <input type="hidden" name="ICabang" id="ICabang" value="">
            
            <div class='row'>
            	<div class='col-sm-6'>
            		<div class="row"><div class='col-sm-12'><h4 class='title title-form'>DATA CABANG</h4></div></div>
            		
            		<div class='form-group'>
            			<label class="control-label col-sm-3">Kode Cabang</label>
            			<div class='col-sm-9'>
            				<input type='text' id='KodeCabang' name="KodeCabang" class='form-control' placeholder="Enter Kode Cabang" data-toggle='tooltip' title="Press Enter" />
            			</div>
            		</div>

            		<div class='form-group'>
            			<label class="control-label col-sm-3">Nama Cabang</label>
            			<div class='col-sm-9'>
            				<input type='text' class='form-control' id='NamaCabang' disabled placeholder="Nama Cabang"  />
            			</div>
            		</div>

            		<div class='form-group'>
            			<label class="control-label col-sm-3">Nomor Kontrak / Addendum</label>
            			<div class='col-sm-9'>
            				<div class="input-group">
            					<span class='input-group-addon'><i class='fa fa-users'></i></span>
            					<input type="text" name="NomorKontrak" id='NomorKontrak' placeholder='Enter Nomor Kontrak' class='form-control'>
            				</div>
            			</div>
            		</div>
            	</div>

            	<div class='col-sm-6'>
            		<div class="row"><div class='col-sm-12'><h4 class='title title-form'>DATA UNIT KERJA</h4></div></div>
            		<div id='ShowUnitKerja'></div>
            		
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
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Kode Divisi</th>
                            <th>Nama Divisi</th>
                            <th>Keterangan</th>
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