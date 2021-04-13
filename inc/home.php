<?php 
$sql = "SELECT * FROM tb_cabang ORDER BY IdCabang DESC";
$query = $db->query($sql);
$row = $query->rowCount();
if($row > 0){
?>
<div class="row">
    <?php
        $color = array("blue","purple","orange","red","yellow","green");
        $i=0;
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $tot = $db->query("SELECT COUNT(IdCabang) as tot FROM tb_karyawan WHERE IdCabang = '$res[IdCabang]' GROUP BY IdCabang")->fetch(PDO::FETCH_ASSOC);
            $tot = !empty($tot['tot']) ? $tot['tot'] : 0;
    ?>
    <div class="col-lg-4 col-xs-12">
		<div class="small-box bg-<?php echo $color[$i] ?>">
            <div class="inner">
              <h3><?php echo $tot; ?></h3>

              <p><?php echo $res['NamaCabang']; ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="index.php?page=Karyawan&Filter=<?php echo $res['NamaCabang'] ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
	</div>
        <?php 
        $i++;
        if($i == 6){
            $i=0;
        }
    } ?>
</div>
<?php } ?>