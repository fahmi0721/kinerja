
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
        <span class='pull-right'><button type='button' id='BtnBack' class='btn btn-xs btn-danger' onclick='Clear()'><i class='fa fa-mail-reply'></i> Kembali</button></span>
        <span class='clearfix'></span>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdTagihan" id="IdTagihan">
            <div class='row' id='DataHeadCabang'>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Cabang</h4>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Kode Cabang</label>
                        <div class='col-sm-9'>
                            <input type='text' name='KodeCabang' id='KodeCabang' class='form-control' placeholder='Kode Cabang'>
                            <input type='hidden' name='IdCabang' id='IdCabang'>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Nama Cabang</label>
                        <div class='col-sm-9'>
                            <input type='text' id='NamaCabang' class='form-control' readonly placeholder='Nama Cabang'>
                        </div>
                    </div>

                    


                </div>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Tagihan</h4>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Judul Tagihan</label>
                        <div class='col-sm-9'>
                            <input type='text' id='JudulTagihan' name='JudulTagihan' class='form-control'  placeholder='Judul Tagihan'>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Periode</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <select class='form-control' name='Bulan' id='Bulan'>
                                    <option value=''>..:: Pilih Bulan ::..</option>
                                    <?php
                                        for($i=1; $i <= 12; $i++){
                                            $bln = sprintf("%02d",$i);
                                            echo "<option value='$bln'>".getBulan($bln)."</option>";
                                        }

                                    ?>
                                </select>
                                <span class='input-group-addon'><i class='fa fa-minus'></i></span>
                                <input type='text' placeholder='Tahun' id='Tahun' name='Tahun' class='form-control'>
                            </div>
                        </div>
                    </div>
                    <div id='ShowUnitKerja'></div>
                </div>
            </div>
            <hr>

            <div class='row'>
                <div class='col-sm-12'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Dafatr Tagihan</h4>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-md-12 col-sm-12">
                            <table class='table table-striped'>
                                <thead>
                                    <tr>
                                        <th width='5px'><center>NO</center></th>
                                        <th>NAMA KARYAWAN</th>
                                        <th>UNIT KERJA</th>
                                        <th>UPAH</th>
                                        <th> POTONGAN INDISIPLINER (ABSEN)</th>
                                    </tr>   
                                </thead>
                                <tbody id='ShowKarywanPaket'></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class='row' id='BtnSubmit'>
                <div class='col-sm-12'>
                    <center>
                        <button class='btn btn-lg btn-primary' onclick='SubmitData()' type='button'><i class='fa fa-check-square'></i> Submit</button>
                        <button class='btn btn-lg btn-danger' id='BtnBatal' onclick='Clear()' type='button'><i class='fa fa-mail-reply'></i> Batal</button>
                    </center>
                </div>
            </div>

        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <?php if($_SESSION['Level'] != "author"){ ?>
            <p id='BtnAdd'>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Cabang</th>
                            <th>Periode Tagihan</th>
                            <th>Judul Tagihan</th>
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

