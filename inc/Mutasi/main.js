$(document).ready(function(){
    Clear();
	LoadData();

	$("#Nik").autocomplete({
		source: "load.php?proses=GetDataPejabatInMutasi",
		select: function (event, ui) {
			$("#Nama").val(ui.item.Nama);
			$("#IdPejabat").val(ui.item.IdPejabat);
			$("#Jabatan").val(ui.item.Jabatan);
			$("#TptLahir").val(ui.item.TptLahir);
			$("#TglL").val(ui.item.TglLahir);
			$("#KelasJabatan").val(ui.item.KJ);
			$("#Nik").val(ui.item.label);
			$("#TmtMutasi").focus();
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | "+ item.Nama +"</div>").appendTo(ul); };
	$("#TmtMutasi").datepicker({"format": "yyyy-mm-dd", "autoclose" : true});
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Mutasi/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Nama" },
			{ "data" : "Nik"},
			{ "data" : "KJ"},
			{ "data" : "TmtMutasi"},
			{ "data" : "Keterangan"},
			{ "data" : "Status" ,"sClass" : "text-center", "sWidth" : "15px"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function Aprove(IdMutasi){
	jQuery("#AprovelModal").modal('show', {backdrop: 'static'});
	$("#IdM").val(IdMutasi);
	
}

function SubmitAprove(){
	if($("#Status").val() == ""){
		Customerror("Mutasi Aprovel", 1, "Status Belum dipilih",'pesan1');
		return false;
	}
	var data = $("#FormAprove").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Mutasi/proses.php?proses=Aprove",
		data : data,
		success: function(res){
			console.log(res);
			var Table = $("#TableData").DataTable();
			if(res == "sukses"){
				jQuery("#AprovelModal").modal('hide');
				sukses('update',"Mutasi",'003');
				Table.ajax.reload();
			}
		},
		error: function(er){
			console.log(er);
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Mutasi");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdMutasi","IdPejabat","Nama","Nik","TptLahir","TglL","KelasJabatan","Jabatan","TmtMutasi","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}


function IsiData(e){
	$("#IdPejabat").val(e.IdPejabat);
	$("#IdMutasi").val(e.IdMutasi);
	$("#Nama").val(e.Nama);
	$("#TptLahir").val(e.TptLahir);
	$("#TglL").val(e.TglLahir);
	$("#Nik").val(e.Nik);
	$("#KelasJabatan").val(e.KelasJabatan);
	$("#Jabatan").val(e.NamaJabatan);
	$("#TmtMutasi").val(e.TmtMutasi);
	$("#Keterangan").val(e.Keterangan);
}

function Crud(IdMutasi,Status){
	Clear();
	if (IdMutasi){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Mutasi/proses.php?proses=ShowData",
				data : "IdMutasi="+IdMutasi,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Mutasi");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					IsiData(data);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdMutasi").val(IdMutasi)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Mutasi");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#Nik").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Nik","TptLahir","TmtMutasi","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Mutasi", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Mutasi/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Mutasi",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}