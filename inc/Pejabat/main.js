$(document).ready(function(){
    Clear();
	LoadData();

	$("#KelasJabatan").autocomplete({
		source: "load.php?proses=getKelasJabatan",
		select: function (event, ui) {
			$("#KelasJabatan").val(ui.item.label);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };

	$("#Jabatan").autocomplete({
		source: "load.php?proses=getJabatanPejabat",
		select: function (event, ui) {
			$("#IdJabatan").val(ui.item.IdJabatan);
			$("#Jabatan").val(ui.item.label);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Pejabat/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Nama" },
			{ "data" : "Nik"},
			{ "data" : "NoHp"},
			{ "data" : "TTL"},
			{ "data" : "KJ"},
			{ "data" : "Jabatan"},
			{ "data" : "Alamat"},
			{ "data" : "Foto","sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function GetNik(){
	$("#Nik").val("");
	var TglLahir = $("#TglLahir").val();
	if(TglLahir == ""){ error("Pejabat", 1, "Tanggal Lahir Belum Lengkap!"); $("#TglLahir").focus(); return false; }
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/Pejabat/proses.php?proses=GetNik",
		data : "TglLahir="+TglLahir,
		success: function(res){
			console.log(res)
			if(res.msg == "sukses"){
				$("#Nik").val(res.NIK);
			}
		},
		error : function(er){
			console.log()
		}
	})
}

function ShowFoto(foto){
	jQuery("#modalDetail").modal('show', {backdrop: 'static'});
	$("#DetailFoto").html("<center><img src='img/Pejabat/"+foto+"' class='img-responsive'></center>");
}

function Clear(){
	$("#Title").html("Tampil Data Pejabat");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdPejabat","Nama","Titles","Nik","TptLahir","TglLahir","NoHp","KelasJabatan","Jabatan","IdPejabat","Alamat","Foto"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	$("#JKL").prop("checked",true);
	clearNIK();
}

function StrToUpper(e){
	a = e.value;
	a = a.toUpperCase();
	e.value = a;
}

function clearNIK(){
	$("#TglLahir").prop('readonly',false);
	$("#BtnNik > button").removeAttr('disabled');
}

function IsiData(e){
	$("#IdPejabat").val(e.IdPejabat);
	$("#Nama").val(e.Nama);
	$("#Titles").val(e.Title);
	$("#TptLahir").val(e.TptLahir);
	$("#TglLahir").val(e.TglLahir);
	$("#Nik").val(e.Nik);
	var JK = e.JK;
	if(JK != null){
		JK = JK.substr(0,1);
		$("#JK"+JK).prop("checked",true);
	}
	$("#NoHp").val(e.NoHP);
	$("#KelasJabatan").val(e.KelasJabatan);
	$("#Jabatan").val(e.NamaJabatan);
	$("#IdJabatan").val(e.IdJabatan);
	$("#Alamat").val(e.Alamat);
	$("#TmpFoto").val(e.Foto);
	$("#TglLahir").prop('readonly',true);
	$("#BtnNik > button").attr('disabled', true);
}

function Crud(IdPejabat,Status){
	Clear();
	if (IdPejabat){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Pejabat/proses.php?proses=ShowData",
				data : "IdPejabat="+IdPejabat,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Pejabat");
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
			$("#IdPejabat").val(IdPejabat)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Pejabat");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#Nama").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Nama","Titles","Nik","TptLahir","TglLahir","NoHp","KelasJabatan","Jabatan","Alamat"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Pejabat", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/Pejabat/proses.php?proses=Crud",
		processData: false,
		contentType: false,
		cache : false,
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Pejabat",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}