<?php
//SHOPROOO商城资源
namespace app\models;

class MerchantsStepsField extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_steps_fields';
	protected $primaryKey = 'fid';
	public $timestamps = false;
	protected $fillable = array('user_id', 'agreement', 'steps_site', 'site_process', 'contactName', 'contactPhone', 'contactEmail', 'organization_code', 'organization_fileImg', 'companyName', 'business_license_id', 'legal_person', 'personalNo', 'legal_person_fileImg', 'license_comp_adress', 'license_adress', 'establish_date', 'business_term', 'busines_scope', 'license_fileImg', 'company_located', 'company_adress', 'company_contactTel', 'company_tentactr', 'company_phone', 'taxpayer_id', 'taxs_type', 'taxs_num', 'tax_fileImg', 'status_tax_fileImg', 'company_name', 'account_number', 'bank_name', 'linked_bank_number', 'linked_bank_address', 'linked_bank_fileImg', 'company_type', 'company_website', 'company_sale', 'shop_seller_have_experience', 'shop_website', 'shop_employee_num', 'shop_sale_num', 'shop_average_price', 'shop_warehouse_condition', 'shop_warehouse_address', 'shop_delicery_company', 'shop_erp_type', 'shop_operating_company', 'shop_buy_ecmoban_store', 'shop_buy_delivery', 'preVendorId', 'preVendorId_fileImg', 'shop_vertical', 'registered_capital', 'contactXinbie');
	protected $guarded = array();
}

?>
