$(document).ready(function(){
  // Clear();
   //LoadData();
	LoadPendidikan();
	LoadBerkas();
});

/*function LoadData(){
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

*/

function LoadData(){
	var data = $("#FormData").serialize();
	$("#TableData").DataTable({
		"ordering": false,
		"ajax": "inc/SeleksiBerkas/proses.php?proses=DetailData&" + data,
		"columns": [
			{ "data": "No" },
			{ "data": "NoLamaran" },
			{ "data": "Nama" },
			{ "data": "NoKTP" },
			{ "data": "TTL" },
			{ "data": "Usia" },
			{ "data": "Agama" },
			{ "data": "NoTelp" },
			{ "data": "Pendidikan" },
			{ "data": "Alamat" },
			{ "data": "Berkas" },
			{ "data": "Pilih" }
		],
		"destroy" : true,
		"drawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	}).ajax.reload();	
	/*$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/SeleksiBerkas/proses.php?proses=DetailData&" + data,
		data: data,
		
		success: function (data) {
			console.log(data)
		},
		error: function(er){
			console.log(er);
		}
	});*/
}

function LoadPendidikan(IdLowongan){
	var data = IdLowongan != undefined ? "IdLowongan=" + IdLowongan : "IdLowongan=nodata";
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/SeleksiBerkas/proses.php?proses=GetPendidikan",
		data : data,
		beforeSend : function(){
			StartLoad();
		},
		success: function(data){
			console.log(data);
			$("#LeftPendidikan").html("");
			$("#RightPendidikan").html("");
			
			for(var i=0; i < data.length; i++){
				var Cek = (i % 2);
				if(Cek == 0){
					$("#LeftPendidikan").append(
						"<div class='input-group' style='margin-bottom:15px'>"
							+"<input type = 'text' readonly class= 'form-control' value = '"+data[i]+"' >"
							+"<span class='input-group-addon'><input type='checkbox' value='"+data[i]+"' name='Pendidikan[]'></span>"
                        +"</div>"
					)
				}else{
					$("#RightPendidikan").append(
						"<div class='input-group' style='margin-bottom:15px'>"
						+ "<input type = 'text' readonly class= 'form-control' value = '" + data[i] +"' >"
						+ "<span class='input-group-addon'><input type='checkbox' value='" + data[i] +"' name='Pendidikan[]'></span>"
						+ "</div>"
					)
				}
			}
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	});
}

function LoadBerkas(IdLowongan) {
	var data = IdLowongan != undefined ? "IdLowongan=" + IdLowongan : "IdLowongan=nodata";
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/SeleksiBerkas/proses.php?proses=GetBerkas",
		data: data,
		beforeSend: function () {
			StartLoad();
		},
		success: function (data) {
			console.log(data);
			$("#LeftBerkas").html("");
			$("#RightBerkas").html("");

			for (var i = 0; i < data.length; i++) {
				var Cek = (i % 2);
				if (Cek == 0) {
					$("#LeftBerkas").append(
						"<div class='input-group' style='margin-bottom:15px'>"
						+ "<input type = 'text' readonly class= 'form-control' value = '" + data[i] + "' >"
						+ "<span class='input-group-addon'><input type='checkbox' value='" + data[i] +"' name='Berkas[]'></span>"
						+ "</div>"
						
					)
				} else {
					$("#RightBerkas").append(
						"<div class='input-group' style='margin-bottom:15px'>"
						+ "<input type = 'text' readonly class= 'form-control' value = '" + data[i] + "' >"
						+ "<span class='input-group-addon'><input type='checkbox' value='" + data[i] +"' name='Berkas[]'></span>"
						+ "</div>"
					)
				}
			}
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}
	});
}
function LoadLowongan(IdLowongan){
	if(IdLowongan != ""){
		$.ajax({
			type : "POST",
			dataType : "json",
			url : "inc/SeleksiBerkas/proses.php?proses=GetLowongan",
			data : "IdLowongan="+IdLowongan,
			beforeSend : function(){
				StartLoad();
			},
			success: function(data){
				console.log(data);
				$("#Penempatan").val(data.Penempatan);
				$("#TglBuka").val(data.TglBuka);
				$("#TglTutup").val(data.TglTutup);
				$("#Keterangan").val(data.Keterangan);
				LoadPendidikan(IdLowongan);
				LoadBerkas(IdLowongan);
				StopLoad();
			},
			error: function(er){
				console.log(er);
			}
		})
	}else{
		$("#Penempatan").val("-");
		$("#TglBuka").val("-");
		$("#TglTutup").val("-");
		$("#Keterangan").val("-");
	}
}