

$('[data-toggle="tooltip"]').tooltip();


$("#TglLahir, #TMTCabang, #TMTIsu").datepicker({"format": "yyyy-mm-dd", "autoclose" : true});
$('#Tahun').datepicker({
  format: "yyyy",
  weekStart: 1,
  orientation: "bottom",
  language: "{{ app.request.locale }}",
  keyboardNavigation: false,
  viewMode: "years",
  minViewMode: "years",
  autoclose: true
});

function StopLoad(){
    $(".LoadingState").hide();
}

function StartLoad(){
    $(".LoadingState").show();
}

function sukses(aksi, modul, kode_modul) {
  if (aksi == 'insert' || aksi == '0') {
    var info_sukses = "SCS-"+kode_modul+".0 : Data "+modul+" Telah Tersimpan.";
  } else if (aksi == 'update' || aksi == 'aprovel' || aksi == '1') {
    var info_sukses = "SCS-"+kode_modul+".1 : Data "+modul+" Telah Diperbaharui.";
  } else if (aksi == 'delete' || aksi == '2') {
    var info_sukses = "SCS-"+kode_modul+".2 : Data "+modul+" Telah Di Hapus.";
  }
  $("#proses").html("<div class='alert alert-success'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> "+info_sukses+"</div>");
}

function error(kode_modul, no, catatan) {
  $("#proses").html("<div class='alert alert-warning'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> ER-"+kode_modul+"."+no+" : "+catatan+"</div>");
}

function Customerror(kode_modul, no, catatan,id) {
  $("#"+id).html("<div class='alert alert-warning'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> ER-"+kode_modul+"."+no+" : "+catatan+"</div>");
}

function MessageInfo(Catatan,Id){
  $("#" + Id).html("<div class='alert alert-info'>  " + Catatan + "</div>");
}



function Customsukses(kode_modul, no, catatan, id) {
  $("#" + id).html("<div class='alert alert-success'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> SCS-" + kode_modul + "." + no + " : " + catatan + "</div>");
}

function scrolltop(){
  $("html, body").animate({
          scrollTop: 0
      }, 600);
      return false;
}

function angka(objek) {
  a = objek.value;
  b = a == undefined ? a : a.replace(/[^\d]/g, "");
  objek.value = b;
}

function ToAngka(str) {
  a = str;
  b = a == undefined ? a : a.replace(/[^\d]/g, "");
  return b;
}

function decimal(objek) {
  a = objek.value;
  b = a == undefined ? a : a.replace(/[^\d,]/g, "");
  objek.value = b;
}

function ToDecimal(str){
  a = str == undefined ? str : str.replace(/[^\d]/g, "");
  a = str.replace('.', ",");
  b = a.replace(/[^,\d]/g, "");
  return b;
}

function FormatRupiah(angka){
  if(angka != ""){
    var reverse = angka.toString().split('').reverse().join(''),
    ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return ribuan;
  }else{
    return 0;
  }
}

function AngkaRupiah(objek){
  angka = objek.value;
  var number_string = angka.toString().replace(/[^,\d]/g, ''),
  split   		= number_string.split(','),
  sisa     		= split[0].length % 3,
  rupiah     		= split[0].substr(0, sisa),
  ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  objek.value = rupiah;
}

function formatRupiah(angka, prefix){
  var number_string = angka.toString().replace(/[^,\d]/g, ''),
  split       = number_string.split(','),
  sisa        = split[0].length % 3,
  rupiah      = split[0].substr(0, sisa),
  ribuan      = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}



function in_array(needle, haystack){
  var found = 0;
  for (var i=0, len=haystack.length;i<len;i++) {
      if (haystack[i] == needle) return i;
          found++;
  }
  return -1;
}