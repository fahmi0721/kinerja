$(document).ready(function(){
    Clear();
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/KelasJabatan/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "KelasJabatan" },
			{ "data" : "TKO"},
			{ "data" : "TKP"},
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function StrToUpper(e){
	a = e.value;
	a = a.toUpperCase();
	e.value = a;
}

function Clear(){
	$("#Title").html("Tampil Data Kelas Jabatan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdKelasJabatan","KelasJabatan","TKO","TKP","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function IsiData(data){
	$("#IdKelasJabatan").val(data.IdKelasJabatan);
	$("#KelasJabatan").val(data.KelasJabatan);
	$("#TKO").val(formatRupiah(data.TKO));
	$("#TKP").val(formatRupiah(data.TKP));
	$("#Keterangan").val(data.Keterangan);
}

function Crud(IdKelasJabatan,Status){
	Clear();
	if (IdKelasJabatan){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/KelasJabatan/proses.php?proses=ShowData",
				data : "IdKelasJabatan="+IdKelasJabatan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Kelas Jabatan");
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
			$("#IdKelasJabatan").val(IdKelasJabatan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Kelas Jabatan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#KelasJabatan").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["KelasJabatan","TKO","TKP","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Kelas Jabatan", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/KelasJabatan/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Kelas Jabatan",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}