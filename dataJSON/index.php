<?php
if (preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])) {
  exit;
}
?>
<div class="row btn-klasifikasi">
  <div class="col-md-3 mb-4">
    <a href="<?php echo $link_back; ?>"  class="btn btn-custom btn-block master <?php echo $act != "laporan" ?  "active" : " " ?>">
      <i class="fas fa-inbox"></i>
      <p style="margin-bottom:0px; color: #6c757d">
        Master
      </p>  
    </a>
  </div>

  <div class="col-md-3 mb-4">
    <a href="<?php echo $link_back; ?>&act=laporan"  class="btn btn-custom btn-block laporan <?php echo $act != "laporan" ?  " " : "active" ?>">
    <i class="fas fa-envelope-open-text"></i>
    <p style="margin-bottom:0px;color: #6c757d">
        Laporan
      </p>
    </a>
  </div>
</div>
<?php
$con = $db_result;
switch ($act) {

  default:

    $get_klasifikasi = mysqli_query($db_result, "SELECT
    *,
    ( SELECT COUNT(db_kategori.id) FROM db_kategori WHERE db_kategori.klasifikasi_id = db_klasifikasi.id AND db_kategori.hapus = 0 ) as 'jumlah_kategori'
  FROM
    db_klasifikasi 
  WHERE
    hapus=\"0\" ");
    $ht_klasifikasi = mysqli_num_rows($get_klasifikasi);
    if ($ht_klasifikasi > 0) {
      while ($data_klasifikasi = mysqli_fetch_array($get_klasifikasi)) {
        $no++;
        $nama = $data_klasifikasi['nama'];
        $kode = $data_klasifikasi['kode'];
        $id = $data_klasifikasi['id'];
        $jumlah_kategori = $data_klasifikasi['jumlah_kategori'];

        $Subt .= " <tr>
        <td>$no</td>
        <td>$kode</td>
        <td>$nama</td>
        <td><a href=\"$link_back&act=kategori&gid=$id\" class=\"btn btn-small btn-primary\">$jumlah_kategori</a></td>                                                         
        <td>
        <div class=\"row ml-3\">
        <p data-placement=\"top\" data-toggle=\"tooltip\" title=\"Edit\"><button class=\"btn btn-success btn-sm mr-2 edit_btn\" data-title=\"Edit\" data-toggle=\"modal\" data-target=\"#mainModal\" data-id=\"$id\" data-kode=\"$kode\" data-nama=\"$nama\"><i class=\"fas fa-pencil-alt\"></i></button></p>
        <p data-placement=\"top\" data-toggle=\"tooltip\" title=\"Delete\"><a class=\"btn btn-danger btn-sm text-light\" data-title=\"Delete\" onClick=\"return confirm('Yakin Hapus Data?')\" href=\"$link_back&act=delete&id=$id\" ><i class=\"fas fa-trash-alt\"></i></a></p></td>
        </div>
        </tr>";
      }
    }
?>
    <!-- awal konten -->

    <div class="col-md-12 mt-4" id="aset">

      <div class="icon-add" data-toggle="modal" data-target="#mainModal" style="display: block">
        <i data-toggle="tooltip" title="Add Data" class="fas fa-plus" id="add_btn"></i>
      </div>

      <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" id="mainForm" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                  <label>Kode</label>
                  <input type="text" name="kode" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Nama Klasifikasi</label>
                  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="nama" required></textarea>
                </div>
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Klasifikasi</th>
              <th>Nama Klasifikasi</th>
              <th>Kategori</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
            <?php echo $Subt; ?>
          </tbody>
        </table>

      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        $("#add_btn").click(function() {
          let url = "<?php echo $link_back ?>" + "&act=store"
          $("#mainForm").attr('action', url)
          $("#modal_title").html('Tambah Data');
          $('[name=kode]').val('')
          $('[name=nama]').val('')
        })
        $(".edit_btn").click(function() {
          let url = "<?php echo $link_back ?>" + "&act=update"
          $("#mainForm").attr('action', url)
          $("#modal_title").html('Edit Data');
          $('[name="kode"]').val($(this).data('kode'))
          $('[name="nama"]').val($(this).data('nama'))
          $("#id").val($(this).data('id'))
        })
      }, false);
    </script>

<?php
    break;

  case "store":
    if (count($_POST) > 0) {
      foreach ($_POST as $pkey => $pvalue) {
        $post1 = mysqli_escape_string($db_result, $pvalue);
        $post1 = strip_tags($post1);
        $post1 = preg_replace('/\s+/', ' ', $post1);

        $post1 = trim($post1);

        $arpost[$pkey] = "$post1";
      }
      extract($arpost);
    }
    $kode = strtoupper($_POST['kode']);
    $nama = $_POST['nama'];

    $get_klasifikasi = mysqli_query($db_result, "select * from db_klasifikasi WHERE hapus=\"0\" AND kode = \"$kode\" ");
    $n_klasifikasi = mysqli_num_rows($get_klasifikasi);

    if ($n_klasifikasi == 0) {
      $input = mysqli_query($db_result, "INSERT INTO db_klasifikasi (kode, nama, status, tgl_insert) VALUES (\"$kode\", \"$nama\", \"1\", now()) ");
      $message = "<div class=\"alert alert-success\">Klasifikasi Berhasil Ditambahkan.</div>";
    } else {
      $message = "<div class=\"alert alert-danger\">Kode Klasifikasi Sudah Ada (Harus unik).</div>";
    }
    echo $message;
    echo "<meta http-equiv='refresh' content='2; url=$link_back'>";
    break;

  case "delete":
    $id = $_GET['id'];
    $get_klasifikasi = mysqli_query($db_result, "select * from db_klasifikasi WHERE hapus=\"0\" AND id=\"$id\" ");
    $n_klasifikasi = mysqli_num_rows($get_klasifikasi);
    if ($n_klasifikasi != 0) {
      $input = mysqli_query($db_result, "UPDATE db_klasifikasi SET hapus=\"1\" WHERE id=\"$id\"");
      $message = "<div class=\"alert alert-info\">Klasifikasi Berhasil Dihapus.</div>";
    } else {
      $message = "<div class=\"alert alert-danger\">Data Tidak Ditemukan.</div>";
    }
    echo $message;
    echo "<meta http-equiv='refresh' content='2; url=$link_back'>";
    break;

  case "update":
    if (count($_POST) > 0) {
      foreach ($_POST as $pkey => $pvalue) {
        $post1 = mysqli_escape_string($db_result, $pvalue);
        $post1 = strip_tags($post1);
        $post1 = preg_replace('/\s+/', ' ', $post1);

        $post1 = trim($post1);

        $arpost[$pkey] = "$post1";
      }
      extract($arpost);
    }
    $kode = strtoupper($_POST['kode']);
    $nama = $_POST['nama'];
    $id = $_POST['id'];

    $get_klasifikasi = mysqli_query($db_result, "select * from db_klasifikasi WHERE hapus=\"0\" AND id=\"$id\" ");
    $n_klasifikasi = mysqli_num_rows($get_klasifikasi);
    if ($n_klasifikasi != 0) {
      $get_klasifikasi = mysqli_query($db_result, "select * from db_klasifikasi WHERE hapus=\"0\" AND id!=\"$id\" AND kode=\"$kode\" ");
      $n_klasifikasi = mysqli_num_rows($get_klasifikasi);
      if ($n_klasifikasi == 0) {
        $input = mysqli_query($db_result, "UPDATE db_klasifikasi SET kode=\"$kode\", nama=\"$nama\" WHERE id=\"$id\"");
        $message = "<div class=\"alert alert-info\">Klasifikasi Berhasil Diupdate.</div>";
      } else {
        $message = "<div class=\"alert alert-danger\">Kode Sudah Digunakan.</div>";
      }
    } else {
      $message = "<div class=\"alert alert-danger\">Data Tidak Ditemukan.</div>";
    }
    echo $message;
    echo "<meta http-equiv='refresh' content='2; url=$link_back'>";
    break;

  case "kategori":
    include "kategori/index.php";
    break;

  case "data":
    include "data/index.php";
    break;

  case "laporan":
    include "laporan/index.php";
    break;
}


?>
<!-- akhir konten -->