
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
    <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        
        
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
                            <th rowspan='2' width="5px"><center>No</center></th>
                            <th rowspan='2'><center>PERIODE</center></th>
                            <th rowspan='2'><center>NAMA PEJABAT</center></th>
                            <th colspan='4'><center>KPI</center></th>
                            <th colspan='4'><center>KOMPETENSI</center></th>
                            <th rowspan='2'><center>FINAL SKOR</center></th>
                            <th rowspan='2' width="8%"><center>Aksi</center></th>
                        </tr>
                        <tr>
                            <th><center>BOBOT<p>[B]</p></center></th>
                            <th><center>TARGET<p>[T]</p></center></th>
                            <th><center>REALISASI<p>[R]</p></center></th>
                            <th><center>NILAI<p>([R]/[T]) * [B]</p></center></th>
                            <th><center>BOBOT<p>[B]</p></center></th>
                            <th><center>TARGET<p>[T]</p></center></th>
                            <th><center>REALISASI<p>[R]</p></center></th>
                            <th><center>NILAI<p>([R]/[T]) * [B]</p></center></th>
                        </tr>
                    </thead>
                </table>
            </div>
            </div>
            
        </div> 
        <div class="col-sm-12">
            <h5>CATATAN : </h5>
            <p class='text-muted'>- Nilai KPI = (Realisasi / Target) * Bobot</p>
            <p class='text-muted'>- Nilai Kompetensi = (Realisasi / Target) * Bobot</p>
            <p class='text-muted'>- Final Skor = (Nilai KPI * 80%) + (Nilai Kompetensi * 20%)</p>
        </div>

    </div>
    
    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>

<div class='modal fade in' id='modal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content modal-lg'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Detail Data</h5>
</div>
<div class='modal-body'>
            
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title" id="Title">DETAIL DATA PEJABAT</h3>
        </div>
        
        <div class="box-body">
            <table class='table table-striped'>
                <tr>
                    <td>NAMA</td>
                    <td>:</td>
                    <td id='Nama'></td>
                    <td>NIK</td>
                    <td>:</td>
                    <td id='NIK'></td>
                </tr>

                <tr>
                    <td>TTL</td>
                    <td>:</td>
                    <td id='TTL'></td>
                    <td>JABATAN/KJ</td>
                    <td>:</td>
                    <td id='KJ'></td>
                </tr>

                <tr>
                    <td>No HP</td>
                    <td>:</td>
                    <td id='NOHP'></td>
                    <td>ALAMAT</td>
                    <td>:</td>
                    <td id='ALAMAT'></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">PENILAIAN KINERJA PEGAWAI</h3>
        </div>
        
        <div class="box-body">
            <table class='table table-striped'>
                <thead> 
                    <tr>
                        <th width='5%'><center>NO</center></th>
                        <th><center>RENCANA KERJA BULANAN</center></th>
                        <th><center>BOBOT</center></th>
                        <th><center>TARGET(%)</center></th>
                        <th><center>REALISASI</center></th>
                        <th><center>NILAI</center></th>
                    </tr>
                </thead>     
                <tbody id='TMKP'></tbody>           
            </table>
        </div>
    </div>

    
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">PENILAIAN KOMPETENSI PEGAWAI</h3>
        </div>
        
        <div class="box-body">
            <table class='table table-striped'>
                <thead> 
                    <tr>
                        <th><center>NO</center></th>
                        <th><center>RENCANA KERJA BULANAN</center></th>
                        <th><center>BOBOT</center></th>
                        <th><center>TARGET(%)</center></th>
                        <th><center>REALISASI</center></th>
                        <th><center>NILAI</center></th>
                    </tr>
                </thead>     
                <tbody id='TKOMPTENSI'></tbody>           
            </table>
        </div>
    </div>

</div>
</div>
</div>
</div>
