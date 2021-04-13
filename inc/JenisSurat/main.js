$(document).ready(function(){
    Clear();
	LoadData();
	$('#Kode').keyup(function () {
		this.value = this.value.toUpperCase();
	});
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/JenisSurat/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Kode" },
			{ "data" : "NamaJenis" },
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Jenis Surat");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdJenisSurat","Kode","NamaJenis","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	$("#Kode").prop("readonly",false);
}

function Crud(IdJenisSurat,Status){
	Clear();
	if (IdJenisSurat){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/JenisSurat/proses.php?proses=ShowData",
				data : "IdJenisSurat="+IdJenisSurat,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Jenis Surat");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdJenisSurat").val(data.IdJenisSurat);
					$("#Kode").val(data.Kode);
					$("#Kode").prop("readonly", true);
					$("#NamaJenis").val(data.NamaJenis);
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
			$("#IdJenisSurat").val(IdJenisSurat)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Jenis Surat");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#Kode").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Kode", "NamaJenis"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Jenis Surat Keluar", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/JenisSurat/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Jenis Surat Keluar",'002');
				Table.ajax.reload();
				StopLoad();
			} else if (result == "found"){
				error("Jenis Surat Keluarng", 3,  "Kode telah digunakan!"); $("#" + iForm[i]).focus();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}