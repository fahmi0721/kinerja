$(document).ready(function(){
    Clear();
	LoadData();

	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
			GetUnitKerja(ui.item.IdCabang)
			
		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };
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
}



function Clear(){
	$("#Title").html("Tampil Data Rencana Anggaran Biaya");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$("#ShowUnitKerja").html(
		"<div class='form-group'>"
			+"<div class='col-sm-12'>"
				+"<div class='alert alert-info' role='alert'>"
				  +"<b>Informasi !</b> Silahkan Pilih Cabang Terlebih Dahulu."
				+"</div>"
			+"</div>"

		+"</div>"
	);
	var iForm = ["aksi","IdDivisi","KodeDivisi","NamaDivisi","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function CheckAll(posisi){
	if($("#Jabatan"+posisi).is(":checked")){
		$(".Jabatan"+posisi).prop("checked",true);
	}else{
		$(".Jabatan"+posisi).prop("checked",false);
	}
}

function Cheks(posisi){
	var Jum = $(".Jabatan"+posisi).length;
	console.log(posisi);
}

function GetUnitKerja(IdCabang) {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/Rab/proses.php?proses=GetUnitKerja",
		data : "IdCabang="+IdCabang,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(result);
			if(result['messages'] == "success"){
				var html = "<div class='form-group'>";
					html += "<label class='control-label col-sm-3'>Tenaga Kerja</label>";
					html += "<div class='col-sm-9'>";
						html += "<div class='row'>";
							for(var i=0; i < result['item'].length; i++){
								html += "<div class='col-sm-6'>";
									html += "<label class='checkbox'>";
										html += "<input type='checkbox' name='Jabatan[]' id='Jabatan"+i+"' onclick=\"CheckAll('"+i+"')\"> "+result['item'][i]['NamaJabatan'];
											for(var j=0; j < result['ItemKaryawan'][result['item'][i]['KodeJabatan']].length; j++){
												html += "<label class='checkbox'>";
													html += "<input type='checkbox' class='Jabatan"+i+"' onclick=\"Cheks('"+i+"')\" name='IdKaryawan[]' > "+result['ItemKaryawan'][result['item'][i]['KodeJabatan']][j]['NamaKaryawan'];
												html += "</label>";
											}
									html += "</label>";
								html += "</div>";
							}
						html += "</div>";
					html += "</div>";

				html += "</div>";

				$('#ShowUnitKerja').html(html);
			}else{
				$("#ShowUnitKerja").html(
					"<div class='form-group'>"
						+"<div class='col-sm-12'>"
							+"<div class='alert alert-info' role='alert'>"
							  +"<b>Informasi !</b> Data Belum Ada."
							+"</div>"
						+"</div>"

					+"</div>"
				);
			}
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	})
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