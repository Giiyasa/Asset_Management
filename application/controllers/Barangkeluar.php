<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Barang Masuk ";
        $data['barangkeluar'] = $this->admin->getBarangkeluar();
        $data['id_barang_keluar'] = "";
        $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('diskon', 'Diskon', "trim|less_than_equal_to[{$this->input->post('total_nominal')}]");
    }

    private function _validasi_cart()
    {   
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');

        $input = $this->input->post('barang_id', true);
        $stok = $this->admin->get('barang', ['id_barang' => $input])['stok'];
        $stok_valid = $stok + 0.1;
       
      
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Pemindahan Barang";
            $data['barang'] = $this->admin->get('barang');

            // Mendapatkan dan men-generate kode transaksi barang keluar
            $kode = 'S' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
            $kode_tambah = substr($kode_terakhir, -4, 4);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 4, '0', STR_PAD_LEFT);
            $data['id_barang_keluar'] = $kode . $number;


            $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        } else {
            // $input = $this->input->post(null, true);
            $input = array(
                'id_barang_keluar' => $this->input->post('id_barang_keluar'),
                'user_id' => $this->input->post('user_id'),
                'tanggal_keluar' => $this->input->post('tanggal_keluar'),
                'nama_penerima' => $this->input->post('nama_penerima'),
                'alamat' => $this->input->post('alamat'),
                'diskon' => $this->input->post('diskon'),
                'total_nominal' => $this->input->post('total_nominal'),
                );
            if ($this->input->post('grand_total')=="") {
            $input['grand_total'] = $this->input->post('grand_total_hidden');
            } else {
            $input['grand_total'] = $this->input->post('grand_total');
            }
            $insert = $this->admin->insert('barang_keluar', $input);

            $id_barang_keluar = $this->input->post('id_barang_keluar');
            $this->admin->simpan_cart($id_barang_keluar);
            $this->cart->destroy();

            // var_dump($input);

            if ($insert) {
                set_pesan('Data Saved!');
                redirect('barangkeluar');
            } else {
                set_pesan('Oops, Something went wrong!');
                redirect('barangkeluar/add');
            }
        }
    }

    public function add_to_cart(){
        $this->_validasi_cart();
        if ($this->form_validation->run() == false) {
        $data['title'] = "Pemindahan Barang";
        $data['barang'] = $this->admin->get('barang');

        // Mendapatkan dan men-generate kode transaksi barang keluar
        $kode = 'S-' . date('ymd');
        $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
        $kode_tambah = substr($kode_terakhir, -4, 4);
        $kode_tambah++;
        $number = str_pad($kode_tambah, 4, '0', STR_PAD_LEFT);
        $data['id_barang_keluar'] = $kode . $number;

        $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        } else {
        $barang_id=$this->input->post('barang_id');
        $barang=$this->admin->get_barang($barang_id);
        $i=$barang->row_array();
        $data = array(
           'id'       => $i['id_barang'],
           'name'     => $i['nama_barang'],
           'price'    => str_replace(",", "", $this->input->post('harga')),
           'qty'      => $this->input->post('jumlah_keluar'),
           'amount'   => str_replace(",", "", $this->input->post('harga'))
        );
        // var_dump($data);
        $this->cart->insert($data);
        redirect('Barangkeluar/add');
        }
    }

    public function remove(){
        $row_id=$this->uri->segment(3);
        $this->cart->update(array(
               'rowid'      => $row_id,
               'qty'     => 0
            ));
        redirect('Barangkeluar/add');
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        //Tambah stok jika hit hapus data
        if ($id) {
        $get = $this->admin->getIDBarangKeluar2($id)->result_array();
        foreach ($get as $i) {
        $data['stok'] = $i['jumlah_keluar'] - $i['stok'];
        $this->admin->update_stok($i['barang_id'], $data);
        // var_dump($data);
        }
        }

        if ($this->admin->delete('barang_keluar', 'id_barang_keluar', $id)) {
            set_pesan('Data Deleted!');
        } else {
            set_pesan('Something went wrong', false);
        }
        redirect('barangkeluar');
    }

    public function faktur_surat_jalan($id){
        $x['title'] = "Invoice";
        $x['data'] = $this->admin->getIDBarangKeluar2($id);
        $this->load->view('faktur/surat_jalan', $x);
    }

    public function faktur_surat_tagihan($id){
        $x['title'] = "Billing Invoice";
        $x['data'] = $this->admin->getIDBarangKeluar2($id);
        $this->load->view('faktur/surat_tagihan', $x);
    }

    public function surat_jalan($id){

        $x['title'] = "Invoice";
        $x['data'] = $this->admin->getIDBarangKeluar($id);
        $this->load->view('faktur/surat_jalan', $x);
    }
}
