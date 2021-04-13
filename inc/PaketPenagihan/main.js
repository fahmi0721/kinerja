$(document).ready(function(){
    Clear();
	LoadData();

	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
			LoadDataJabatan(ui.item.IdCabang);
		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/PaketPenagihan/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaCabang" },
			{ "data" : "NamaPaket" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function LoadDataJabatan(IdCabang){
	if(IdCabang != ""){
		$.ajax({
			type : "POST",
			dataType : "json",
			url: "inc/PaketPenagihan/proses.php?proses=GetDataJabatabCabang",
			data : "IdCabang="+IdCabang,
			chace : false,
			beforeSend: function(){
				StartLoad();
			},
			success: function(result){
				console.log(result);
				if(result['messages'] == 'success'){
					var html = "<div class='form-group'>";		
						html += "<label class='col-sm-3 control-label'>Jabatan</label>";
						html += "<div class='col-sm-9'>";
						html += "<div class='row' id='Cekbox'>";
						html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
							html += "<div class='input-group'>";
								html +="<span class='input-group-addon'><input type='checkbox' id='Cekbox' onclick='CheckAll()'></span>";
								html += "<input type='text' readonly class='form-control' value='Check All'>";
							html += "</div>";

						html += "</div>";
					for(var i=0; i < result['Item'].length; i++){
						html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
							html += "<div class='input-group'>";
								html +="<span class='input-group-addon'><input type='checkbox' class='Cekbox' onclick='ViewDaftarPenagihan()' name='Jabatan[]' value='"+result['Item'][i]['Jabatan']+"'></span>";
								html += "<input type='text' class='form-control' readonly value='"+result['Item'][i]['NamaJabatan']+"'>";
							html += "</div>";

						html += "</div>";
					}
						html += "</div>";
						html += "</div>";
					html += "</div>";
					$("#ShowUnitKerja").html(html);
				}else if(result['messages'] == 'notfound'){
					$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Belum Ada Data</div>");
				}
				StopLoad();
			},
			error : function(er){
				console.log(er);
			}
		})
	}
}

function UbahLoadDataJabatan(IdCabang,datas){
	if(IdCabang != ""){
		$.ajax({
			type : "POST",
			dataType : "json",
			url: "inc/PaketPenagihan/proses.php?proses=GetDataJabatabCabang",
			data : "IdCabang="+IdCabang,
			chace : false,
			beforeSend: function(){
				StartLoad();
			},
			success: function(result){
				if(result['messages'] == 'success'){
					var html = "<div class='form-group'>";		
						html += "<label class='col-sm-3 control-label'>Jabatan</label>";
						html += "<div class='col-sm-9'>";
						html += "<div class='row' id='Cekbox'>";
						html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
							html += "<div class='input-group'>";
								html +="<span class='input-group-addon'><input type='checkbox' id='Cekbox' onclick='CheckAll()'></span>";
								html += "<input type='text' readonly class='form-control' value='Check All'>";
							html += "</div>";

						html += "</div>";
					for(var i=0; i < result['Item'].length; i++){
						if(in_array(result['Item'][i]['Jabatan'], datas)){
							console.log("okok\n");
						}
						cekd = in_array(result['Item'][i]['Jabatan'], datas) != -1 ? "checked" : "";
						html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
							html += "<div class='input-group'>";
								html +="<span class='input-group-addon'><input type='checkbox' class='Cekbox' "+cekd+" onclick='ViewDaftarPenagihan()' name='Jabatan[]' value='"+result['Item'][i]['Jabatan']+"'></span>";
								html += "<input type='text' class='form-control' readonly value='"+result['Item'][i]['NamaJabatan']+"'>";
							html += "</div>";

						html += "</div>";
					}
						html += "</div>";
						html += "</div>";
					html += "</div>";
					$("#ShowUnitKerja").html(html);
					ViewDaftarPenagihan();
				}else if(result['messages'] == 'notfound'){
					$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Belum Ada Data</div>");
				}
				StopLoad();
			},
			error : function(er){
				console.log(er);
			}
		})
	}
}

function ViewDaftarPenagihan(){
	var iData = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/PaketPenagihan/proses.php?proses=GetListPenagihan",
		data : iData,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(iData);
			if(result['messages'] == 'success'){
				var html = "";		
				var No=1;
				for(var i=0; i < result['Item'].length; i++){
					html += "<tr>";
						html += "<td><center>"+No+"</center></td>";
						html += "<td>"+result['Item'][i]['NamaKaryawan']+"</td>";
						html += "<td>"+result['Item'][i]['NamaJabatan']+"</td>";
					html += "</tr>";
					No++;
				}
					
				$("#ShowDetailList").html(html);
			}else if(result['messages'] == 'notfound'){
				$("#ShowDetailList").html("<td colspan='3'><center>No data available in table</center>");
			}else{
				$("#ShowDetailList").html("<td colspan='3'><center>No data available in table</center>");
			}
			StopLoad();
		},
		error : function(er){
			console.log(er);
			StopLoad();

		}
	})
}

function CheckAll(){
	if($("input#Cekbox").is(":checked")){
		$(".Cekbox").prop("checked", true);
	}else{
		$(".Cekbox").prop("checked", false);
	}
	ViewDaftarPenagihan();
}


function Clear(){
	$("#Title").html("Tampil Data Paket Penagihan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Pilih Cabang Terlebih Dahulu</div>");
	$("#ShowDetailList").html("<td colspan='3'><center>No data available in table</center>");
	var iForm = ["aksi","IdPaketPenagihan","NamaCabang","KodeCabang","IdCabang","NamaPaket"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdPaketPenagihan,Status){
	Clear();
	if (IdPaketPenagihan){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/PaketPenagihan/proses.php?proses=ShowData",
				data : "IdPaketPenagihan="+IdPaketPenagihan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Paket Penagihan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdPaketPenagihan").val(data.IdPaketPenagihan);
					$("#NamaCabang").val(data.NamaCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#IdCabang").val(data.IdCabang);
					$("#NamaPaket").val(data.NamaPaket);
					UbahLoadDataJabatan(data.IdCabang,data.Items);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdPaketPenagihan").val(IdPaketPenagihan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Paket Penagihan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaPaketPenagihan").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	if(aksi != "delete"){
		var iForm = ["KodeCabang","NamaPaket"];
		var KodeError = 1;
		for (var i = 0; i < iForm.length; i++) {
			if(aksi != "delete"){
				if($("#"+iForm[i]).val() == ""){ error("Paket Penagihan", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
			}
		}

		var total=0;
		$("#Cekbox input[type='checkbox']:checked").each(function(){
			//Update total
			total += parseInt($(this).data("exval"),10);
		});
		if(total <= 0){
				error("Paket Tagihan", KodeError, "Jabatan Belum Dipilih!"); 
				$('html, body').animate({
					scrollTop: $("#proses").offset().top
				}, 2000);
				return false; 
		}
	}
}


function SubmitData(){
	var aksi = $("#aksi").val();
	var data = $("#FormData").serialize();
	if(Validasi() != false){
		$.ajax({
			type : "POST",
			url : "inc/PaketPenagihan/proses.php?proses=Crud",
			data : data,
			beforeSend: function() {
				StartLoad();
			},
			success: function(result){
				console.log(result);
				var Table = $("#TableData").DataTable();
				if(result == "sukses"){
					Clear();
					sukses(aksi,"Paket Penagihan",'003');
					Table.ajax.reload();
					StopLoad();
				}
			},
			error : function(er){
				console.log(er);
			}
		});
	}

}