$(document).ready(function(){
   Clear();
   LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Lowongan/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" },
			{ "data" : "NamaLowongan" },
			{ "data" : "Penempatan" },
			{ "data" : "Periode" },
			{ "data" : "Keterangan" },
			{ "data" : "Aksi" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		},
	});	

}


function Clear(){
	$("#Title").html("Tampil Data Lowongan");
	$("#FormData").hide();
	$("#proses").html("");
	$("#close_modal").trigger("click");
	iForm = ['IdLowongan',"NamaLowongan","Penempatan","TglBuka","TglTutup","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val("");
	}
	$("#DetailData").show();
}

function Crud(IdLowongan, Status){
	Clear();
	if(IdLowongan){
		if(Status == "Ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Lowongan/proses.php?proses=ShowData",
				data : "IdLowongan="+IdLowongan,
				beforeSend : function(){
					StartLoad();
				},
				success : function(data){
					$("#Title").html("Ubah Data Lowongan");
					$("#FormData").show();
					$("#NamaLowongan").focus();
					$("#aksi").val("update");

					$("#IdLowongan").val(data.IdLowongan);
					$("#NamaLowongan").val(data.NamaLowongan);
					$("#Penempatan").val(data.Penempatan);
					$("#TglBuka").val(data.TglBuka);
					$("#TglTutup").val(data.TglTutup);
					$("#Keterangan").val(data.Keterangan);
					$("#DetailData").hide();
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}

			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdLowongan").val(IdLowongan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Lowongan");
		$("#FormData").show();
		$("#NamaLowongan").focus();
		$("#aksi").val("insert");
		$("#DetailData").hide();
	}
}

function Validasi(aksi){
	StartLoad();
	if(aksi != "delete"){
		iForm = ["NamaLowongan","Penempatan","TglBuka","TglTutup","Keterangan"];
		var KodeError = 1;
		for (var i = 0; i < iForm.length; i++) {
			if($("#"+iForm[i]).val() == ""){ error("Lowongan", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); StopLoad(); return false; }
		}
	}
}

function SubmitData(){
	var aksi = $("#aksi").val();
	if(Validasi(aksi) != false){
		var data = $("#FormData").serialize();
		var TableData = $("#TableData").DataTable();
		$.ajax({
			type : "POST",
			url : "inc/Lowongan/proses.php?proses=Crud",
			data : data,
			beforeSend : function(){
				StartLoad();
			},
			success : function(result){
				console.log(result);
				if(result == "sukses"){
					TableData.ajax.reload();
					Clear();
					sukses(aksi,"Lowongan",'001');
				}
				StopLoad();
			},
			error: function(er){
				console.log(er);
			}
		})
	}
}