
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi">
            <input type="hidden" name="IdPelamar" id="IdPelamar">

            <div class="form-group">
                <label class="control-label col-sm-2">Lowongan </label>
                <div class="col-sm-5">
                    <select class="form-control" id="Lowongan" name="Lowongan">
                        <option value="">..:: Pilih Lowongan ::..</option>
                        <?php 
                            $sql = "SELECT IdLowongan, NamaLowongan FROM tb_lowongan ORDER BY IdLowongan DESC";
                            $query = $db->query($sql);
                            while($dt = $query->fetch(PDO::FETCH_ASSOC)){
                                echo "<option value='".$dt['IdLowongan']."'>".$dt['NamaLowongan']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">No Lamaran</label>
                <div class="col-sm-5">
                    <input type="text" name="NoLamaran" class="form-control" id="NoLamaran" placeholder="No Lamaran">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Nama</label>
                <div class="col-sm-5">
                    <input type="text" name="Nama" class="form-control" id="Nama" placeholder="Nama">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">No KTP</label>
                <div class="col-sm-5">
                    <input type="text" name="NoKTP" class="form-control" id="NoKTP" placeholder="No KTP">
                </div>
            </div>

             <div class="form-group">
                <label class="control-label col-sm-2">TTL</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="TempatLahir" id="TempatLahir" placeholder="Tempat Lahir" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="TglLahir" id="TglLahir" placeholder="Tanggal Lahir" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Agama</label>
                <div class="col-sm-5">
                    <select class="form-control" id="Agama" name="Agama">
                        <?php 
                            $Agama = array("..:: Pilih Agama ::..","ISLAM", "KRISTEN", "BUDHA", "HINDU", "KONG HU CU");
                            foreach ($Agama as $agama) {
                                echo "<option value='".$agama."'>".$agama."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-2">No Telp/Hp</label>
                <div class="col-sm-5">
                    <input type="text" name="NoTelp" class="form-control" id="NoTelp" placeholder="No Telp/Hp">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Email</label>
                <div class="col-sm-5">
                    <input type="text" name="Email" class="form-control" id="Email" placeholder="Email">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Pendidikan</label>
                <div class="col-sm-5">
                    <input type="text" name="Pendidikan" class="form-control" id="Pendidikan" placeholder="Pendidikan Terakhir">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Universitas</label>
                <div class="col-sm-5">
                    <input type="text" name="Universitas" class="form-control" id="Universitas" placeholder="Universitas">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Kelengkapan Berkas</label>
                <div class="col-sm-5">
                   <div class="input-group">
                        <input type="text"  class="form-control" id="Item" placeholder="Nama Berkas">
                        <span class="input-group-btn"><button id="BtnPlus" data-id='0' data-status='0' type="button" class="btn btn-primary"><i class="fa fa-plus"></i></button></span>
                   </div>
                   <hr>
                   <div class="table-responsive">
                       <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Berkas</th>
                                    <th width="5%"><center>Aksi</center></th>
                                </tr>
                            </thead>
                            <tbody id="TempBerkas"></tbody>
                       </table>
                       <div id="DataBerkas"></div>
                   </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Alamat</label>
                <div class="col-sm-5">
                    <textarea name="Alamat" class="form-control" id="Alamat" placeholder="Alamat"></textarea>
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
                    <button onclick="Import()" type="button" class="btn btn-success btn-sm"><i class="fa fa-upload"></i> Upload</button>
                </p>
                <?php } ?>
            <div class="table-responsive">
                <form id="SearchForm">
                <table class="table table-striped table-bordered" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th widt='20%'>No Lamaran</th>
                            <th>Nama</th>
                            <th>No KTP</th>
                            <th>TTL</th>
                            <th>Agama</th>
                            <th>No Telp/Hp</th>
                            <th>Pendidikan</th>
                            <th>Alamat</th>
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