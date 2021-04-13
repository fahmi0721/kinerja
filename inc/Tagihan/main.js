$(document).ready(function(){
    Clear();
	LoadData();
	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
			LoadDataPaket(ui.item.IdCabang);
		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Tagihan/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaCabang" },
			{ "data" : "Periode" },
			{ "data" : "JudulTagihan"},
			{ "data" : "Aksi", "sClass" : "text-center", "sWidth" : "10%" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}




function LoadDataPaket(IdCabang,IdPaketPenagihan){
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/Tagihan/proses.php?proses=GetDataPaket",
		data : "IdCabang="+IdCabang,
		chace : false,
		beforeSend: function(){
			StartLoad();
		},
		success: function(result){
			if(result['messages'] == 'success'){
				var html = "<div class='form-group'>";		
					html += "<label class='col-sm-3 control-label'>Paket Penagihan</label>";
					html += "<div class='col-sm-9'>";
					html += "<select class='form-control' onchange=\"GetKaryawanPaket(this.value)\" name='IdPaketPenagihan' id='Paket'>";
					html += "<option value=''>..:: Pilih Paket ::..</option>";
				for(var i=0; i < result['Item'].length; i++){
					html += "<option value='"+result['Item'][i]['IdPaketPenagihan']+"'>"+result['Item'][i]['NamaPaket']+"</option>";
				}
					html += "</select>";
					html += "</div>";
				html += "</div>";
				$("#ShowUnitKerja").html(html);
				if(IdPaketPenagihan){
					$("#Paket").val(IdPaketPenagihan);
				}
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


function UbahLoadDataPaket(IdCabang,IdPaketPenagihan,IdTagihan){
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/Tagihan/proses.php?proses=GetDataPaket",
		data : "IdCabang="+IdCabang,
		chace : false,
		beforeSend: function(){
			StartLoad();
		},
		success: function(result){
			if(result['messages'] == 'success'){
				var html = "<div class='form-group'>";		
					html += "<label class='col-sm-3 control-label'>Paket Penagihan</label>";
					html += "<div class='col-sm-9'>";
					html += "<select class='form-control' disabled onchange=\"UbahGetKaryawanPaket(this.value,'"+IdTagihan+"')\" name='IdPaketPenagihan' id='Paket'>";
					html += "<option value=''>..:: Pilih Paket ::..</option>";
				for(var i=0; i < result['Item'].length; i++){
					html += "<option value='"+result['Item'][i]['IdPaketPenagihan']+"'>"+result['Item'][i]['NamaPaket']+"</option>";
				}
					html += "</select>";
					html += "</div>";
				html += "</div>";
				$("#ShowUnitKerja").html(html);
				if(IdPaketPenagihan){
					$("#Paket").val(IdPaketPenagihan);
				}
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

function GetKaryawanPaket(IdPaket){
	var iData = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/Tagihan/proses.php?proses=GetDataKaryawanPaket",
		data : iData,
		chace : false,
		beforeSend: function(){
			StartLoad();
			console.log(iData);

		},
		success: function(result){
			console.log(result);
			if(result['messages'] == 'success'){
				var html = "";	
				var No=1;
				for(var i=0; i < result['Item'].length; i++){
					html += "<tr>";
						html += "<td width='5px' class='text-center'>"+No+"</td>";
						html += "<td>"+result['Item'][i]['NamaKaryawan']+"</td>";
						html += "<td>"+result['Item'][i]['NamaJabatan']+"</td>";
						html += "<td id='Upah"+i+"' data-id='"+result['Item'][i]['GajiPokok']+"'>Rp. "+FormatRupiah(result['Item'][i]['GajiPokok'])+"</td>";
						html += "<td>";
							html += "<div class='input-group'>";
								html += "<input type='hidden' name='IdKaryawan[]' value='"+result['Item'][i]['IdKaryawan']+"' class='form-control'>";
								html += "<span class='input-group-addon'><i class='fa fa-percent'></i></span>";
								html += "<input type='text' class='form-control' onkeyup=\"decimal(this); KalkulasiPotongan("+i+")\" placeholder='Persentase Potongan' name='PotonganPercent[]' id='PotonganPercent"+i+"' class='form-control'>";
								html += "<span class='input-group-addon'>Rp. </span>";
								html += "<input type='text' class='form-control' readonly name='Potongan[]' placeholder='Potongan' id='Potongan"+i+"' class='form-control'>";

							html += "</div>";
						html += "</td>";
					html += "</tr>";
					No++;
				}
				$("#ShowKarywanPaket").html(html);
			}else if(result['messages'] == 'notfound'){
				$("#ShowKarywanPaket").html("<div class='alert alert-info' role='alert'>No data avalible in table</div>");
			}
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function KalkulasiPotongan(posisi){
	var persentase = $("#PotonganPercent"+posisi).val();
	persentase = persentase == "" ? 0 : $("#PotonganPercent"+posisi).val().replace(",",".");
	persentase = parseFloat(persentase);
	var Upah = $("#Upah"+posisi).attr("data-id");
	console.log(persentase);
	Upah = parseFloat(Upah);
	var Potongan =Math.round((persentase/100) *Upah);
	Potongan = Potongan.toString().replace(".",",");
	$("#Potongan"+posisi).val(formatRupiah(Potongan));
}

function UbahGetKaryawanPaket(IdPaket,IdTagihan,Status){
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/Tagihan/proses.php?proses=GetDataKaryawanPaketUbah",
		data : "IdPaket="+IdPaket+"&IdTagihan="+IdTagihan,
		chace : false,
		beforeSend: function(){
			StartLoad();
		},
		success: function(result){
			console.log(result);
			if(result['messages'] == 'success'){
				var html = "";	
				var No=1;
				var st = Status == "ubah" ? "" : "disabled";
				for(var i=0; i < result['Item'].length; i++){
					var ptg = result['Item'][i]['Potongan'] == 0 ? 0  : FormatRupiah(result['Item'][i]['Potongan']);
					var ptgPercent = result['Item'][i]['PotonganPercent'] == 0 ? 0  : result['Item'][i]['PotonganPercent'].toString().replace('.','.');
					html += "<tr>";
						html += "<td width='5px' class='text-center'>"+No+"</td>";
						html += "<td>"+result['Item'][i]['NamaKaryawan']+"</td>";
						html += "<td>"+result['Item'][i]['NamaJabatan']+"</td>";
						html += "<td id='Upah"+i+"' data-id='"+result['Item'][i]['GajiPokok']+"'>Rp. "+FormatRupiah(result['Item'][i]['GajiPokok'])+"</td>";
						html += "<td>";
							html += "<div class='input-group'>";
								html += "<input type='hidden' name='IdDaftarTagihan[]' value='"+result['Item'][i]['IdDaftarTagihan']+"' class='form-control'>";
								html += "<input type='hidden' name='IdKaryawan[]' value='"+result['Item'][i]['IdKaryawan']+"' class='form-control'>";
								html += "<span class='input-group-addon'><i class='fa fa-percent'></i></span>";
								html += "<input type='text' "+st+" class='form-control prPotongn' onkeyup=\"decimal(this); KalkulasiPotongan("+i+")\" placeholder='Persentase Potongan' value='"+ptgPercent+"' name='PotonganPercent[]' id='PotonganPercent"+i+"' class='form-control'>";
								html += "<span class='input-group-addon'>Rp. </span>";
								html += "<input type='text' class='form-control' readonly name='Potongan[]' placeholder='Potongan' id='Potongan"+i+"' value='"+ptg+"' class='form-control'>";

							html += "</div>";
						html += "</td>";
					html += "</tr>";
					No++;
				}
				$("#ShowKarywanPaket").html(html);
			}else if(result['messages'] == 'notfound'){
				$("#ShowKarywanPaket").html("<tr><td colspan='5' class='text-center'>No data avalible in table</td<</tr>");
			}
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}


function LoadDetail(IdTagihan){
	jQuery("#modalDetail").modal('show', {backdrop: 'static'});
	var html = "<div class='row'>";
		html += "<div class='col-sm-12'><h4 class='title title-form'>Data Cabang</h4></div>";
		html += "</div>";

	$("#DetailModal").html(html);
}


function Clear(){
	$("#Title").html("Tampil Data Daftar Tagihan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#DetailDataKaryawan").hide();
	$("#DataHeadCabang").show();
	$("#aksi").val("");
	$("#BtnBack").hide();
	$("#KodeCabang").prop("disabled",false);
	$("#ShowUnitKaryawan").html("");
	$("#BtnSubmit").show();
	$("#Bulan").prop("disabled",false);
	$("#Tahun").prop("disabled",false);
	$("#JudulTagihan").prop("disabled",false);
	$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Silahkan Lengkapi Form Cabang</div>");
	$("#ShowKarywanPaket").html("<tr><td colspan='5' class='text-center'>No data avalible in table</td<</tr>");
	var iForm = ["aksi","IdTagihan","KodeCabang","IdCabang","NamaCabang","JudulTagihan", "Bulan","Tahun"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}


function Crud(IdTagihan,Status){
	if (IdTagihan){
		if(Status == "ubah"){
			Clear();
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Tagihan/proses.php?proses=ShowData",
				data : "IdTagihan="+IdTagihan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#Title").html("Ubah Data Tagihan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdTagihan").val(data.IdTagihan);
					$("#JudulTagihan").val(data.JudulTagihan);
					UbahLoadDataPaket(data.IdCabang,data.IdPaketPenagihan,IdTagihan);
					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#KodeCabang").prop("disabled",true);
					$("#NamaCabang").val(data.NamaCabang);
					UbahGetKaryawanPaket(data.IdPaketPenagihan,IdTagihan,Status);
					$("#Tahun").val(data.Tahun);
					$("#Bulan").val(data.Bulan);
					
					
					
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else if(Status == "detail"){
			Clear();
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Tagihan/proses.php?proses=ShowData",
				data : "IdTagihan="+IdTagihan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#Title").html("Detail Data Tagihan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdTagihan").val(data.IdTagihan);
					$("#JudulTagihan").val(data.JudulTagihan);
					UbahLoadDataPaket(data.IdCabang,data.IdPaketPenagihan,IdTagihan);
					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#KodeCabang").prop("disabled",true);
					$("#NamaCabang").val(data.NamaCabang);
					UbahGetKaryawanPaket(data.IdPaketPenagihan,IdTagihan,Status);
					$("#Tahun").val(data.Tahun);
					$("#Bulan").val(data.Bulan);
					$("#Bulan").prop("disabled",true);
					$("#Tahun").prop("disabled",true);
					$("#JudulTagihan").prop("disabled",true);
					$("#BtnSubmit").hide();
					$("#BtnBack").show();

					
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdTagihan").val(IdTagihan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		Clear();
		$("#Title").html("Tambah Data Daftar Tagihan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#KodeCabang").focus();
		$("#aksi").val("insert");
	}

}



function Validasi() {
	var aksi = $("#aksi").val();
	var iForm = ["KodeCabang","IdCabang","NamaCabang","JudulTagihan", "Bulan","Tahun"];
	
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Daftar Tagihan", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); $('html, body').animate({
				scrollTop: $("#proses").offset().top
			}, 2000); return false; }
		}
	}
	
}



function SubmitData(){
	var aksi = $("#aksi").val();
	if(Validasi() != false){
		var data = $("#FormData").serialize();
		$.ajax({
			type : "POST",
			url : "inc/Tagihan/proses.php?proses=Crud",
			data : data,
			beforeSend: function() {
				StartLoad();
			},
			success: function(result){
				console.log(result);
				var Table = $("#TableData").DataTable();
				if(result == "sukses"){
					Clear();
					Table.ajax.reload();
					sukses(aksi,"Tagihan",'003');
					StopLoad();
				}
			},
			error : function(er){
				console.log(er);
			}
		});
	}
	

}