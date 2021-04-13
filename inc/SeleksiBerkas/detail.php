
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Lowongan</h4>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Lowongan</label>
                        <div class="col-sm-9">
                           <select class="form-control" id="Lowongan" onchange="LoadLowongan(this.value)" name="IdLowongan">
                               <option value="">..:: Pilih Lowongan ::..</option>
                               <?php 
                                    $sql = "SELECT NamaLowongan, IdLowongan FROM tb_lowongan ORDER BY IdLowongan DESC";
                                    $query = $db->query($sql);
                                    while($res = $query->fetch(PDO::FETCH_ASSOC)){
                                        echo "<option value='$res[IdLowongan]'>$res[NamaLowongan]</option>";
                                    }

                               ?>
                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Penempatan</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="Penempatan" placeholder="Penempatan">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" readonly id="TglBuka" placeholder="Tanggal Buka" class="form-control">
                                <span class="input-group-addon">S/D</span>
                                <input type="text" readonly id="TglTutup" placeholder="Tanggal Tutup" class="form-control">

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea readonly class="form-control" id="Keterangan" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <h4 class="title title-form">Filter Seleksi</h4>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Usia Maksimal</label>
                        <div class="col-sm-9">
                            <input type="text" name="Usia" class="form-control" id="Usia" placeholder="Usia">
                        </div>
                    </div>

                     <div class="form-group">
                        <label class="control-label col-sm-3">Pendidikan</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-6" id="LeftPendidikan">
                                    
                                </div>
                                <div class="col-sm-6" id="RightPendidikan">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Kelengkapan Berkas</label>
                        <div class="col-sm-9">
                             <div class="row">
                                <div class="col-sm-6" id="LeftBerkas">
                                    
                                </div>
                                <div class="col-sm-6" id="RightBerkas">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
            
            <div class="pull-right">
                <button type="button" onclick="LoadData()" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Seleksi Data</button>
            </div>
            <span class="clearfix"></span>
        </form>
        <hr>
        <div id="DetailData">
            <div class='row'>                    
            <div class="col-sm-12">
                <div class="table-responsive">
                    <form id="SearchForm">
                    <table class="table table-striped table-bordered" id="TableData">
                        <thead>
                            <tr>
                                <th width="5px"><center>No</center></th>
                                <th>No Lamaran</th>
                                <th>Nama</th>
                                <th>No KTP</th>
                                <th>TTL</th>
                                <th>Usia</th>
                                <th>Agama</th>
                                <th>No Telp/Hp</th>
                                <th>Pendidkan</th>
                                <th>Alamat</th>
                                <th>Berkas</th>
                                <th width="8%"><center>Pilih</center></th>
                            </tr>
                            
                        </thead>
                    
                    </table>
                    </form>
                </div>
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