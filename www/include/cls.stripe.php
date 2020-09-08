<?php
/**
 * Stripe.api_key = "sk_test_WZ1opYtvcLQqaH2MWxXyPT4l"
 */

class stripe {
	public $stripe_url='https://api.stripe.com/v1/';
	public $APIKEY="sk_test_WZ1opYtvcLQqaH2MWxXyPT4l";
	
	/**
	 * 信用卡支付
	 * card_info=array(
	   "amount"=>1000,
	   "email"=>test@gmail.com,
	   "number"=>"4242424242424242",
	   "exp_month"=>"12",
	   "exp_year"=>"2017",
	   "cvc"=>"123"
	   );
	 */
	function pay_card($card_info,$user_token=false,$status=null)
	{
		$card_info['pay_card']=true;
		
		//新用户建立帐号
		if(empty($user_token))
		{
			$rs_customer=$this->createCustomer($card_info);
			$user_token=$rs_customer['id'];
		}
		
		$user_info=$this->get_user_account($user_token);
		$card_info['user_token']=$user_token;
		
		//删除卡号
		if($status=='delete')
		{
			$card_info['source']=$user_info['source_card']['id'];
			$rs_payment=$this->deletePayment($card_info);
		
			return $rs_payment;
		}
		
		//更改用户信息
		if($card_info['email']!=$user_info['email'] || $card_info['phone']!=$user_info['phone'])
			$rs_customer=$this->updateCustomer($card_info);
		
		//获取用户资料
		$user_info=$this->get_user_account($user_token);
		$card_info['user_token']=$user_token;
		
		//绑定卡号
		if($status=='create')
		{
			$rs_payment=$this->updatePayment($card_info);
			$user_info=$this->get_user_account($user_token);
			
			//绑定错误提示
			if(!empty($rs_payment['error']))
			{
				$rs['charge_info']=$rs_payment['error'];
				$rs['user_info']=$user_info;
					
				return $rs;
			}
		}
		
		//进行支付
		$payment_info['amount']=$card_info['amount'];
		$payment_info['user_token']=$user_info['token'];
		$payment_info['source']=$user_info['source_card']['id'];
		
		$charge_info=$this->createCharge($payment_info);
		
		$rs['charge_info']=$charge_info;
		$rs['user_info']=$user_info;
		
		return $rs;
	}
	
	/**
	 * 支票支付
	 * $bankaccount_info=array(
	 "account_holder_name"=>"Jane Austen",
	 "account_number"=>"000123456789"
	 "routing_number"=>"110000000",
	 );
	 	
	 $bankaccount_info['country']='US';
	 $bankaccount_info['currency']="usd";
	 $bankaccount_info['account_holder_type']="individual";
	 	
	 */
	function pay_bankaccount($bankaccount_info,$user_token=false,$status=null)
	{
		$bankaccount_info['pay_bankaccount']=true;
		
		//新用户建立帐号
		if(empty($user_token))
		{
			$rs_customer=$this->createCustomer($bankaccount_info);
			$user_token=$rs_customer['id'];
		}
		
		//获取用户资料
		$user_info=$this->get_user_account($user_token);
		$bankaccount_info['user_token']=$user_token;
		
		//绑定支票
		if($status=='create')
			$rs_payment=$this->updatePayment($bankaccount_info);
		//获取支付id
		else			
			$bankaccount_info['source']=$user_info['source_bankaccount']['id'];
		
		//验证支票
		if($status=='new')
			$rs_payment=$this->verifyBankAccount($bankaccount_info);
		
		//支票支付
		if($status=='verified')
			$rs_payment=$this->createCharge($bankaccount_info);
		
		//支票删除
		if($status=='delete')
			$rs_payment=$this->deletePayment($bankaccount_info);
		
		$rs['charge_info']=$rs_payment;
		$rs['user_info']=$user_info;
		
		return $rs;
	}
	
	//返回详细用户信息
	function get_user_account($user_token)
	{
		$rs_customer=$this->viewCustomer($user_token);
		
		if(!empty($rs_customer['error']))
			return array();
	
		$rs=array(
				'token'=>$rs_customer['id'],
				'email'=>$rs_customer['email'],
				'phone'=>$rs_customer['metadata']['phone']);
	
		foreach($rs_customer['sources']['data'] as $v)
		{
			if($v['object']=='card')
			{
				$source_card['id']=$v['id'];
				$source_card['exp_month']=$v['exp_month'];
				$source_card['exp_year']=$v['exp_year'];
				$source_card['last4']=$v['last4'];
				$source_card['cvc']="***";
				$source_card['number']="**** **** **** {$v['last4']}";
				
				$rs['source_card']=$source_card;
			}
				
			if($v['object']=='bank_account')
			{
				$source_bankaccount['id']=$v['id'];
				$source_bankaccount['routing_number']=$v['routing_number'];
				$source_bankaccount['account_holder_name']=$v['account_holder_name'];
				$source_bankaccount['last4']=$v['last4'];
				$source_bankaccount['account_number']="**** **** {$v['last4']}";
				$source_bankaccount['status']=$v['status'];
				
				$rs['source_bankaccount']=$source_bankaccount;
			}
		}
	
		return $rs;
	}
	
	function update_user($user_info,$user_token=false)
	{
		//新用户建立帐号
		if(empty($user_token))
		{
			$rs_customer=$this->createCustomer($card_info);
			$user_token=$rs_customer['id'];
		}
		
		$user_info['user_token']=$user_token;
		
		$rs=$this->updateCustomer($user_info);
		
		return $rs;
	}
	
	
	
	
	
	
	
	
	
	private function curl($option, $data=null, $method="POST")
	{
		$url = $this->stripe_url.$option;
	
		$ci = curl_init($url);
		curl_setopt( $ci, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ci, CURLOPT_FORBID_REUSE, 0 );
		curl_setopt( $ci, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt( $ci, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$this->APIKEY}"));
	
		if(!empty($data))
		{
			if(is_array($data))
				$q = http_build_query($data);
			else
				$q=$data;
	
			curl_setopt( $ci, CURLOPT_POSTFIELDS, $q);
		}
		
		$rs_obj=json_decode(curl_exec($ci));
		$rs=$this->objToArray($rs_obj,$rs);
		
		return  $rs;
	}
	
	//信用卡支付
	private function createCharge($payment_info)
	{
		$data=array(
			"amount"=>$payment_info['amount'],
			"currency"=>"usd",
			'source'=>$payment_info['source'],
			"customer"=>$payment_info['user_token']);
		
		$option = "charges";
		
		$rs=$this->curl($option, $data);
		return $rs;
	}
	
	//创建用户
	private function createCustomer($payment_info)
	{
		$data=array('email'=>$payment_info['email'],'metadata'=>array('phone'=>$payment_info['phone']));
	
		$option = "customers";
		$rs=$this->curl($option, $data);
		
		return $rs;
	}
	
	//更改用户信息
	private function updateCustomer($user_info)
	{
		$data=array('email'=>$user_info['email'],'metadata'=>array('phone'=>$user_info['phone']));
		
		$option = "customers/{$user_info['user_token']}";
		$rs=$this->curl($option, $data);
		
		return $rs;
	}
	
	//更改支付信息
	private function updatePayment($payment_info)
	{
		//更改信用卡信息
		if(!empty($payment_info['pay_card']))
			$source=$this->init_source_card($payment_info);
		
		//更改支票信息
		if(!empty($payment_info['pay_bankaccount']))
			$source=$this->init_source_bankaccount($payment_info);
		
		$data=array('source'=>$source);
		$option = "customers/{$payment_info['user_token']}/sources";
		
		$rs=$this->curl($option, $data);
		
		return $rs;
	}
	
	//删除原有支付信息
	private function deletePayment($payment_info)
	{
		$option = "customers/{$payment_info['user_token']}/sources/{$payment_info['source']}";
		$rs=$this->curl($option, $data=null, "DELETE");
	}
	
	//信用卡必填信息
	private function init_source_card($card_info)
	{
		$source=array(
			'object'=>'card',
			'number'=>$card_info['number'],
			'exp_month'=>$card_info['exp_month'],
			'exp_year'=>$card_info['exp_year'],
			'cvc'=>$card_info['cvc']);
		
		return $source;
	}
	
	//支票必填信息
	private function init_source_bankaccount($bankaccount_info)
	{
		$source=array(
			'object'=>'bank_account',
			'country'=>'US',
			'currency'=>'usd',
			'account_holder_type'=>'individual',
			'account_holder_name'=>$bankaccount_info['account_holder_name'],
			"account_number"=>$bankaccount_info['account_number'],
			'routing_number'=>$bankaccount_info['routing_number']);
	
		return $source;
	}
	
	//查看用户
	private function viewCustomer($user_token)
	{
		$option = "customers".'/'.$user_token;
		return $this->curl($option);
	}
	
	//银行账号验证
	private function verifyBankAccount($bankaccount_info) 
	{
		$data="amounts[]={$bankaccount_info['deposite1']}&amounts[]={$bankaccount_info['deposite2']}";
		$option="customers/{$bankaccount_info['user_token']}/sources/{$bankaccount_info['source']}/verify";
		
		$rs=$this->curl($option, $data);
		
		return $rs;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function retrieveCharge($data) {
		$url="charges/{$data}";
		$this->curl($url, NULL);
	}
	
	private function refund($data) {
		$url = "refunds";
		$this->curl($url, $data);
	}
	
	//创建token：1.信用卡，2.银行账号
	private function createToken($data, $type) {
		$url = "tokens";
		
		//Create a card token
		if($type == "card") {
			$value=array(
					"card"=>$data
			);
		}
		//Create a bank account token
		elseif($type == "bank_account") {
			$value=array(
					"bank_account"=>$data
			);
		}
		
		$rs=$this->curl($url, $value);
		
		return $rs;
	}
	
	function objToArray($obj, &$arr)
	{
		if(!is_object($obj) && !is_array($obj)){
			$arr = $obj;
			return $arr;
		}
	
		foreach ($obj as $key => $value)
		{
			if (!empty($value))
			{
				$arr[$key] = array();
				$this->objToArray($value, $arr[$key]);
			}
			else
			{
				$arr[$key] = $value;
			}
		}
		return $arr;
	}
	
	
	
	function retrieveBalance($transactionid) {
		$option="balance/history/".$transactionid;
		return $this->curl($option);
	}
	
	function listCharge($data) {
		$request = "customer=".$data;
		$option="charges?limit=100&".$request."&include[]=total_count";
	
		return $this->curl($option,NULL,"GET");
	}
	
}