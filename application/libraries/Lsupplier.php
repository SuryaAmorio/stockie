<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Lsupplier {



    //Supplier add form

    public function supplier_add_form() {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $data = array(

            'title' => display('add_supplier')

        );

        $supplierForm = $CI->parser->parse('supplier/add_supplier_form', $data, true);

        return $supplierForm;

    }



    //Insert supplier

    public function insert_supplier($data) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $result = $CI->Suppliers->supplier_entry($data);

        if ($result == TRUE) {

            return TRUE;

        } else {

            return FALSE;

        }

    }



    //Supplier List

    public function supplier_list() {

   

        $CI =& get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data['total_supplier']    = $CI->Suppliers->count_supplier();

        $data['currency']          = $currency_details[0]['currency'];

        $data['company_info']      = $CI->Suppliers->retrieve_company();

        $supplierlist = $CI->parser->parse('supplier/supplier',$data,true);

        return $supplierlist;

    }



    // supplier search

    public function supplier_search($supplier_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $suppliers_list = $CI->Suppliers->supplier_search($supplier_id);

        $i = 0;

        if (!empty($suppliers_list)) {

            foreach ($suppliers_list as $k => $v) {

                $i++;

                $suppliers_list[$k]['sl'] = $i;

            }

        }



        $data = array(

            'title'          => display('search'),

            'suppliers_list' => $suppliers_list,

            'links'          => '',

        );



        $supplierList = $CI->parser->parse('supplier/supplier', $data, true);

        return $supplierList;

    }



    //Supplier Search Item

    public function supplier_search_item($supplier_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $suppliers_list = $CI->Suppliers->supplier_search_item($supplier_id);

        $i = 0;

        if ($suppliers_list) {

            foreach ($suppliers_list as $k => $v) {

                $i++;

                $suppliers_list[$k]['sl'] = $i;

            }



            $data = array(

                'title'          => display('manage_suppiler'),

                'suppliers_list' => $suppliers_list

            );

            $supplierList = $CI->parser->parse('supplier/supplier', $data, true);

            return $supplierList;

        } else {

            redirect('Csupplier/manage_supplier');

        }

    }



    //Product search by supplier

    public function product_by_search() {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $suppliers_list = $CI->Suppliers->product_search_item($supplier_id);

        $i = 0;

        foreach ($suppliers_list as $k => $v) {

            $i++;

            $suppliers_list[$k]['sl'] = $i;

        }

        $data = array(

            'title' => display('manage_supplier'),

            'suppliers_list' => $suppliers_list

        );

        $supplierList = $CI->parser->parse('supplier/supplier', $data, true);

        return $supplierList;

    }



    //Supplier Edit Data

    public function supplier_edit_data($supplier_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $supplier_detail = $CI->Suppliers->retrieve_supplier_editdata($supplier_id);

        $data = array(

            'title'         => display('supplier_edit'),

            'service_provider' => $supplier_detail[0]['service_provider'],

            'supplier_id'   => $supplier_detail[0]['supplier_id'],

            'supplier_name' => $supplier_detail[0]['supplier_name'],

            'address'       => $supplier_detail[0]['address'],

            'address2'      => $supplier_detail[0]['address2'],

            'mobile'        => $supplier_detail[0]['mobile'],

            'emailnumber'   => $supplier_detail[0]['emailnumber'],

            'email_address' => $supplier_detail[0]['email_address'],

            'phone'         => $supplier_detail[0]['phone'],

            'contact'       => $supplier_detail[0]['contact'],

            'fax'           => $supplier_detail[0]['fax'],

            'city'          => $supplier_detail[0]['city'],

            'state'         => $supplier_detail[0]['state'],

            'zip'           => $supplier_detail[0]['zip'],

            'country'       => $supplier_detail[0]['country'],

            'details'       => $supplier_detail[0]['details'],

            'status'        => $supplier_detail[0]['status']

        );

        $chapterList = $CI->parser->parse('supplier/edit_supplier_form', $data, true);

        return $chapterList;

    }



    //Supplier Details Data

    public function supplier_detail_data($supplier_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);

        $purchase_info = $CI->Suppliers->supplier_purchase_data($supplier_id);

        $total_amount = 0;

        if (!empty($purchase_info)) {

            foreach ($purchase_info as $k => $v) {

                $purchase_info[$k]['final_date'] = $CI->occational->dateConvert($purchase_info[$k]['purchase_date']);

                $total_amount = $total_amount + $purchase_info[$k]['grand_total_amount'];

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title'            => display('supplier_details'),

            'supplier_id'      => $supplier_detail[0]['supplier_id'],

            'supplier_name'    => $supplier_detail[0]['supplier_name'],

            'supplier_address' => $supplier_detail[0]['address'],

            'supplier_mobile'  => $supplier_detail[0]['mobile'],

            'details'          => $supplier_detail[0]['details'],

            'total_amount'     => number_format($total_amount, 2, '.', ','),

            'purchase_info'    => $purchase_info,

            'currency'         => $currency_details[0]['currency'],

            'position'         => $currency_details[0]['currency_position'],

        );

        $chapterList = $CI->parser->parse('supplier/supplier_details', $data, true);

        return $chapterList;

    }



    public function supplier_sales_data($supplier_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->library('occational');

        $supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);

        $sales_info = $CI->Suppliers->supplier_sales_data($supplier_id, null);



        if (!empty($sales_info)) {

            foreach ($sales_info as $k => $v) {

                $sales_info[$k]['date'] = $CI->occational->dateConvert($sales_info[$k]['date']);

            }

        }

        $data = array(

            'supplier_id'       => $supplier_detail[0]['supplier_id'],

            'supplier_name'     => $supplier_detail[0]['supplier_name'],

            'supplier_address'  => $supplier_detail[0]['address'],

            'supplier_mobile'   => $supplier_detail[0]['mobile'],

            'details'           => $supplier_detail[0]['details'],

            'sales_info'        => $sales_info,

        );

        $sales_report = $CI->parser->parse('supplier/supplier_sales_report', $data, true);

        return $sales_report;

    }



    //Ledger Book Maintaining information....

    public function supplier_ledger($supplier_id, $start, $end) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $supplier = $CI->Suppliers->supplier_list("110", "0");

        $supplier_details = $CI->Suppliers->supplier_personal_data($supplier_id);

        $ledger = $CI->Suppliers->suppliers_ledger($supplier_id, $start, $end);

       

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $data = array(

            'title'            => display('supplier_ledger'),

            'ledgers'          => $ledger,

            'supplier_name'    => $supplier_details[0]['supplier_name'],

            'address'          => $supplier_details[0]['address'],

            'supplier_ledger'  => 'Csupplier/supplier_ledger',

            'supplier'         => $supplier,

            'currency'         => $currency_details[0]['currency'],

            'position'         => $currency_details[0]['currency_position'],

            'links'            => '',

        );



        $singlecustomerdetails = $CI->parser->parse('supplier/supplier_ledger', $data, true);

        return $singlecustomerdetails;

    }



    public function supplier_sales_details($supplier_id, $start, $end) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);

        $sales_info = $CI->Suppliers->supplier_sales_details($supplier_id, $start, $end);



        $sub_total = 0;

        if (!empty($sales_info)) {

            foreach ($sales_info as $k => $v) {

                $sales_info[$k]['date'] = $CI->occational->dateConvert($sales_info[$k]['date']);

                $sub_total += $sales_info[$k]['total'];

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title'            => display('supplier_sales_details'),

            'supplier_id'      => $supplier_detail[0]['supplier_id'],

            'supplier_name'    => $supplier_detail[0]['supplier_name'],

            'supplier_address' => $supplier_detail[0]['address'],

            'supplier_mobile'  => $supplier_detail[0]['mobile'],

            'details'          => $supplier_detail[0]['details'],

            'sub_total'        => number_format($sub_total, 2, '.', ','),

            'sales_info'       => $sales_info,

            'currency'         => $currency_details[0]['currency'],

            'position'         => $currency_details[0]['currency_position'],

        );

        $sales_report = $CI->parser->parse('supplier/supplier_sales_details', $data, true);

        return $sales_report;

    }



    ################################################################################################ Supplier sales details all from menu###########



    public function supplier_sales_details_allm($links, $per_page, $page) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $supplier_detail = $CI->Suppliers->supplier_personal_data_all();

        $sales_info = $CI->Suppliers->supplier_sales_details_all($per_page, $page);



        $sub_total = 0;

        if (!empty($sales_info)) {

            foreach ($sales_info as $k => $v) {

                $sales_info[$k]['date'] = $sales_info[$k]['date'];

                $sub_total += $sales_info[$k]['total'];

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title'           => display('supplier_sales_details'),

            'supplier_id'     => $supplier_detail[0]['supplier_id'],

            'supplier_name'   => '',

            'details'         => $supplier_detail[0]['details'],

            'sub_total'       => number_format($sub_total, 2, '.', ','),

            'sales_info'      => $sales_info,

            'links'           => $links,

            'supplier_ledger' => 'Csupplier/supplier_ledger/',

            'supplier_sales_details' => 'Csupplier/supplier_sales_details',

            'supplier_sales_summary' => 'Csupplier/supplier_sales_summary/',

            'sales_payment_actual'   => 'Csupplier/sales_payment_actual/',

            'currency'        => $currency_details[0]['currency'],

            'position'        => $currency_details[0]['currency_position'],

        );

        $sales_report = $CI->parser->parse('supplier/supplier_sales_details', $data, true);

        return $sales_report;

    }



     public function supplier_sales_details_datewise($links, $per_page, $page,$fromdate,$todate) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $supplier_detail = $CI->Suppliers->supplier_personal_data_all();

        $sales_info = $CI->Suppliers->supplier_sales_details_datewise($per_page, $page,$fromdate,$todate);



        $sub_total = 0;

        if (!empty($sales_info)) {

            foreach ($sales_info as $k => $v) {

                $sales_info[$k]['date'] = $sales_info[$k]['date'];

                $sub_total += $sales_info[$k]['total'];

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title'           => display('supplier_sales_details'),

            'supplier_id'     => $supplier_detail[0]['supplier_id'],

            'supplier_name'   => '',

            'details'         => $supplier_detail[0]['details'],

            'sub_total'       => number_format($sub_total, 2, '.', ','),

            'sales_info'      => $sales_info,

            'links'           => $links,

            'fromdate'        => $fromdate,

            'todate'          => $todate,

            'currency'        => $currency_details[0]['currency'],

            'position'        => $currency_details[0]['currency_position'],

        );

        $sales_report = $CI->parser->parse('supplier/supplier_sales_details', $data, true);

        return $sales_report;

    }





    public function supplier_sales_summary($supplier_id, $links, $per_page, $page) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);

        $sales_info = $CI->Suppliers->supplier_sales_summary($per_page, $page);



        $sub_total = 0;

        if (!empty($sales_info)) {

            foreach ($sales_info as $k => $v) {

                $sales_info[$k]['date'] = $CI->occational->dateConvert($sales_info[$k]['date']);

                $sub_total += $sales_info[$k]['total'];

            }

        }



        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title'                  => display('supplier_sales_summary'),

            'supplier_detail'        => $supplier_detail,

            'sub_total'              => number_format($sub_total, 2, '.', ','),

            'sales_info'             => $sales_info,

            'links'                  => $links,

            'supplier_ledger'        => 'Csupplier/supplier_ledger/' . $supplier_id,

            'supplier_sales_details' => 'Csupplier/supplier_sales_details/' . $supplier_id,

            'supplier_sales_summary' => 'Csupplier/supplier_sales_summary/' . $supplier_id,

            'sales_payment_actual'   => 'Csupplier/sales_payment_actual/' . $supplier_id,

            'currency'               => $currency_details[0]['currency'],

            'position'               => $currency_details[0]['currency_position'],

        );

        $sales_report = $CI->parser->parse('supplier/supplier_sales_summary', $data, true);

        return $sales_report;

    }



    ########################## Sales & Payment ledger #########################

    #	This function will be responsible for retreive all actual sales information 

    # 	as well as payment info.Whatever stock that will not be matter .

    ############################################################################



    function sales_payment_actual($supplier_id, $links, $per_page, $page) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $sales_payment_actual = $CI->Suppliers->sales_payment_actual($per_page, $page);

        $sup_per_info = $CI->Suppliers->supplier_personal_data($supplier_id);



        $total_amount = 0;

        if (!empty($sales_payment_actual)) {

            foreach ($sales_payment_actual as $k => $v) {

                $sales_payment_actual[$k]['total_amount'] = $total_amount + $sales_payment_actual[$k]['amount'];

                $total_amount = $sales_payment_actual[$k]['total_amount'];

            }

        }



        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $data = array(

            'title'                 => display('supplier_actual_ledger'),

            'info'                  => $CI->Suppliers->supplier_personal_data($supplier_id),

            'total_details'         => $CI->Suppliers->sales_payment_actual_total($supplier_id),

            'ledger'                => $sales_payment_actual,

            'links'                 => $links,

            'company_info'          => $CI->Suppliers->retrieve_company(),

            'supplier_ledger'       => 'Csupplier/supplier_ledger/' . $supplier_id,

            'supplier_sales_details'=> 'Csupplier/supplier_sales_details/' . $supplier_id,

            'supplier_sales_summary'=> 'Csupplier/supplier_sales_summary/' . $supplier_id,

            'sales_payment_actual'  => 'Csupplier/sales_payment_actual/' . $supplier_id,

            'currency'              => $currency_details[0]['currency'],

            'position'              => $currency_details[0]['currency_position'],

        );



        $sales_actual_report = $CI->parser->parse('supplier/sales_payment_ledger', $data, true);

        return $sales_actual_report;

    }



    //Search supplier

    public function supplier_search_list($cat_id, $company_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $category_list = $CI->Suppliers->retrieve_category_list();

        $suppliers_list = $CI->Suppliers->supplier_search_list($cat_id, $company_id);

        $data = array(

            'title'          => display('manage_suppiler'),

            'suppliers_list' => $suppliers_list,

            'category_list'  => $category_list

        );

        $supplierList = $CI->parser->parse('supplier/supplier', $data, true);

        return $supplierList;

    }



    ################################################################################################################################################### Supplier Report Part ################################



    public function supplier_ledger_report($links, $per_page, $page) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $supplier = $CI->Suppliers->supplier_list();

        $supplier_details = $CI->Suppliers->supplier_personal_data1();

        $ledger = $CI->Suppliers->supplier_product_sale1($per_page, $page);



        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $data = array(

            'title'          => display('supplier_ledger'),

            'ledgers'        => $ledger,

            'supplier_name'  => '',

            'supplier_ledger'=> 'Csupplier/supplier_ledger',

            'supplier'       => $supplier,

            'currency'       => $currency_details[0]['currency'],

            'position'       => $currency_details[0]['currency_position'],

            'links'          => $links,

        );



        $singlecustomerdetails = $CI->parser->parse('supplier/supplier_ledger', $data, true);

        return $singlecustomerdetails;

    }



    // supplier id wise info from view/mange page

    public function supplier_ledger_info($supplier_id) {

        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $supplier_details = $CI->Suppliers->supplier_personal_data($supplier_id);

        $supplier         = $CI->Suppliers->supplier_list("110", "0");

        $ledgers          = $CI->Suppliers->supplier_product_sale_info($supplier_id);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $data = array(

            'title'           => display('supplier_ledger'),

            'ledgers'         => $ledgers,

            'supplier_name'   => $supplier_details[0]['supplier_name'],

            'address'         => $supplier_details[0]['address'],

            'supplier_ledger' => 'Csupplier/supplier_ledger',

            'supplier'        => $supplier,

            'currency'        => $currency_details[0]['currency'],

            'position'        => $currency_details[0]['currency_position'],

            'links'           => '',

        );



        $singlecustomerdetails = $CI->parser->parse('supplier/supplier_ledger', $data, true);

        return $singlecustomerdetails;

    }







        public function advance_details_data($receiptid,$supplier_id) {



        $CI = & get_instance();

        $CI->load->model('Suppliers');

        $CI->load->model('Purchases');

        $CI->load->model('Web_settings');

        $receiptdata      = $CI->Suppliers->advance_details($receiptid,$supplier_id);

        $supplier_details = $CI->Suppliers->supplier_personal_data($supplier_id);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $company_info     = $CI->Purchases->retrieve_company();

        $data = array(

            'title'            => display('supplier_advance'),

            'details'          => $receiptdata,

            'supplier_name'    => $supplier_details[0]['supplier_name'],

            'receipt_no'       => $receiptdata[0]['VNo'],

            'address'          => $supplier_details[0]['address'],

            'mobile'           => $supplier_details[0]['mobile'],

            'company_info'     => $company_info,

            'currency'         => $currency_details[0]['currency'],

            'position'         => $currency_details[0]['currency_position'],

        );



        $resultdata = $CI->parser->parse('supplier/supplier_advance_receipt', $data, true);

        return $resultdata;

    }



}



?>