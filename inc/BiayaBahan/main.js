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

function KalkulasiBiaya(str){
	console.log(str);
	var Biaya = str.replace(/[^,\d]/g, '');
	var Ppn = (10/100) * Biaya;
	Ppn = formatRupiah(Ppn);
	$("#Ppn").val(Ppn);

	
}

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/BiayaBahan/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaCabang" },
			{ "data" : "NamaBiaya" },
			{ "data" : "Biaya"},
			{ "data" : "Berlaku"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Biaya Bahan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdBiayaBahan","IdCabang","KodeCabang","NamaCabang","NamaBiaya","Biaya","Ppn","BerlakuMulai","BerlakuSampai","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	$("#KodeCabang").prop("readonly", false);
}

function Crud(IdBiayaBahan,Status){
	Clear();
	if (IdBiayaBahan){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/BiayaBahan/proses.php?proses=ShowData",
				data : "IdBiayaBahan="+IdBiayaBahan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Biaya Bahan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdBiayaBahan").val(data.IdBiayaBahan);
					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#KodeCabang").prop("readonly",true);
					$("#NamaCabang").val(data.NamaCabang);
					$("#NamaBiaya").val(data.NamaBiaya);
					$("#Biaya").val(formatRupiah(data.Biaya));
					$("#Ppn").val(formatRupiah(data.Ppn));
					$("#BerlakuMulai").val(data.BerlakuMulai);
					$("#BerlakuSampai").val(data.BerlakuSampai);
					$("#Keterangan").val(data.Keterangan);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdBiayaBahan").val(IdBiayaBahan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Biaya Bahan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#KodeCabang").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = [ "KodeCabang", "NamaBiaya", "Biaya", "Ppn", "BerlakuMulai", "BerlakuSampai", "Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Biaya Bahan", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/BiayaBahan/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Biaya Bahan",'002');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}