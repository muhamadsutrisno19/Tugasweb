<div class="page-header">
    <h3>Anggota Baru</h3>
</div>
<?= validation_errors('<p style="color:red;">', '</p>'); ?>
<?php
if ($this->session->flashdata()) {
    echo "<div class='alert alert-danger alert-message'>";
    echo $this->session->flashdata('alert');
    echo "</div>";
} ?>
<form action="<?php echo base_url() . 'admin/tambah_anggota_act' ?>" method="post" enctype="multipart/form-data">


    <div class="form-group">
        <label>Nama Anggota</label>
        <input type="text" name="nama_anggota" class="form-control">
        <?php echo form_error('nama_anggota'); ?>
    </div>

    <div class="form-group">
        <label>Gender</label>
        <select name="gender" class="form-control">
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
    </div>

    <div class="form-group">
        <label>No Tlpn</label>
        <input type="text" name="no_tlpn" class="form-control">
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" class="form-control">
    </div>

    <div class="form-group">
        <input type="submit" value="Simpan" class="btn btn-primary">
    </div>
    </div>
</form>
