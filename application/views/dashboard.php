<div class="row justify-content-md-center">

    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $barang; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Departemen</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $supplier; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stok barang</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stok; ?></div>
                            </div>
                            <div class="col-auto">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cubes fa-2x text-gray-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row pt-5 mt-5 ">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-warning py-3">
                <h6 class="m-0 font-weight-bold text-white text-center">Total Barang</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 text-center table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($barang_min) :
                            foreach ($barang_min as $b) :
                        ?>
                                <tr>
                                    <td><?= $b['nama_barang']; ?></td>
                                    <td><?= $b['stok']; ?></td>
                                    <td>
                                        <a href="<?= base_url('barangmasuk/add/') . $b['id_barang'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="text-center">
                                    No minimum stock items
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="blockquote-footer lead text-center ">Barang akan Muncul Ketika Kurang dari 20</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-success py-3">
                <h6 class="m-0 font-weight-bold text-white text-center">Riwayat Pemindahan Barang</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Departemen</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi['barang_masuk'] as $tbm) : ?>
                            <tr>
                                <td><strong><?= $tbm['tanggal_masuk']; ?></strong></td>
                                <td><?= $tbm['nama_barang']; ?></td>
                                <td><?= $tbm['nama_supplier'];?></td>
                                <td><span class="badge badge-success"><?= $tbm['jumlah_masuk']; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-danger py-3">
                <h6 class="m-0 font-weight-bold text-white text-center">Riwayat Penambahan Barang</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <td>Total Harga</td>
                            <th>Total Penambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi['barang_keluar'] as $tbk) : ?>
                            <tr>
                                <td><strong><?= $tbk['tanggal_keluar']; ?></strong></td>
                                <td><?= $tbk['nama_barang']; ?></td>
                                <td><?= 'Rp.' . number_format ($tbk['total_nominal_dtl']); ?></td>
                                <td><span class="badge badge-danger"><?= $tbk['jumlah_keluar']; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row pt-5 justify-content-md-center">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7" >
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Total Aktivitas Pada Tahun <?= date('Y'); ?></h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="myAreaChart" width="669" height="320" class="chartjs-render-monitor" style="display: block; width: 669px; height: 320px;"></canvas>
                </div>
            </div>
        </div>
    </div>  
</div>