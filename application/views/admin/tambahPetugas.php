<section id="content">
  <div class="container">
    <div class="section">
      <div class="divider"></div>
      
      <div id="table-datatables">
        <h4 class="header">Data Petugas</h4>
        <hr>
        
        <div class="row">
          <!-- Alert -->
          <?php if($this->session->flashdata('info')) { ?>
            <div class="row" id="alert_box">
              <div class="col s12 m12">
                <div class="card green darken-1">
                  <div class="row">
                    <div class="col s12 m10">
                      <div class="card-content white-text center">
                        <p><?php echo $this->session->flashdata('info'); ?></p>
                      </div>
                    </div>
                    <div class="col s12 m2">
                      <i class="mdi-content-clear icon_style" id="alert_close" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
          <!-- End Alert -->

          <!-- Button Section -->
          <div class="col s12 m8 l11">
            <a href="<?php echo base_url(); ?>petugas/tambah" class="btn blue">Tambah<i class="mdi-av-playlist-add right"></i></a>
            <a href="<?php echo base_url(); ?>petugas/export" class="btn cyan waves-effect waves-light">Excel<i class="mdi-action-print right"></i></a>
            <a href="<?php echo base_url(); ?>petugas/exportPDF" class="btn waves-effect waves-light indigo">PDF<i class="mdi-action-print right"></i></a>
          </div>

          <!-- Data Table -->
          <div class="col s12 m12 l12">
            <table id="data-table-simple" class="responsive-table display" cellspacing="0">
              <thead>
                <tr>
                  <th>ID Petugas</th>
                  <th>Nama Petugas</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dataPetugas as $petugas) { ?>
                  <tr>
                    <td><?php echo $petugas->idUser; ?></td>
                    <td><?= $petugas->nama; ?></td>
                    <td><?= $petugas->email; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <br>
    </div>
  </div>
</section>
