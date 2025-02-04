<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Gis extends Admin_Controller
{
    public $modul_ini           = 'pemetaan';
    public $sub_modul_ini       = 'peta';
    public $kategori_pengaturan = 'peta';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('penduduk_model');
        $this->load->model('plan_lokasi_model');
        $this->load->model('plan_area_model');
        $this->load->model('plan_garis_model');
        $this->load->model('pembangunan_model');
        $this->load->model('pembangunan_dokumentasi_model');
        $this->load->model('data_persil_model');
        $this->load->model('wilayah_model');
    }

    public function clear(): void
    {
        $this->session->unset_userdata([
            'log', 'cari', 'filter', 'sex',
            'warganegara', 'fisik', 'mental', 'menahun',
            'golongan_darah', 'dusun', 'rw', 'rt',
            'agama', 'umur_min', 'umur_max', 'pekerjaan_id',
            'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk',
            'layer_penduduk', 'layer_keluarga', 'layer_rtm', 'layer_wilayah', 'layer_lokasi', 'layer_area',
        ]);

        $this->session->set_userdata('layer_keluarga', 0);

        redirect('gis');
    }

    public function index(): void
    {
        $list_session = ['filter', 'sex', 'cari', 'umur_min', 'umur_max', 'pekerjaan_id', 'status', 'agama', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk'];

        foreach ($list_session as $session) {
            $data[$session] = $this->session->userdata($session) ?: '';
        }

        if (isset($_SESSION['dusun'])) {
            $data['dusun']   = $_SESSION['dusun'];
            $data['list_rw'] = $this->wilayah_model->list_rw($data['dusun']);
            if (isset($_SESSION['rw'])) {
                $data['rw']      = $_SESSION['rw'];
                $data['list_rt'] = $this->wilayah_model->list_rt($data['dusun'], $data['rw']);
                $data['rt']      = $_SESSION['rt'] ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = '';
            $data['rw']    = '';
            $data['rt']    = '';
        }
        $variabel_sesi = ['layer_penduduk', 'layer_keluarga', 'layer_rtm', 'layer_desa', 'layer_wilayah', 'layer_lokasi', 'layer_area', 'layer_dusun', 'layer_rw', 'layer_rt', 'layer_garis'];

        foreach ($variabel_sesi as $variabel) {
            $data[$variabel] = $this->session->userdata($variabel) ?: 0;
        }

        $data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['list_jenis_kelamin']   = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_dusun']           = $this->wilayah_model->list_dusun();
        $data['wilayah']              = $this->wilayah_model->list_wil();
        $data['desa']                 = $this->header['desa'];
        $data['lokasi']               = $this->plan_lokasi_model->list_lokasi();
        $data['garis']                = $this->plan_garis_model->list_garis();
        $data['area']                 = $this->plan_area_model->list_area();
        $data['lokasi_pembangunan']   = $this->pembangunan_model->list_lokasi_pembangunan();
        $data['penduduk']             = $this->penduduk_model->list_data_map();
        $data['keyword']              = $this->penduduk_model->autocomplete();
        $data['dusun_gis']            = $this->wilayah_model->list_dusun();
        $data['rw_gis']               = $this->wilayah_model->list_rw();
        $data['rt_gis']               = $this->wilayah_model->list_rt();
        $data['list_ref']             = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['list_bantuan']         = collect(unserialize(STAT_BANTUAN))->toArray() + collect($this->program_bantuan_model->list_program(0))->pluck('nama', 'lap')->toArray();
        $data['persil']               = $this->data_persil_model->list_data();
        $this->render('gis/maps', $data);
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_rtm'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['cari']);
        }
        redirect('gis');
    }

    public function filter(): void
    {
        $filter = $this->input->post('filter');
        if ($filter != '') {
            $_SESSION['filter'] = $filter;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_rtm'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['filter']);
        }
        redirect('gis');
    }

    public function layer_rtm()
    {
        $layer_rtm = $this->input->post('layer_rtm');
        if ($layer_rtm == '') {
            $_SESSION['layer_rtm'] = 0;
        } else {
            $_SESSION['layer_penduduk'] = 0;
            $_SESSION['layer_keluarga'] = 0;
            $_SESSION['layer_rtm']      = 1;
        }
        redirect('gis');
    }

    public function layer_penduduk(): void
    {
        $layer_penduduk = $this->input->post('layer_penduduk');
        if ($layer_penduduk == '') {
            $_SESSION['layer_penduduk'] = 0;
        } else {
            $_SESSION['layer_penduduk'] = 1;
            $_SESSION['layer_keluarga'] = 0;
        }
        redirect('gis');
    }

    public function layer_wilayah(): void
    {
        $_SESSION['layer_wilayah'] = $this->input->post('layer_wilayah') ? 1 : 0;
        redirect('gis');
    }

    public function layer_area(): void
    {
        $_SESSION['layer_area'] = $this->input->post('layer_area') ? 1 : 0;
        redirect('gis');
    }

    public function layer_lokasi(): void
    {
        $_SESSION['layer_lokasi'] = $this->input->post('layer_lokasi') ? 1 : 0;
        redirect('gis');
    }

    public function layer_keluarga(): void
    {
        $layer_keluarga = $this->input->post('layer_keluarga');
        if ($layer_keluarga == '') {
            $_SESSION['layer_keluarga'] = 0;
        } else {
            $_SESSION['layer_keluarga'] = 1;
            $_SESSION['layer_penduduk'] = 0;
        }
        redirect('gis');
    }

    public function sex(): void
    {
        $sex = $this->input->post('sex');
        if ($sex != '') {
            $_SESSION['sex'] = $sex;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_rtm'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['sex']);
        }
        redirect('gis');
    }

    public function dusun(): void
    {
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $_SESSION['dusun'] = $dusun;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['dusun']);
        }
        redirect('gis');
    }

    public function rw(): void
    {
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $_SESSION['rw'] = $rw;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_rtm'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['rw']);
        }
        redirect('gis');
    }

    public function rt(): void
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $_SESSION['rt'] = $rt;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_rtm'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['rt']);
        }
        redirect('gis');
    }

    public function agama(): void
    {
        $agama = $this->input->post('agama');
        if ($agama != '') {
            $_SESSION['agama'] = $agama;
            if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga']) && empty($_SESSION['layer_rtm'])) {
                $_SESSION['layer_penduduk'] = 1;
            }
        } else {
            unset($_SESSION['agama']);
        }
        redirect('gis');
    }

    public function ajax_adv_search(): void
    {
        $list_session = ['umur_min', 'umur_max', 'pekerjaan_id', 'status', 'agama', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk'];

        foreach ($list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $data['input_umur']           = true;
        $data['list_agama']           = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['list_pendidikan']      = $this->referensi_model->list_data('tweb_penduduk_pendidikan');
        $data['list_pendidikan_kk']   = $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk');
        $data['list_pekerjaan']       = $this->referensi_model->list_data('tweb_penduduk_pekerjaan');
        $data['list_status_kawin']    = $this->referensi_model->list_data('tweb_penduduk_kawin');
        $data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['form_action']          = site_url('gis/adv_search_proses');

        $this->load->view('sid/kependudukan/ajax_adv_search_form', $data);
    }

    public function adv_search_proses(): void
    {
        $adv_search = $this->validasi_pencarian($this->input->post());
        $i          = 0;

        while ($i++ < count($adv_search)) {
            $col[$i] = key($adv_search);
            next($adv_search);
        }
        $i = 0;

        while ($i++ < count($col)) {
            if ($adv_search[$col[$i]] == '') {
                unset($adv_search[$col[$i]]);
            } else {
                $_SESSION[$col[$i]] = $adv_search[$col[$i]];
                if (empty($_SESSION['layer_penduduk']) && empty($_SESSION['layer_keluarga'])) {
                    $_SESSION['layer_penduduk'] = 1;
                }
            }
        }
        redirect('gis');
    }

    private function validasi_pencarian($post)
    {
        $data['umur_min']             = bilangan($post['umur_min']);
        $data['umur_max']             = bilangan($post['umur_max']);
        $data['pekerjaan_id']         = $post['pekerjaan_id'];
        $data['status']               = $post['status'];
        $data['agama']                = $post['agama'];
        $data['pendidikan_sedang_id'] = $post['pendidikan_sedang_id'];
        $data['pendidikan_kk_id']     = $post['pendidikan_kk_id'];
        $data['status_penduduk']      = $post['status_penduduk'];

        return $data;
    }

    public function layer_garis(): void
    {
        $_SESSION['layer_garis'] = $this->input->post('layer_garis') ? 1 : 0;
        redirect('gis');
    }
}
