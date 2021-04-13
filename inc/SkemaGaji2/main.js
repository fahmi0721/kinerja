$(document).ready(function(){
    Clear();
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Divisi/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "KodeDivisi" },
			{ "data" : "NamaDivisi" },
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
			GetDataNomorKontrakCabang(ui.item.IdCabang);

		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };

	
}

function GetDataNomorKontrakCabang(IdCabang){
	alert(IdCabang);
	$("#NomorKontrak").autocomplete({
		source: "load.php?proses=getKodeNomorKontrak&≈="+IdCabang,
		select: function (event, ui) {
			$("#NomorKontrak").val(ui.item.label);

		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.JudulKontrak + "</div>").appendTo(ul); };
}



function Clear(){
	$("#Title").html("Tampil Data Divisi");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = [".Pengupahan"];
	for (var i = 0; i < iForm.length; i++) {
		$(iForm[i]).val('');
	}
	$("#ShowItemKontrak").html(
		"<div class='alert alert-warning' role='alert'>"
			+"<b>Warning !</b> Silahkan Pilih Cabang Terlebih Dahulu."
		+"</div>"
	);

	$("#ShowDataKaryawan").html(
		"<div class='col-sm-12'>"
		+ "<div class='alert alert-warning' role='alert'>"
		+ "<b>Warning !</b> Silahkan Pilih Cabang Terlebih Dahulu."
		+ "</div>"
		+ "</div>"
	);
}

function Crud(IdDivisi,Status){
	Clear();
	if (IdDivisi){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Divisi/proses.php?proses=ShowData",
				data : "IdDivisi="+IdDivisi,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Divisi");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdDivisi").val(data.IdDivisi);
					$("#KodeDivisi").val(data.KodeDivisi);
					$("#NamaDivisi").val(data.NamaDivisi);
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
			$("#IdDivisi").val(IdDivisi)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetKodeCabang();
		$("#Title").html("Tambah Data Divisi");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaDivisi").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaDivisi","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Divisi", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Divisi/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Divisi",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}