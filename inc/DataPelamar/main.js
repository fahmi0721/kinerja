$(document).ready(function(){
    Clear();
    LoadData();

    $( "#Item" ).autocomplete({
		minLength: 2,
		source: "load.php?proses=getDataBerkas"
	})
    
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/DataPelamar/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" },
			{ "data" : "NoLamaran" },
			{ "data" : "Nama" },
			{ "data" : "NoKTP" },
			{ "data" : "TTL" },
			{ "data" : "Agama" },
			{ "data" : "NoTelp" },
			{ "data" : "Pendidikan" },
			{ "data" : "Alamat" },
			{ "data" : "Aksi" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		},
	});


}

function Clear(){
	$("#Title").html("Tampil Data Pelamar");
	$("#FormData").hide();
	$("#proses").html("");
	$("#close_modal").trigger("click");
	iForm = ['IdPelamar',"Lowongan","NoLamaran","Nama","NoKTP","TemptatLahir","TglLahir","Agama","NoTelp","Email","Pendidikan","Universitas","Alamat"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val("");
		if(iForm[i] == "Agama"){
			$("#"+iForm[i]).val("..:: Pilih Agama ::..");
		}
	}
	$("#DetailData").show();
	ClearTempBerkas();
}


function Crud(IdPelamar, Status){
	Clear();
	if(IdPelamar){
		if(Status == "Ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/DataPelamar/proses.php?proses=ShowData",
				data : "IdPelamar="+IdPelamar,
				beforeSend : function(){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Tambah Data Pelamar");
					$("#FormData").show();
					$("#Lowongan").focus();
					$("#aksi").val('update');

					$("#IdPelamar").val(data.IdPelamar);
					$("#Lowongan").val(data.IdLowongan);
					$("#NoLamaran").val(data.NoLamaran);
					$("#Nama").val(data.Nama);
					$("#NoKTP").val(data.NoKTP);
					$("#TempatLahir").val(data.TempatLahir);
					$("#TglLahir").val(data.TglLahir);
					$("#Agama").val(data.Agama);
					$("#NoTelp").val(data.NoTelp);
					$("#Email").val(data.Email);
					$("#Pendidikan").val(data.Pendidikan);
					$("#Universitas").val(data.Universitas);
					$("#Alamat").val(data.Alamat);

					var Berkas = data.ListBerkas;
					if(Berkas.length > 0){
						$("#Kosong").remove();
						ID =0;
						for (var i = 0; i < Berkas.length; i++) {
							ID = (ID + i);
							$("#TempBerkas").append(
								"<tr id='TempData"+ID+"' class='TempData'>"
									+"<td>"+Berkas[i]+"</td>"
									+"<td><center><button type='button' data-toggle='tooltip' onclick=\"HapusTemp('"+ID+"')\" title='Hapus' class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></button></center></td>"
								+"</tr>"
							);
							$("#DataBerkas").append(
								"<input type='hidden' value='"+Berkas[i]+"' name='Berkas[]' id='InputData"+ID+"' class='TempData'>"
							);
							$('[data-toggle="tooltip"]').tooltip();
						}
						$("#BtnPlus").attr("data-status",1);
						$("#BtnPlus").attr("data-id",ID +1);
					}



					$("#DetailData").hide();
					StopLoad();
				},
				error :function(er){
					console.log(er)
				}
			});
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdPelamar").val(IdPelamar)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Pelamar");
		$("#FormData").show();
		$("#Lowongan").focus();
		$("#aksi").val('insert');
		$("#DetailData").hide();
	}
}

function ClearTempBerkas(){
	$("#BtnPlus").attr("data-id",0);
	$("#TempBerkas").html(
		"<tr id='Kosong'>"
			+"<td colspan='2'><center>Data Kosong</center></td>"
		+"</tr>"
	);
	$(".TempData").remove();
	$("#BtnPlus").attr("data-status",0);
}

$("#BtnPlus").click(function(){
	var ID = parseInt($(this).attr("data-id"));
	var Item = $("#Item").val();
	$("#Kosong").remove();
	$("#TempBerkas").append(
		"<tr id='TempData"+"' class='TempData'>"
			+"<td>"+Item+"</td>"
			+"<td><center><button data-toggle='tooltip' onclick=\"HapusTemp('"+ID+"')\" title='Hapus' class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></button></center></td>"
		+"</tr>"
	);
	$("#DataBerkas").append(
		"<input type='hidden' value='"+Item+"' name='Berkas[]' id='InputData"+ID+"' class='TempData'>"
	);
	$('[data-toggle="tooltip"]').tooltip();
	$(this).attr("data-id",ID +1);
	$("#Item").val("").focus();
	var CekStatus = $(".TempData").length;
	if(CekStatus > 0){
		$(this).attr('data-status',1);
	}else{
		$(this).attr('data-status',0);
	}
	
});	

function HapusTemp(ID){
	$("#TempData"+ID).remove();
	$("#InputData"+ID).remove();
	var CekStatus = $(".TempData").length;
	if(CekStatus <= 0){
		$("#TempBerkas").html(
			"<tr id='Kosong'>"
				+"<td colspan='2'><center>Data Kosong</center></td>"
			+"</tr>"
		);
		$("#BtnPlus").attr("data-status",0);
	}
}

function Validasi(aksi){
	StartLoad();
	if(aksi != "delete"){
		iForm = ["Lowongan","NoLamaran","Nama","NoKTP","TemptatLahir","TglLahir","Agama","NoTelp","Email","Pendidikan","Universitas","Alamat"];
		KodeError =1;
		for (var i = 0; i < iForm.length; i++) {
			if(iForm[i] != "Agama"){
				if($("#"+iForm[i]).val() == ""){ error("Data Pelamar", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); StopLoad(); return false; }
			}else{
				if($("#"+iForm[i]).val() == "..:: Pilih Agama ::.."){ error("Pelamar", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); StopLoad(); return false; }
			}
		}
		var CekStatus = parseInt($("#BtnPlus").attr("data-status"));
		if(CekStatus <= 0){
			error("Pelamar", KodeError, "Kelengkapan Berkas", " Belum Lengkap!"); $("#Item").focus(); StopLoad(); return false;
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
			url : "inc/DataPelamar/proses.php?proses=Crud",
			data : data,
			beforeSend : function(){
				StartLoad();
			},
			success : function(result){
				console.log(result);
				if(result == "sukses"){
					TableData.ajax.reload();
					Clear();
					sukses(aksi,"Data Pelamar",'002');
				}
				StopLoad();
			},
			error: function(er){
				console.log(er);
			}
		})
	}
}