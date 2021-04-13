$(document).ready(function(){
    Clear();
	LoadData();
	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
			
		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };
	$("#BerlakuMulai, #BerlakuSampai").datepicker({ "format": "yyyy-mm-dd", "autoclose": true });
});

function CalculateMounth(){
	var first = $("#BerlakuMulai").val();
	var last = $("#BerlakuSampai").val();
	if(first != "" && last != ""){
		$.ajax({
			type : "POST",
			dataType: "json",
			url : "inc/Kontrak/proses.php?proses=CalculateMounth",
			data : "first="+first+"&last="+last,
			beforeSend : function(){
				StartLoad();
			},
			success : function(data){
				console.log(data);
				var lbl = "";
				var LamaKontrak = 0;
				if(data['y'] != 0 && data['m'] != 0){
					lbl = data['y'] + " Tahun " + data['m'] + " Bulan";
					LamaKontrak = (data['y'] * 12) + data['m'];
				}else if(data['y'] != 0){
					lbl = data['y'] + " Tahun ";
					LamaKontrak = (data['y'] * 12)
				}else if(data['m'] != 0){
					lbl = data['m'] + " Bulan";
					LamaKontrak = data['m'];
				}
				$("#Selama").html(lbl);
				$("#LamaKontrak").val(LamaKontrak);
				KalkulasiUpah();
				StopLoad();
			},
			error : function(er){
				console.log(er);
			}
		});
	}
	

}


function KalkulasiUpah(){
	TotalUangMakan();
	TotalUangTransport();
	TotalUpah();
	KalkulasiBpjsKes();
	KalkulasiThrPesangon();
	KalkulasiJaminanKecelakaanKerja();
	KalkulasiJaminanKematian();
	KalkulasiJaminanHariTua();
	KalkulasiJaminanPensiun();
	KalkulasiBpjsTK();
	KalkulasiPakaian();
	KalkulasiJPTK();
	KalkulasiTagihan();
	KalkulasiTotalTagihanAll();

}


function TotalUangTransport(){
	var UangTransportPersen = $("#UangTransportPersen").val() == "" ? 0 : parseFloat($("#UangTransportPersen").val().replace(",", '.'));
	var UangTransportHari = $("#UangTransportHari").val() == "" ? 0 : parseFloat($("#UangTransportHari").val().replace(/[^\d]/g, ""));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var TotalUangTransport = (UpahPokok * (UangTransportPersen / 100)) * UangTransportHari;
	var res = FormatRupiah(Math.round(TotalUangTransport));
	$("#UangTransport").val(res);
}
/** BPJS KETENAGAKERJAAN */
function KalkulasiJaminanKecelakaanKerja(){
	var JaminanKecelakaanKerjaPersen = $("#JaminanKecelakaanKerjaPersen").val() == "" ? 0 : parseFloat($("#JaminanKecelakaanKerjaPersen").val().replace(",", '.'));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var JaminanKecelakaanKerja = UpahPokok * (JaminanKecelakaanKerjaPersen / 100);
	var res = FormatRupiah(Math.round(JaminanKecelakaanKerja));
	$("#JaminanKecelakaanKerja").val(res);
}

function KalkulasiJaminanKematian(){
	var JaminanKematianPersen = $("#JaminanKematianPersen").val() == "" ? 0 : parseFloat($("#JaminanKematianPersen").val().replace(",", '.'));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var JaminanKematian = UpahPokok * (JaminanKematianPersen / 100);
	var res = FormatRupiah(Math.round(JaminanKematian));
	$("#JaminanKematian").val(res);
}

function KalkulasiJaminanHariTua() {
	var JaminanHariTuaPersen = $("#JaminanHariTuaPersen").val() == "" ? 0 : parseFloat($("#JaminanHariTuaPersen").val().replace(",", '.'));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var JaminanHariTua = UpahPokok * (JaminanHariTuaPersen / 100);
	var res = FormatRupiah(Math.round(JaminanHariTua));
	$("#JaminanHariTua").val(res);
}

function KalkulasiJaminanPensiun() {
	var JaminanPensiunPersen = $("#JaminanPensiunPersen").val() == "" ? 0 : parseFloat($("#JaminanPensiunPersen").val().replace(",", '.'));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var JaminanPensiun = UpahPokok * (JaminanPensiunPersen / 100);
	var res = FormatRupiah(Math.round(JaminanPensiun));
	$("#JaminanPensiun").val(res);
}

function KalkulasiBpjsTK(){
	var JaminanKecelakaanKerjaPersen = $("#JaminanKecelakaanKerjaPersen").val() == "" ? 0 : parseFloat($("#JaminanKecelakaanKerjaPersen").val().replace(",", '.'));
	var JaminanKecelakaanKerja = $("#JaminanKecelakaanKerja").val() == "" ? 0 : parseFloat($("#JaminanKecelakaanKerja").val().replace(/[^\d]/g, ""));

	var JaminanKematianPersen = $("#JaminanKematianPersen").val() == "" ? 0 : parseFloat($("#JaminanKematianPersen").val().replace(",", '.'));
	var JaminanKematian = $("#JaminanKematian").val() == "" ? 0 : parseFloat($("#JaminanKematian").val().replace(/[^\d]/g, ""));

	var JaminanHariTuaPersen = $("#JaminanHariTuaPersen").val() == "" ? 0 : parseFloat($("#JaminanHariTuaPersen").val().replace(",", '.'));
	var JaminanHariTua = $("#JaminanHariTua").val() == "" ? 0 : parseFloat($("#JaminanHariTua").val().replace(/[^\d]/g, ""));

	var JaminanPensiunPersen = $("#JaminanPensiunPersen").val() == "" ? 0 : parseFloat($("#JaminanPensiunPersen").val().replace(",", '.'));
	var JaminanPensiun = $("#JaminanPensiun").val() == "" ? 0 : parseFloat($("#JaminanPensiun").val().replace(/[^\d]/g, ""));

	var Persen = (JaminanKecelakaanKerjaPersen + JaminanKematianPersen + JaminanHariTuaPersen + JaminanPensiunPersen);
	var Nilai = (JaminanKecelakaanKerja + JaminanKematian + JaminanHariTua + JaminanPensiun);
	var resPersen = Persen.toString().replace(".", ',');
	var resNilai = FormatRupiah(Math.round(Nilai));
	$("#BpjsTkPersen").val(resPersen);
	$("#BpjsTk").val(resNilai);
}
/** END BPJS KETENAGAKERJAAN */


function KalkulasiThrPesangon() {
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var Res = UpahPokok / 12;
	var res = FormatRupiah(Math.round(Res));
	$("#Thr").val(res);
	$("#Pesangon").val(res);
}

function KalkulasiPakaian() {
	var PakaianKerjaPersen = $("#PakaianKerjaPersen").val() == "" ? 0 : parseFloat($("#PakaianKerjaPersen").val().replace(",", '.'));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var PakaianKerja = (UpahPokok * (PakaianKerjaPersen / 100)) / 12;
	var res = FormatRupiah(Math.round(PakaianKerja));
	$("#PakaianKerja").val(res);
}

function KalkulasiJPTK() {
	var JasaPjtkPersen = $("#JasaPjtkPersen").val() == "" ? 0 : parseFloat($("#JasaPjtkPersen").val().replace(",", '.'));
	var Upah = $("#TotalUpah").val() == "" ? 0 : parseFloat($("#TotalUpah").val().replace(/[^\d]/g, ""));
	var JasaPjtk = Upah * (JasaPjtkPersen / 100);
	var res = FormatRupiah(Math.round(JasaPjtk));
	$("#JasaPjtk").val(res);
}

function KalkulasiBpjsKes(){
	var BpjsKesPersen = $("#BpjsKesPersen").val() == "" ? 0 : parseFloat($("#BpjsKesPersen").val().replace(",", '.'));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var BpjsKes = UpahPokok * (BpjsKesPersen / 100);
	var res = FormatRupiah(Math.round(BpjsKes));
	$("#BpjsKes").val(res);
}


function TotalUangMakan() {
	var UangMakanPersen = $("#UangMakanPersen").val() == "" ? 0 : parseFloat($("#UangMakanPersen").val().replace(",", '.'));
	var UangMakanHari = $("#UangMakanHari").val() == "" ? 0 : parseFloat($("#UangMakanHari").val().replace(/[^\d]/g, ""));
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var TotalUangMakan = (UpahPokok * (UangMakanPersen / 100)) * UangMakanHari;
	var res = FormatRupiah(Math.round(TotalUangMakan));
	$("#UangMakan").val(res);
}

function TotalUpah() {
	var UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g, ""));
	var UangMakan = $("#UangMakan").val() == "" ? 0 : parseFloat($("#UangMakan").val().replace(/[^\d]/g, ""));
	var UangTransport = $("#UangTransport").val() == "" ? 0 : parseFloat($("#UangTransport").val().replace(/[^\d]/g, ""));
	var Tunjangan = $("#Tunjangan").val() == "" ? 0 : parseFloat($("#Tunjangan").val().replace(/[^\d]/g, ""));
	var TotalUpah = UpahPokok + UangMakan + UangTransport + Tunjangan;
	var res = FormatRupiah(TotalUpah);
	$("#TotalUpah").val(res);
	
}

/** Total Tagihan / Pekerja */

function KalkulasiTagihan(){
	var TotalUpah = $("#TotalUpah").val() == "" ? 0 : parseFloat($("#TotalUpah").val().replace(/[^\d]/g, ""));
	var BpjsTk = $("#BpjsTk").val() == "" ? 0 : parseFloat($("#BpjsTk").val().replace(/[^\d]/g, ""));
	var BpjsKes = $("#BpjsKes").val() == "" ? 0 : parseFloat($("#BpjsKes").val().replace(/[^\d]/g, ""));
	var PakaianKerja = $("#PakaianKerja").val() == "" ? 0 : parseFloat($("#PakaianKerja").val().replace(/[^\d]/g, ""));
	var Thr = $("#Thr").val() == "" ? 0 : parseFloat($("#Thr").val().replace(/[^\d]/g, ""));
	var Dplk = $("#Dplk").val() == "" ? 0 : parseFloat($("#Dplk").val().replace(/[^\d]/g, ""));
	var Pesangon = $("#Pesangon").val() == "" ? 0 : parseFloat($("#Pesangon").val().replace(/[^\d]/g, ""));
	var JasaPjtk = $("#JasaPjtk").val() == "" ? 0 : parseFloat($("#JasaPjtk").val().replace(/[^\d]/g, ""));	

	var Tagihan = (TotalUpah + BpjsKes + BpjsTk + PakaianKerja + Thr + Dplk + Pesangon + JasaPjtk);
	var res = FormatRupiah(Tagihan);
	$("#Tagihan").val(res);
}

/** Total Tagihan  */
function KalkulasiTotalTagihanAll(){
	var Tagihan = $("#Tagihan").val() == "" ? 0 : parseFloat($("#Tagihan").val().replace(/[^\d]/g, ""));
	var Jumlah = $("#Jumlah").val() == "" ? 0 : parseInt($("#Jumlah").val().replace(/[^\d]/g, ""));
	var LamaKontrak = $("#LamaKontrak").val() == "" ? 0 : parseInt($("#LamaKontrak").val().replace(/[^\d]/g, ""));
	var Total = LamaKontrak * Tagihan * Jumlah;
	var res = FormatRupiah(Total);
	$("#TotalTagihan").val(res);

}

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Kontrak/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaCabang" },
			{ "data" : "JudulKontrak" },
			{ "data" : "NomorKontrak"},
			{ "data" : "Berlaku" },
			{ "data": "TotalTagihan" },
			{ "data": "Addendum", "sClass": "text-center" },
			{ "data" : "FileKontrak" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function LoadDataReload(IdKontrak){
	jQuery("#modalDetail").modal('show', { backdrop: 'static' });
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/Kontrak/proses.php?proses=DetailAddendum",
		data : "IdKontrak="+IdKontrak,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(result);
			var totData = result['data'].length;
			console.log(totData);
			if(totData > 0){
				var html = "";
				for(var i=0; i < totData; i++){
					html += "<tr>";
					html += "<td><center>" + result['data'][i]['No'] + "</center></td>";
					html += "<td>" + result['data'][i]['JudulKontrak'] + "</td>";
					html += "<td>" + result['data'][i]['NomorKontrak'] + "</td>";
					html += "<td>" + result['data'][i]['Berlaku'] + "</td>";
					html += "<td>" + result['data'][i]['TotalTagihan'] + "</td>";
					html += "<td><center>" + result['data'][i]['FileKontrak'] + "</center></td>";
					html += "<td><center>" + result['data'][i]['Aksi'] + "</center></td>";
					html += "</tr>";
				}
				$("#resultAddendum").html(html);
			}else{
				$("#resultAddendum").html("<tr><td colspan='8'><center>No data available in table</center></td></tr>");
			}
			StopLoad();

		},
		error : function(er){
			console.log(er);
		}
	})
}


function Clear(){
	$("#Title").html("Tampil Data Kontrak");
	$("#close_modal").trigger('click');
	$("#closemodal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi", "TmpFile", "IdKontrak", 'IdKontrakInduk', "IdCabang", "KodeCabang", "NamaCabang", "JenisKontrak", "JudulKontrak", "NomorKontrak", "BerlakuMulai", "BerlakuSampai", "Keterangan","FileKontrak"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	
	$("#KodeCabang").prop("readonly", false);
	$("#JenisKontrak0").prop("disabled", false);
	$("#JenisKontrak1").prop("disabled", false);
	$("#JenisKontrak0").prop("checked", true);
	$("#ShoItemKontrak").html("<tr><td colspan='7'><center>No data available in table</center></td></tr>");
	CelarListKontrak();
	$("#BtnCek").attr('data-cek',0);
	$(".DetailData").prop("readonly", false);
	$(".DetailData").prop("disabled", false);
	$(".btn-k").prop('disabled', false);
	$(".jns").prop('disabled', false);
	$("#ShowFilterJenis").html("");
}

function CelarListKontrak(){
	
	var iForm = [];
	if ($("#JenisKontrak1").is(':checked')){
		iForm = [ '#NamaList', '#Tagihan', '#TotalTagihan',  '#PakaianKerja',  '#TotalUpah', '#Tunjangan'];
	}else{
		iForm = ['#UpahPokok', '#UangMakanHari', '#UangTransportHari', '#NamaList', '#Tagihan', '#TotalTagihan', '#Pesangon', '#Thr', '#JasaPjtk', '#Dplk', '#PakaianKerja', '#JaminanPensiun', '#JaminanHariTua', '#JaminanKematian', '#JaminanKecelakaanKerja', '#BpjsTk', '#BpjsKes', '#TotalUpah', '#Tunjangan', '#UangTransport', '#UangTransportHari', '#UangMakan'];
	}
	for(var i=0; i < iForm.length; i++){
		$(iForm[i]).val("");
	}
	DefaultValue();
	KalkulasiUpah();
	$("#Jumlah").val(1);
	var obj = $("#BtnCek");
	obj.html("<i class='fa fa-plus'></i> Tambah");
	obj.attr('onclick', "TambahList()");
	if ($("#JenisKontrak0").is(':checked')) {
		$(".Detail").prop('readonly', false);
	}
	$(".Detail").prop('disabled', false);
	$(".btn-c").prop('disabled', false);
	
}

function ValidasiList(){
	var iForm = ['#BerlakuMulai', '#BerlakuSampai', '#UpahPokok',  '#UpahPokok', '#UangMakanHari', '#UangTransportHari', '#NamaList'];
	var KodeError = 1;
	for(var i =0; i < iForm.length; i++){
		if ($(iForm[i]).val() == "") { error("List Kontrak", KodeError + i, iForm[i] + " Belum Lengkap!"); $(iForm[i]).focus(); return false; }
	}	

}

function DeleteAllListKontrak(){
	$.ajax({
		type: "GET",
		url: "inc/Kontrak/proses.php",
		data: "proses=HapusAllList",
		beforeSend: function () {
			StartLoad();
		},
		success: function (result) {
			console.log(result)
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}
	})	
}

function ViewList(st){
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "inc/Kontrak/proses.php",
		data: "proses=DetailListKontrak",
		beforeSend: function () {
			StartLoad();
		},
		success: function (result) {
			console.log(result);
			var html= "";
			if (result['status'] == "success"){
				for(var i=0; i < result['item'].length; i++){
					html += "<tr>";

						html += "<td><center>" + result['item'][i]['No'] + "</center></td>";
						html += "<td>" + result['item'][i]['NamaList'] + "</td>";
						html += "<td>" + result['item'][i]['LamaKontrak'] + " Bulan</td>";
						html += "<td>" + formatRupiah(result['item'][i]['Jumlah']) + "</td>";
						html += "<td>Rp. " + formatRupiah(result['item'][i]['Tagihan']) + "</td>";
						html += "<td>Rp. " + formatRupiah(result['item'][i]['TotTagihan']) + "</td>";
						html += "<td><center>";
						html += " <a href='javascript:void(0)' class='btn btn-xs btn-success' onclick=\"DetailListKontrak('" + result['item'][i]['IdListKontrak'] + "')\" data-toggle='tooltip' title='Detail Data'><i class='fa fa-eye'></i></a>";
						if(st != '1'){
							html += " <a href='javascript:void(0)' class='btn btn-xs btn-primary btn-k' onclick=\"UpdateDataListKontrak('" + result['item'][i]['IdListKontrak'] + "')\" data-toggle='tooltip' title='Ubah Data'><i class='fa fa-edit'></i></a>";
							html += " <a href='javascript:void(0)' class='btn btn-xs btn-danger btn-k' onclick=\"DeleteDataListKontrak('" + result['item'][i]['IdListKontrak'] + "')\" data-toggle='tooltip' title='Hapus Data'><i class='fa fa-trash'></i></a>";
							html +"</center></td>";
						}
					html += "</tr>";
				}
				$("#ShoItemKontrak").html(html);
				$('[data-toggle="tooltip"]').tooltip();
				
				$("#BtnCek").attr('data-cek', 1);
			}else{
				$("#ShoItemKontrak").html("<tr><td colspan='7'><center>No data available in table</center></td></tr>");
				$("#BtnCek").attr('data-cek', 0);
			}
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function DefaultValue(){
	$("#UangMakanPersen").val('0,5');
	$("#UangTransportPersen").val('0,25');
	$("#BpjsKesPersen").val('4');
	$("#BpjsTkPersen").val('6,54');
	$("#JaminanKecelakaanKerjaPersen").val('0,54');
	$("#JaminanKematianPersen").val('0,3');
	$("#JaminanHariTuaPersen").val('3,7');
	$("#JaminanPensiunPersen").val('2');
	$("#PakaianKerjaPersen").val('60');
	$("#JasaPjtkPersen").val('10');
	$("#Jumlah").val('1');
}

function DetailListKontrak(IdListKontrak){
	IsiListKontrak(IdListKontrak);
	$(".Detail").prop('disabled',true);
	$(".btn-c").prop('disabled',true);
}

function IsiListKontrak(IdListKontrak){
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/Kontrak/proses.php?proses=ShowListKontrakSementara",
		data : "IdListKontrak="+IdListKontrak,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(result);
			$("#BerlakuMulai").val(result['Berlaku']);
			$("#BerlakuSampai").val(result['Sampai']);
			$("#UpahPokok").val(formatRupiah(result['UpahPokok']));
			$("#UangMakanPersen").val(ToDecimal(result['UangMakanPersen']));
			$("#UangMakanHari").val(ToAngka(result['UangMakanHari']));
			$("#UangMakan").val(formatRupiah(result['UangMakan']));
			$("#UangTransportPersen").val(ToDecimal(result['UangTransportPersen']));
			$("#UangTransportHari").val(ToAngka(result['UangTransportHari']));
			$("#UangTransport").val(formatRupiah(result['UangTransport']));
			$("#Tunjangan").val(formatRupiah(result['Tunjangan']));
			$("#Tunjangan").val(formatRupiah(result['Tunjangan']));
			var TotalUpah = (parseFloat(result['UpahPokok']) + parseFloat(result['UangMakan']) + parseFloat(result['UangTransport']) + parseFloat(result['Tunjangan']) );
			$("#TotalUpah").val(formatRupiah(TotalUpah));
			$("#NamaList").val(result['NamaList']);
			$("#Jumlah").val(ToAngka(result['Jumlah']));
			$("#Tagihan").val(formatRupiah(result['Tagihan']));
			$("#LamaKontrak").val(ToAngka(result['LamaKontrak']));
			var TotalTagihan = (parseFloat(result['Tagihan']) * parseInt(result['Jumlah']) * parseInt(result['Tagihan']));
			$("#TotalTagihan").val(formatRupiah(TotalTagihan));
			$("#BpjsKesPersen").val(ToDecimal(result['BpjsKesPersen']));
			$("#BpjsKes").val(formatRupiah(result['BpjsKes']));
			$("#BpjsTkPersen").val(ToDecimal(result['BpjsTkPersen']));
			$("#BpjsTk").val(formatRupiah(result['BpjsTk']));
			$("#JaminanKecelakaanKerjaPersen").val(ToDecimal(result['JaminanKecelakaanKerjaPersen']));
			$("#JaminanKecelakaanKerja").val(formatRupiah(result['JaminanKecelakaanKerja']));
			$("#JaminanKematianPersen").val(ToDecimal(result['JaminanKematianPersen']));
			$("#JaminanKematian").val(formatRupiah(result['JaminanKematian']));
			$("#JaminanHariTuaPersen").val(ToDecimal(result['JaminanHariTuaPersen']));
			$("#JaminanHariTua").val(formatRupiah(result['JaminanHariTua']));
			$("#JaminanPensiunPersen").val(ToDecimal(result['JaminanPensiunPersen']));
			$("#JaminanPensiun").val(formatRupiah(result['JaminanPensiun']));
			$("#PakaianKerjaPersen").val(ToDecimal(result['PakaianKerjaPersen']));
			$("#PakaianKerja").val(formatRupiah(result['PakaianKerja']));
			$("#Thr").val(formatRupiah(result['Thr']));
			$("#Pesangon").val(formatRupiah(result['Pesangon']));
			$("#Dplk").val(formatRupiah(result['Dplk']));
			$("#JasaPjtkPersen").val(ToDecimal(result['JasaPjtkPersen']));
			$("#JasaPjtk").val(formatRupiah(result['JasaPjtk']));
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	});
}

function UpdateDataListKontrak(IdListKontrak){
	var obj = $("#BtnCek");
	obj.html("<i class='fa fa-edit'></i> Ubah");
	obj.attr('onclick',"UpdateList('"+IdListKontrak+"')");
	IsiListKontrak(IdListKontrak);
	
}
function UpdateList(IdListKontrak){
	if (ValidasiList() != false) {
		var iData = $("#FormData").serialize();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "inc/Kontrak/proses.php?proses=UpdateListSementara",
			data: iData + "&IdListKontrak=" + IdListKontrak,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				if (result['status'] == "success") {
					alert(result['messages']);
					CelarListKontrak();
					ViewList();
				}
				StopLoad();
			},
			error: function (er) {
				console.log(er);
			}
		})
	}
}

function DeleteDataListKontrak(IdListKontrak){
	var confir = confirm("Anda yankin menghapus data ini?");
	if(confir == true){
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "inc/Kontrak/proses.php?proses=HapusList",
			data: "IdListKontrak=" + IdListKontrak,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				if(result['status'] == "success"){
					alert(result['messages']);
					CelarListKontrak();
					ViewList();
				}
				StopLoad();
			},
			error: function (er) {
				console.log(er);
			}
		})
	}
}



function TambahList(){
	if(ValidasiList() != false){
		var iData = $("#FormData").serialize();
		$.ajax({
			type : "POST",
			dataType : "json",
			url : "inc/Kontrak/proses.php?proses=CrudListSementara",
			data: iData,
			beforeSend : function(){
				StartLoad();
			},
			success : function(result){
				if(result['status'] == "success"){
					alert(result['messages']);
					CelarListKontrak();
					ViewList();
				}
				StopLoad();
			},
			error : function(er){
				console.log(er);
			}
		})
	}	
}

function InsertDataToListSementara(IdKontrak){
	$.ajax({
		type : "POST",
		url : "inc/Kontrak/proses.php?proses=InsertToListSementara",
		data : "IdKontrak="+IdKontrak,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(result);
		},
		error : function(er){
			console.log(er);
		}
	})
}

function Crud(IdKontrak,Status,TmpFile){
	Clear();
	DeleteAllListKontrak();
	if (IdKontrak){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Kontrak/proses.php?proses=ShowData",
				data : "IdKontrak="+IdKontrak,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					InsertDataToListSementara(IdKontrak);
					ViewList();
					$("#Title").html("Ubah Data Kontrak");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdKontrak").val(data.IdKontrak);
					$("#IdKontrakInduk").val(data.IdKontrakInduk);
					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#KodeCabang").prop("readonly",true);
					$("#NamaCabang").val(data.NamaCabang);
					$("#JenisKontrak" + data.JenisKontrak).prop("checked",true);
					$("#JudulKontrak").val(data.JudulKontrak);
					$("#NomorKontrak").val(data.NomorKontrak);
					$("#BerlakuMulai").val(data.BerlakuMulai);
					$("#BerlakuSampai").val(data.BerlakuSampai);
					$("#Keterangan").val(data.Keterangan);
					$("#TmpFile").val(data.FileKontrak);
					$(".jns").prop('disabled', true);
					if ($("#JenisKontrak1").is(':checked')){
						CekJenis(1);
						LoadDataIndukKontrak(data.IdKontrakInduk);
						LoadDataKontrakInduk(IdKontrak);
						$("#BerlakuSampai").prop('readonly',true);
					}
					

					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else if(Status == "detail"){
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "inc/Kontrak/proses.php?proses=ShowData",
				data: "IdKontrak=" + IdKontrak,
				beforeSend: function (data) {
					StartLoad();
				},
				success: function (data) {
					InsertDataToListSementara(IdKontrak);
					ViewList(1);
					$("#Title").html("Detail Data Kontrak");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdKontrak").val(data.IdKontrak);
					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#KodeCabang").prop("readonly", true);
					$("#NamaCabang").val(data.NamaCabang);
					$("#JenisKontrak" + data.JenisKontrak).prop("checked", true);
					$("#JudulKontrak").val(data.JudulKontrak);
					$("#NomorKontrak").val(data.NomorKontrak);
					$("#BerlakuMulai").val(data.BerlakuMulai);
					$("#BerlakuSampai").val(data.BerlakuSampai);
					$("#Keterangan").val(data.Keterangan);
					$("#TmpFile").val(data.FileKontrak);
					$(".DetailData").prop("disabled", true);
					$(".btn-k").prop('disabled',true);
					StopLoad();
				},
				error: function (er) {
					console.log(er);
				}
			})
		
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdKontrak").val(IdKontrak)
			$("#TmpFile").val(TmpFile);
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		KalkulasiUpah();
		$("#Title").html("Tambah Data Kontrak");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#KodeCabang").focus();
		$("#aksi").val("insert");

	}

}

function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["KodeCabang", "JudulKontrak", "NomorKontrak", "BerlakuMulai", "BerlakuSampai", "Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Kontrak", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	if(aksi != "delete"){
		var cekData = $("#BtnCek").attr("data-cek");
		if(cekData == '0'){
			error("Kontrak", KodeError +1, "Data Belum Lengkap!"); $("#UpahPokok").focus(); return false; 
		}
	}

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/Kontrak/proses.php?proses=Crud",
		chace: false,
		contentType : false,
		processData :false,
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Kontrak",'002');
				Table.ajax.reload();
				StopLoad();
			} else if (result == "found"){
				error("Kontrak", KodeError + 1, "Nomor Kontrak Sudah Ada!"); $("#NomorKontrak").focus(); StopLoad(); return false;
				
			} else if (result == "filenotsupport"){
				error("Kontrak", KodeError + 1, "File Yang Anda Masukkan Tidak Didukung Oleh Sistem!"); $("#FileKontrak").focus(); StopLoad(); return false;
				
			}
		},
		error : function(er){
			console.log(er);
		}
	});
}

function LoadNomorKontrakInduk(){
	$("#NomorKontrakInduk").autocomplete({
		source: "load.php?proses=NomorKontrakInduk",
		select: function (event, ui) {
			$("#NomorKontrakInduk").val(ui.item.label);
			$("#IdKontrakInduk").val(ui.item.IdKontrak);
			LoadDataKontrakInduk(ui.item.IdKontrak);

		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.JudulKontrak + "</div>").appendTo(ul); };
}
function LoadDataKontrakInduk(IdKontrak){
	$.ajax({
		type : "POST",
		dataType: "json",
		url : "inc/Kontrak/proses.php?proses=LoadDataKontrakInduk",
		data : "IdKontrak="+IdKontrak,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			$("#BerlakuSampai").val(result['BerlakuSampai']);
			$("#BerlakuSampai").prop("readonly", true);
			$("#UpahPokok").val(formatRupiah(result['UpahPokok']));
			$("#UangMakanHari").val(ToAngka(result['UangMakanHari']));
			$("#UangMakanPersen").val(ToDecimal(result['UangMakanPersen']));
			$("#UangMakan").val(formatRupiah(result['UangMakan']));
			$("#UangTransportHari").val(ToAngka(result['UangTransportHari']));
			$("#UangTransportPersen").val(ToDecimal(result['UangTransportPersen']));
			$("#UangTransport").val(formatRupiah(result['UangTransport']));
			var TotalUpah = (parseFloat(result['UpahPokok']) + parseFloat(result['UangMakan']) + parseFloat(result['UangTransport']) + parseFloat(result['Tunjangan']));
			$("#TotalUpah").val(formatRupiah(TotalUpah));
			$("#BpjsKesPersen").val(ToDecimal(result['BpjsKesPersen']));
			$("#BpjsKes").val(formatRupiah(result['BpjsKes']));
			$("#BpjsTkPersen").val(ToDecimal(result['BpjsTkPersen']));
			$("#BpjsTk").val(formatRupiah(result['BpjsTk']));
			$("#JaminanKecelekaanKerjaPersen").val(ToDecimal(result['JaminanKecelakaanKerjaPersen']));
			$("#JaminanKecelekaanKerja").val(formatRupiah(result['JaminanKecelakaanKerja']));
			$("#JaminanKematianPersen").val(ToDecimal(result['JaminanKematianPersen']));
			$("#JaminanKematian").val(formatRupiah(result['JaminanKematian']));
			$("#JaminanHariTuaPersen").val(ToDecimal(result['JaminanHariTuaPersen']));
			$("#JaminanHariTua").val(formatRupiah(result['JaminanHariTua']));
			$("#JaminanPensiunPersen").val(ToDecimal(result['JaminanPensiunPersen']));
			$("#JaminanPensiun").val(formatRupiah(result['JaminanPensiun']));
			$("#PakaianKerjaPersen").val(ToDecimal(result['PakaianKerjaPersen']));
			$("#PakaianKerja").val(formatRupiah(result['PakaianKerja']));
			$("#Thr").val(formatRupiah(result['Thr']));
			$("#Pesangon").val(formatRupiah(result['Pesangon']));
			$("#Dplk").val(formatRupiah(result['Dplk']));
			$("#JasaPjtk").val(formatRupiah(result['JasaPjtk']));
			$(".Addendum").prop("readonly", true);
			CalculateMounth();
			KalkulasiUpah();
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function CekJenis(str){
	if(str == 1){
		$("#ShowFilterJenis").html(
			"<div class='form-group'>"
				+"<label class='control-label col-sm-2'>Nomor Kontrak Induk</label>"
				+"<div class='col-sm-6'>"
					+"<input type='text' class='form-control' placeholder='Nomor Kontrak Induk' id='NomorKontrakInduk' name='NomorKontrakInduk'>"
				+"</div>"
			+"</div>"
		);
		LoadNomorKontrakInduk();
	}else{
		CelarListKontrak();
		("#BerlakuSampai").val("");
		$("#ShowFilterJenis").html("");
	}
}

function LoadDataIndukKontrak(IdKontrak){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/Kontrak/proses.php?proses=ShowData",
		data : "IdKontrak="+IdKontrak,
		beforeSend : function(){
			StartLoad();
		},
		success : function(data){
			console.log(IdKontrak);
			
			$("#NomorKontrakInduk").val(data.NomorKontrak);
		},
		error : function(er){
			console.log(er);
		}
	})
}