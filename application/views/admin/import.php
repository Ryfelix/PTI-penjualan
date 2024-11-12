<?php if (isset($upload_error)) { // If upload fails ?>
    <div style="color: red;"><?php echo $upload_error; ?></div> <!-- Show error message -->
    <a href="<?php echo base_url('member/dataMember'); ?>">Kembali</a> <!-- Go back link -->
    <?php die; // Stop script execution ?>
<?php } ?>

<section id="content">
    <div class="container">
        <div class="section">
            <div class="divider"></div>

            <!-- Basic Form -->
            <div id="basic-form" class="section">
                <h4 class="header">Tambah Member</h4>
                <hr>

                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card-panel">
                            <div class="row">
                                <form class="col s12" method="post" enctype="multipart/form-data" action="">
                                    <a href="<?php echo base_url('excel/format.xlsx'); ?>">Download Format</a>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input class="btn cyan waves-effect waves-light" type="file" name="file">
                                            <button class="btn blue waves-effect waves-light right" type="submit" name="preview">Lihat</button>
                                            <br><br>
                                        </div>
                                    </div>
                                </form>

                                <?php if (isset($_POST['preview'])) { // If the user clicks the preview button ?>
                                    <form method="post" action="<?php echo base_url('member/tambah'); ?>">
                                        <hr>
                                        <h5 align="center">Lihat Data</h5>
                                        <hr>
                                        <div class="col s12">
                                            <table id="data-table-simple" class="responsive-table display" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Alamat</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $numrow = 1;
                                                    $kosong = 0;
                                                    foreach ($sheet as $row) {
                                                        $no = $row['A'];
                                                        $nama = $row['B'];
                                                        $jk = $row['C'];
                                                        $alamat = $row['D'];

                                                        if (empty($no) && empty($nama) && empty($jk) && empty($alamat)) {
                                                            continue;
                                                        }

                                                        if ($numrow > 1) {
                                                            // Validate if the fields are empty
                                                            $no_td = (!empty($no)) ? "" : " style='background: #E07171;'";
                                                            $nama_td = (!empty($nama)) ? "" : " style='background: #E07171;'";
                                                            $jk_td = (!empty($jk)) ? "" : " style='background: #E07171;'";
                                                            $alamat_td = (!empty($alamat)) ? "" : " style='background: #E07171;'";

                                                            echo "<tr>";
                                                            echo "<td" . $no_td . ">" . $no . "</td>";
                                                            echo "<td" . $nama_td . ">" . $nama . "</td>";
                                                            echo "<td" . $jk_td . ">" . $jk . "</td>";
                                                            echo "<td" . $alamat_td . ">" . $alamat . "</td>";
                                                            echo "</tr>";
                                                        }
                                                        $numrow++; // Increment the row number
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                            <?php if ($kosong > 1) { // If there are more than 1 empty fields ?>
                                                <script>
                                                    $(document).ready(function(){
                                                        // Show the alert if there are empty fields
                                                        $("#jumlah_kosong").html('<?php echo $kosong; ?>');
                                                        $("#kosong").show();
                                                    });
                                                </script>
                                            <?php } else { ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <button class="btn cyan waves-effect waves-light" type="submit" name="action">Tambah
                                                            <i class="mdi-content-send right"></i>
                                                        </button>
                                                        <a href="<?php echo base_url(); ?>member/dataMember" class="btn red waves-effect waves-light right">Batal
                                                            <i class="mdi-content-undo right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
