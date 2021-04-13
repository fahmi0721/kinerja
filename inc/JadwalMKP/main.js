$(document).ready(function(){
    Clear();
	LoadData();
});

function angkaJadwal(objek) {
	a = objek.value;
	b = a.replace(/[^\d]/g,"");
	var d = b.length;
	if(d > 2 || parseInt(b) > 31){
		b = b.substr(0,d-1);
	}
	objek.value = b;
}

function angkaJadwal2(objek) {
	a = objek.value;
	b = a.replace(/[^\d]/g,"");
	var d = b.length;
	if(d > 2 || parseInt(b) > 31){
		b = b.substr(0,d-1);
	}
	var old = parseInt($("#Mulai").val());
	if(old > parseInt(b)){
		b = "";
	}
	objek.value = b;
}

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/JadwalMKP/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Mulai" },
			{ "data" : "Sampai"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}



function Clear(){
	$("#Title").html("Tampil Data Jadwal MKP");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdJadwal","Mulai","Sampai"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdJadwal,Status){
	Clear();
	if (IdJadwal){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/JadwalMKP/proses.php?proses=ShowData",
				data : "IdJadwal="+IdJadwal,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Jadwal MKP");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdJadwal").val(data.IdJadwal);
					$("#Mulai").val(data.Mulai);
					$("#Sampai").val(data.Sampai);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdJadwal").val(IdJadwal)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetMulai();
		$("#Title").html("Tambah Data Jadwal MKP");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#Sampai").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Mulai", "Sampai"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Jadwal MKP", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/JadwalMKP/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Jadwal MKP",'002');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}