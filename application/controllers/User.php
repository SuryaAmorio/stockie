<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    public $user_id;

    function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lusers');
        $this->load->library('session');
        $this->load->model('Userm');
        $this->auth->check_admin_auth();
    }

    #==============User page load============#

    public function index() {
        $content = $this->lusers->user_add_form();
        $this->template->full_admin_html_view($content);
    }

    #===============User Search Item===========#

    public function company_insert(){
<<<<<<< HEAD
     

        $config = array(
            'upload_path' => "assets/dist/images/profile/",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'encrypt_name' => true
        );   
        $this->load->library('upload',$config);

        if($this->upload->do_upload('logo'))
        {
            $view = $this->upload->data();
            $logo = base_url($config['upload_path'] . $view['file_name']);
        }else{
            $view = $this->upload->data();
            $logo = base_url($config['upload_path'] . $view['file_name']);
        }

        $data['company_name'] = $this->input->post('company_name');
        $data['company_email'] = $this->input->post('company_email');
        $data['mobile'] = $this->input->post('mobile');
        $data['address'] = $this->input->post('address');
        $data['website'] = $this->input->post('website');
        $data['logo'] = $logo;

        // echo '<pre>';
        // print_r($data); 
        // echo '</pre>';
        // exit();

       $this->db->insert('company_information', $data);

        $last_insert_id = $this->db->insert_id();
        $data1['username'] = $this->input->post('username');
        $data1['password'] = $this->input->post('password');
        $data1['user_type'] = $this->input->post('user_type');
        $data1['cid'] = $last_insert_id;
        $this->db->insert('user_login', $data1);
        redirect(base_url('User'));
=======

        
      print_r($_POST);
        exit();
        // $data = array(
        //     'CompanyName' => $company,
        //     'Email' => $email,
        //     'Mobile' => $mobile,
        //     'address' => $address,
        //     'Website' => $website
        // );

        // echo '<pre>';
        //  var_dump($data); die;
        // echo '</pre>';

        $status =  $this->db->insert('company_information', $data);

        if($status === true)
        {
            echo "<script>alert('Detailed Saved Successfully')</script>";
        }else{
            echo "<script>alert('Detailed Saved Failed!!!!!')</script>";
        }
>>>>>>> 1f0c5873183e8e8b57efbb9700afb1e14b7e3142
    }

    public function user_search_item() {
        $user_id = $this->input->post('user_id');
        $content = $this->lusers->user_search_item($user_id);
        $this->template->full_admin_html_view($content);
    }

    #================Manage User===============#

    public function manage_user() {
        $content = $this->lusers->user_list();
        $this->template->full_admin_html_view($content);
    }


    #==============Add  Company and admin user==============#


    #==============Insert User==============#

    public function insert_user() {
        $this->load->library('upload');
        if (($_FILES['logo']['name'])) {
            $files = $_FILES;
            $config = array();
            $config['upload_path'] = 'assets/dist/img/profile_picture/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['max_size'] = '1000000';
            $config['max_width'] = '1024000';
            $config['max_height'] = '768000';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = true;

            $this->upload->initialize($config);
              if (!$this->upload->do_upload('logo')) {
                $data['error_message'] = $this->upload->display_errors();
                $this->session->set_userdata($sdata);
                redirect('user');
            } else {
                $view = $this->upload->data();
                $logo = base_url($config['upload_path'] . $view['file_name']);
            }
            
        }
        $data = array(
            'user_id'    => $this->generator(15),
            'first_name' => $this->input->post('first_name',true),
            'last_name'  => $this->input->post('last_name',true),
            'email'      => $this->input->post('email',true),
            'password'   => md5("gef" . $this->input->post('password',true)),
            'user_type'  => $this->input->post('user_type',true),
            'logo'       => (!empty($logo)?$logo:base_url().'assets/dist/img/profile_picture/profile.jpg'),
            'status'     => 1
        );

        $this->lusers->insert_user($data);
        $this->session->set_userdata(array('message' => display('successfully_added')));
        if (isset($_POST['add-user'])) {
            redirect('User/manage_user');
        } elseif (isset($_POST['add-user-another'])) {
            redirect(base_url('User/manage_user'));
        }
    }

    #===============User update form================#

    public function user_update_form($user_id) {
        $user_id = $user_id;
        $content = $this->lusers->user_edit_data($user_id);
        $this->template->full_admin_html_view($content);
    }

    #===============User update===================#

    public function user_update() {
      $this->load->library('upload');
        if (($_FILES['logo']['name'])) {
            $files = $_FILES;
            $config = array();
            $config['upload_path'] = 'assets/dist/img/profile_picture/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['max_size'] = '1000000';
            $config['max_width'] = '1024000';
            $config['max_height'] = '768000';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = true;

            $this->upload->initialize($config);
              if (!$this->upload->do_upload('logo')) {
                $sdata['error_message'] = $this->upload->display_errors();
                $this->session->set_userdata($sdata);
                redirect('user');
            } else {
                $view = $this->upload->data();
                $logo = base_url($config['upload_path'] . $view['file_name']);
            }
        }
        $user_id = $this->input->post('user_id');
        $data['user_id'] = $user_id;
        $data['logo']   = $logo;
        $this->Userm->update_user($data);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('User/manage_user'));
    }

    #============User delete===========#

    public function user_delete($user_id) {
        $this->Userm->delete_user($user_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
      redirect(base_url('User/manage_user'));
    }

    // Random Id generator
    public function generator($lenth) {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 61);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }

}
