<?php
$subNav = array(
        "Pabrik ; pabrik.php ; #509601;",
	"Instansi ; instansi.php ; #509601;",
        "Supplier ; supplier.php ; #509601;",
        "Asuransi ; asuransi.php ; #509601;",
        "Bank ; bank.php ; #509601;",
);

set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/masterdata.php");
include_once("pages/message.php");
?>

<script type="text/javascript">
$(function() {
    load_data_pabrik();
    $('#search').keyup(function() {
        var value = $(this).val();
        load_data_pabrik('',value,'');
    });
});
function form_add() {
var str = '<div id=form_add>'+
            '<form action="" method=post id="save_barang">'+
            '<?= form_hidden('id_pabrik', NULL, 'id=id_pabrik') ?>'+
            '<table width=100% class=data-input>'+
                '<tr><td width=40%>Nama pabrik:</td><td><?= form_input('nama', NULL, 'id=nama size=40') ?></td></tr>'+
                '<tr><td>Alamat:</td><td><?= form_input('alamat', NULL, 'id=alamat size=40') ?></td></tr>'+
                '<tr><td width=40%>Email:</td><td><?= form_input('email', NULL, 'id=email size=40') ?></td></tr>'+
                '<tr><td>No. Telp:</td><td><?= form_input('telp', NULL, 'id=telp size=40') ?></td></tr>'+
            '</table>'+
            '</form>'+
            '</div>';
    $('body').append(str);
    $('#form_add').dialog({
        title: 'Tambah pabrik',
        autoOpen: true,
        width: 480,
        height: 220,
        modal: false,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_barang').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });
    var lebar = $('#pabrik').width();
    $('#pabrik').dblclick(function() {
        $('<div title="Data pabrik" id="pabrik-data"></div>').dialog({
            autoOpen: true,
            modal: true,
            width: 500,
            height: 350,
            buttons: {
                
            }
        });
    });
    
    
    $('#save_barang').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama barang tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_pabrik').val();
        $.ajax({
            url: 'models/update-masterdata.php?method=save_pabrik',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah('#nama');
                        $('input').val('');
                        load_data_pabrik('1','',data.id_pabrik);
                    } else {
                        alert_edit();
                        $('#form_add').dialog().remove();
                        load_data_pabrik($('.noblock').html());
                    }
                    
                }
            }
        });
        return false;
    });
}
$mainNav.set("home");
$('#button').button({
    icons: {
        primary: 'ui-icon-newwin'
    }
});
$('#button').click(function() {
    form_add();
});
$('#reset').button({
    icons: {
        primary: 'ui-icon-refresh'
    }
}).click(function() {
    load_data_pabrik();
    $('#search').val('');
});
$.plugin($afterSubPageShow,{ // <-- event is here
    showAlert:function(){ // <-- random function name is here (choose whatever you want)
    /* The code that will be executed */
    }
});
function load_data_pabrik(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: 'pages/pabrik-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_pabrik='+id_barg,
        success: function(data) {
            $('#result-pabrik').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_pabrik(page, search);
}

function edit_pabrik(str) {
    var arr = str.split('#');
    form_add();
    $('#form_add').dialog({ title: 'Edit pabrik' });
    $('#id_pabrik').val(arr[0]);
    $('#nama').val(arr[1]);
    $('#alamat').val(arr[2]);
    $('#email').val(arr[3]);
    $('#telp').val(arr[4]);
}

function delete_pabrik(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: 'models/update-masterdata.php?method=delete_pabrik&id='+id,
                    cache: false,
                    success: function() {
                        load_data_pabrik(page);
                        $('#alert').dialog().remove();
                    }
                });
            },
            "Cancel": function() {
                $(this).dialog().remove();
            }
        }
    });
}
$.plugin($afterSubPageShow,{ // <-- event is here
    showAlert:function(){ // <-- random function name is here (choose whatever you want)
        $('#search').focus();
    }
});
</script>
<h1 class="margin-t-0">Data pabrik</h1>
<hr>
<button id="button">Tambah Data</button>
<button id="reset">Reset</button>
<?= form_input('search', NULL, 'id=search placeholder="Search ..." class=search') ?>
<div id="result-pabrik">
    
</div>