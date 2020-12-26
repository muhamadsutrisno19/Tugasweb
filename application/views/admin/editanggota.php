<div class="page-header">
    <h3>Edit Anggota</h3>
</div>
<?php foreach ($anggota as $a) { ?>
    <form action="<?php echo base_url() . 'admin/update_anggota' ?>" method="post" enctype="multipart/form-data">


        <div class="form-group">
            <label>Nama Anggota</label>
            <input type="hidden" name="id" value="<?php echo $a->Id_anggota; ?>">
            <input type="text" name="nama_anggota" class="form-control" value="<?php echo $a->nama_anggota; ?>">
            <?php echo form_error('nama_anggota'); ?>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <?php echo form_error('gender'); ?>
            <select name="gender" class="form-control">
                <option value="Laki-laki" <?php if ($a->gender == 'Laki-laki') {
                                                echo "selected";
                                            } ?>>Laki-laki</option>
                <option value="Perempuan" <?php if ($a->gender == 'Perempuan') {
                                                echo "selected";
                                            } ?>>Perempuan</option>
            </select>
        </div>

        <div class="form-group">
            <label>No Tlpn</label>
            <input type="text" name="no_tlpn" class="form-control" value="<?php echo $a->no_tlpn; ?>">
            <?php echo form_error('no_tlpn'); ?>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" value="<?php echo $a->alamat; ?>">
            <?php echo form_error('alamat'); ?>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control" value="<?php echo $a->email; ?>">
            <?php echo form_error('email'); ?>
        </div>

        <div class="form-group">
            <input type="submit" value="Update" class="btn btn-default">
            <input type="button" value="Kembali" class="btn btn-primary" onclick="window.history.go(-1)">
        </div>
    </form>
<?php } ?>